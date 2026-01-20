<?php

namespace App\Http\Controllers;

use App\Models\ExpenseBudget;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected function flushPendingDue(): void
    {
        $userId = Auth::id();
        if (! $userId) {
            return;
        }

        $key = 'pending_transactions:'.$userId;
        $items = session()->get($key, []);
        if (! is_array($items) || $items === []) {
            return;
        }

        $today = now()->startOfDay();
        $keep = [];

        foreach ($items as $it) {
            if (! is_array($it)) {
                continue;
            }

            $rawDate = $it['date'] ?? null;
            if (! $rawDate) {
                $keep[] = $it;

                continue;
            }

            try {
                $date = Carbon::parse($rawDate)->startOfDay();
            } catch (\Throwable $e) {
                $keep[] = $it;

                continue;
            }

            if ($date->greaterThan($today)) {
                $keep[] = $it;

                continue;
            }

            $type = $it['type'] ?? null;
            if (! in_array($type, ['income', 'expense'], true)) {
                $keep[] = $it;

                continue;
            }

            $amount = $it['amount'] ?? 0;
            if (is_string($amount)) {
                $amount = preg_replace('/[^0-9.]/', '', $amount);
            }
            if (! is_numeric($amount) || (float) $amount < 0) {
                $keep[] = $it;

                continue;
            }

            $category = (string) ($it['category'] ?? '');
            if (trim($category) === '') {
                $category = 'Lainnya';
            }

            try {
                Transaction::create([
                    'user_id' => $userId,
                    'type' => $type,
                    'amount' => (float) $amount,
                    'category' => $category,
                    'date' => $date->toDateString(),
                    'description' => isset($it['description']) ? (string) $it['description'] : null,
                ]);
            } catch (\Throwable $e) {
                $keep[] = $it;
            }
        }

        session()->put($key, $keep);
    }

    public function index()
    {
        $this->flushPendingDue();
        $userId = Auth::id();
        $income = $userId ? (float) Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount') : 0.0;
        $expense = $userId ? (float) Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount') : 0.0;
        $availableBalance = $income - $expense;
        $categories = [
            'Gaji Bulanan',
            'Makanan',
            'Transportasi',
            'Hiburan',
            'Tagihan',
            'Lainnya',
        ];

        return view('catat', [
            'categories' => $categories,
            'availableBalance' => $availableBalance,
        ]);
    }

    public function budget(Request $request)
    {
        $this->flushPendingDue();
        $userId = Auth::id();

        $period = $request->query('period', 'month');
        $preset = $request->query('preset');
        $year = (int) ($request->query('year') ?? now()->year);
        $month = (int) ($request->query('month') ?? now()->month);
        $startParam = $request->query('start');
        $endParam = $request->query('end');

        $now = now();
        if ($preset === 'previous') {
            $s = $now->copy()->subMonth()->startOfMonth();
            $e = $now->copy()->subMonth()->endOfMonth();
            $period = 'month';
        } elseif ($period === 'week') {
            $s = $now->copy()->startOfWeek();
            $e = $now->copy()->endOfWeek();
        } elseif ($period === 'custom' && $startParam && $endParam) {
            $s = Carbon::parse($startParam)->startOfDay();
            $e = Carbon::parse($endParam)->endOfDay();
        } else {
            $s = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $e = $s->copy()->endOfMonth();
            $period = 'month';
        }

        if ($period === 'custom') {
            $days = $s->copy()->startOfDay()->diffInDays($e->copy()->endOfDay()) + 1;
            $ps = $s->copy()->subDays($days);
            $pe = $s->copy()->subDay()->endOfDay();
        } elseif ($period === 'week') {
            $ps = $s->copy()->subWeek();
            $pe = $e->copy()->subWeek();
        } else {
            $ps = $s->copy()->subMonth()->startOfMonth();
            $pe = $s->copy()->subMonth()->endOfMonth();
        }

        $budgetRows = ExpenseBudget::where('user_id', $userId)->get(['label', 'max_amount']);
        $budgetByCategory = [];
        $totalBudget = 0;
        foreach ($budgetRows as $r) {
            $budgetByCategory[(string) $r->label] = (int) $r->max_amount;
            $totalBudget += (int) $r->max_amount;
        }

        $spent = (float) Transaction::where('user_id', $userId)->where('type', 'expense')
            ->whereBetween('date', [$s->toDateString(), $e->toDateString()])
            ->sum('amount');
        $prevSpent = (float) Transaction::where('user_id', $userId)->where('type', 'expense')
            ->whereBetween('date', [$ps->toDateString(), $pe->toDateString()])
            ->sum('amount');

        $spentChange = $prevSpent > 0 ? (($spent - $prevSpent) / $prevSpent) * 100 : 0.0;
        $remaining = $totalBudget > 0 ? ($totalBudget - $spent) : 0.0;
        $usagePercent = $totalBudget > 0 ? ($spent / $totalBudget) * 100 : 0.0;

        $currByCategoryRows = Transaction::selectRaw('category as c, SUM(amount) as total')
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$s->toDateString(), $e->toDateString()])
            ->groupBy('c')
            ->orderByDesc('total')
            ->get();
        $prevByCategoryRows = Transaction::selectRaw('category as c, SUM(amount) as total')
            ->where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$ps->toDateString(), $pe->toDateString()])
            ->groupBy('c')
            ->get();

        $currSpentMap = [];
        foreach ($currByCategoryRows as $r) {
            $currSpentMap[(string) $r->c] = (float) $r->total;
        }
        $prevSpentMap = [];
        foreach ($prevByCategoryRows as $r) {
            $prevSpentMap[(string) $r->c] = (float) $r->total;
        }

        $distinctCategories = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->map(fn ($v) => (string) $v)
            ->all();

        $allCategories = collect(array_unique(array_merge(array_keys($budgetByCategory), array_keys($currSpentMap))))
            ->filter(fn ($v) => is_string($v) && trim($v) !== '')
            ->values()
            ->all();

        $colorForLabel = function (string $label): string {
            $h = 0;
            $len = strlen($label);
            for ($i = 0; $i < $len; $i++) {
                $h = ($h * 31 + ord($label[$i])) % 360;
            }

            return "hsl($h, 70%, 55%)";
        };

        $categoryItems = [];
        foreach ($allCategories as $label) {
            $spentCat = (float) ($currSpentMap[$label] ?? 0.0);
            $max = (int) ($budgetByCategory[$label] ?? 0);
            $prevCat = (float) ($prevSpentMap[$label] ?? 0.0);
            $change = $prevCat > 0 ? (($spentCat - $prevCat) / $prevCat) * 100 : 0.0;

            $fillPercent = 0;
            $usage = 0.0;
            if ($max > 0) {
                $usage = ($spentCat / $max) * 100;
                $fillPercent = (int) max(0, min(100, round($usage)));
            } elseif ($spent > 0) {
                $fillPercent = (int) max(0, min(100, round(($spentCat / $spent) * 100)));
            }

            $icon = 'fa-wallet';
            $needle = Str::lower($label);
            if (Str::contains($needle, ['makan', 'minum', 'kuliner'])) {
                $icon = 'fa-utensils';
            } elseif (Str::contains($needle, ['transport', 'bensin', 'ojek', 'bus', 'kereta'])) {
                $icon = 'fa-car-side';
            } elseif (Str::contains($needle, ['hiburan', 'entertain', 'game', 'film'])) {
                $icon = 'fa-ticket';
            } elseif (Str::contains($needle, ['tagihan', 'listrik', 'air', 'internet', 'cicilan'])) {
                $icon = 'fa-file-invoice-dollar';
            } elseif (Str::contains($needle, ['belanja', 'shopping'])) {
                $icon = 'fa-bag-shopping';
            } elseif (Str::contains($needle, ['spp', 'sekolah', 'pendidikan', 'kampus'])) {
                $icon = 'fa-graduation-cap';
            } elseif (Str::contains($needle, ['kesehatan', 'dokter', 'obat', 'rumah sakit'])) {
                $icon = 'fa-briefcase-medical';
            } elseif (Str::contains($needle, ['donasi', 'amal', 'zakat'])) {
                $icon = 'fa-hand-holding-heart';
            } elseif (Str::contains($needle, ['utang', 'pinjam', 'kredit'])) {
                $icon = 'fa-money-bill-transfer';
            }

            $categoryItems[] = [
                'label' => $label,
                'spent' => $spentCat,
                'max' => $max,
                'remaining' => $max > 0 ? ($max - $spentCat) : null,
                'usagePercent' => $max > 0 ? $usage : null,
                'fillPercent' => $fillPercent,
                'changePercent' => $change,
                'icon' => $icon,
                'color' => $colorForLabel($label),
            ];
        }

        usort($categoryItems, function ($a, $b) {
            $aUn = ((int) ($a['max'] ?? 0) <= 0) ? 1 : 0;
            $bUn = ((int) ($b['max'] ?? 0) <= 0) ? 1 : 0;
            if ($aUn !== $bUn) {
                return $bUn <=> $aUn;
            }

            return $b['spent'] <=> $a['spent'];
        });

        $minDate = Transaction::where('user_id', $userId)->min('date');
        $minYear = $minDate ? Carbon::parse($minDate)->year : (int) $now->year;
        $years = range((int) $now->year, (int) $minYear);

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $periodLabel = $period === 'custom'
            ? ($s->format('d/m/Y').' - '.$e->format('d/m/Y'))
            : ($period === 'week' ? 'Minggu Ini' : (($months[(int) $s->month] ?? $s->format('F')).' '.$s->year));
        $prevLabel = $period === 'custom'
            ? ($ps->format('d/m/Y').' - '.$pe->format('d/m/Y'))
            : ($period === 'week' ? 'Minggu Lalu' : (($months[(int) $ps->month] ?? $ps->format('F')).' '.$ps->year));

        return view('budget', [
            'filters' => [
                'period' => $period,
                'preset' => $preset,
                'year' => (int) $s->year,
                'month' => (int) $s->month,
                'start' => $s->toDateString(),
                'end' => $e->toDateString(),
                'years' => $years,
                'months' => $months,
            ],
            'summary' => [
                'totalBudget' => (float) $totalBudget,
                'spent' => (float) $spent,
                'remaining' => (float) $remaining,
                'usagePercent' => (float) $usagePercent,
                'spentChangePercent' => (float) $spentChange,
                'periodLabel' => $periodLabel,
                'prevLabel' => $prevLabel,
            ],
            'categories' => $categoryItems,
        ]);
    }

    public function all()
    {
        $this->flushPendingDue();
        $userId = Auth::id();
        $transactions = Transaction::where('user_id', $userId)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return view('transactions', compact('transactions'));
    }

    public function store(Request $request)
    {
        $rawAmount = $request->input('amount_numeric', $request->input('amount'));
        if (is_string($rawAmount)) {
            $rawAmount = preg_replace('/[^0-9.]/', '', $rawAmount);
        }
        $request->merge(['amount' => $rawAmount]);

        $data = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $data['user_id'] = Auth::id();

        if ($data['type'] === 'expense') {
            $userId = $data['user_id'];
            $income = $userId ? (float) Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount') : 0.0;
            $expense = $userId ? (float) Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount') : 0.0;
            $available = $income - $expense;
            if ((float) $data['amount'] > max(0.0, $available)) {
                return redirect()->route('catat')->with([
                    'insufficient_balance' => true,
                    'insufficient_available' => max(0.0, $available),
                    'insufficient_requested' => (float) $data['amount'],
                ]);
            }
        }

        $entryDate = Carbon::parse($data['date'])->startOfDay();
        $today = now()->startOfDay();

        if ($entryDate->greaterThan($today)) {
            $key = 'pending_transactions:'.$data['user_id'];
            $pending = session()->get($key, []);
            $pending[] = [
                'id' => (string) Str::uuid(),
                'type' => (string) $data['type'],
                'amount' => (float) $data['amount'],
                'category' => (string) $data['category'],
                'date' => (string) $entryDate->toDateString(),
                'description' => isset($data['description']) ? (string) $data['description'] : null,
                'created_at' => now()->toDateTimeString(),
            ];
            session()->put($key, $pending);

            return redirect()->route('pending.index')->with('pending_saved', true);
        }

        Transaction::create($data);

        return redirect()->route('dashboard')->with('transaction_saved', true);
    }

    public function storeBulk(Request $request)
    {
        $raw = (string) ($request->input('items_json') ?? '');
        $items = [];
        try {
            $parsed = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            if (is_array($parsed)) {
                $items = $parsed;
            }
        } catch (\Throwable $e) {
            $items = [];
        }
        if ($items === []) {
            return redirect()->route('catat')->with('bulk_error', true);
        }

        $userId = Auth::id();
        $income = $userId ? (float) Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount') : 0.0;
        $expense = $userId ? (float) Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount') : 0.0;
        $runningAvailable = $income - $expense;
        $insufficientAmount = null;
        foreach ($items as $it) {
            $typePreview = isset($it['type']) ? (string) $it['type'] : null;
            $amountPreview = isset($it['amount']) ? $it['amount'] : null;
            if (is_string($amountPreview)) {
                $amountPreview = preg_replace('/[^0-9.]/', '', $amountPreview);
            }
            if (! is_numeric($amountPreview)) {
                continue;
            }
            $amountPreview = (float) $amountPreview;
            if ($typePreview === 'income') {
                $runningAvailable += $amountPreview;
            } elseif ($typePreview === 'expense') {
                if ($amountPreview > max(0.0, $runningAvailable)) {
                    $insufficientAmount = $amountPreview;
                    break;
                }
                $runningAvailable -= $amountPreview;
            }
        }
        if ($insufficientAmount !== null) {
            return redirect()->route('catat')->with([
                'insufficient_balance' => true,
                'insufficient_available' => max(0.0, $runningAvailable),
                'insufficient_requested' => $insufficientAmount,
            ]);
        }

        $today = now()->startOfDay();
        $pendingKey = 'pending_transactions:'.$userId;
        $pending = session()->get($pendingKey, []);

        foreach ($items as $it) {
            $type = isset($it['type']) ? (string) $it['type'] : null;
            $amount = isset($it['amount']) ? $it['amount'] : null;
            $category = isset($it['category']) ? (string) $it['category'] : null;
            $dateStr = isset($it['date']) ? (string) $it['date'] : null;
            $description = isset($it['description']) ? (string) $it['description'] : null;

            if (! in_array($type, ['income', 'expense'], true)) {
                continue;
            }
            if (is_string($amount)) {
                $amount = preg_replace('/[^0-9.]/', '', $amount);
            }
            if (! is_numeric($amount) || (float) $amount < 0) {
                continue;
            }
            if (! is_string($category) || trim($category) === '') {
                continue;
            }
            try {
                $entryDate = \Carbon\Carbon::parse($dateStr)->startOfDay();
            } catch (\Throwable $e) {
                continue;
            }

            if ($entryDate->greaterThan($today)) {
                $pending[] = [
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'type' => $type,
                    'amount' => (float) $amount,
                    'category' => $category,
                    'date' => (string) $entryDate->toDateString(),
                    'description' => $description,
                    'created_at' => now()->toDateTimeString(),
                ];

                continue;
            }

            try {
                Transaction::create([
                    'user_id' => $userId,
                    'type' => $type,
                    'amount' => (float) $amount,
                    'category' => $category,
                    'date' => $entryDate->toDateString(),
                    'description' => $description,
                ]);
            } catch (\Throwable $e) {
            }
        }

        session()->put($pendingKey, $pending);

        return redirect()->route('dashboard')->with('bulk_saved', true);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->flushPendingDue();
        if ((int) $transaction->user_id !== (int) Auth::id()) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $rawAmount = $request->input('amount_numeric', $request->input('amount'));
        if (is_string($rawAmount)) {
            $rawAmount = preg_replace('/[^0-9.]/', '', $rawAmount);
        }
        $request->merge(['amount' => $rawAmount]);

        $data = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $transaction->update([
            'type' => (string) $data['type'],
            'amount' => (float) $data['amount'],
            'category' => (string) $data['category'],
            'date' => Carbon::parse($data['date'])->toDateString(),
            'description' => $data['description'] ?? null,
        ]);

        return response()->json([
            'transaction' => [
                'id' => $transaction->id,
                'type' => $transaction->type,
                'amount' => (float) $transaction->amount,
                'category' => $transaction->category,
                'date' => Carbon::parse($transaction->date)->toDateString(),
                'description' => $transaction->description,
            ],
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        $this->flushPendingDue();
        if ((int) $transaction->user_id !== (int) Auth::id()) {
            return response()->json(['error' => 'forbidden'], 403);
        }

        $transaction->delete();

        return response()->json(['ok' => true]);
    }

    public function stats(Request $request)
    {
        $this->flushPendingDue();
        $type = $request->query('type');
        $granularity = $request->query('granularity');
        $year = (int) ($request->query('year') ?? now()->year);
        $month = (int) ($request->query('month') ?? now()->month);

        $userId = Auth::id();

        if (! in_array($type, ['income', 'expense', 'saving'], true)) {
            return response()->json(['error' => 'invalid type'], 422);
        }
        if (! in_array($granularity, ['daily', 'monthly', 'yearly'], true)) {
            return response()->json(['error' => 'invalid granularity'], 422);
        }

        if ($granularity === 'daily') {
            $start = now()->setYear($year)->setMonth($month)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            if ($type === 'saving') {
                $incomes = Transaction::selectRaw('DATE(date) as d, SUM(amount) as total')
                    ->where('user_id', $userId)
                    ->where('type', 'income')
                    ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                    ->groupBy('d')
                    ->get()
                    ->keyBy('d');
                $expenses = Transaction::selectRaw('DATE(date) as d, SUM(amount) as total')
                    ->where('user_id', $userId)
                    ->where('type', 'expense')
                    ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                    ->groupBy('d')
                    ->get()
                    ->keyBy('d');

                $dates = $incomes->keys()->merge($expenses->keys())->unique()->sort();

                $labels = [];
                $data = [];
                $sum = 0.0;
                foreach ($dates as $d) {
                    $inc = isset($incomes[$d]) ? (float) $incomes[$d]->total : 0.0;
                    $exp = isset($expenses[$d]) ? (float) $expenses[$d]->total : 0.0;
                    $net = $inc - $exp;
                    // $sum += $net; // Removed cumulative for daily consistency

                    $labels[] = \Carbon\Carbon::parse($d)->day;
                    $data[] = $net;
                }

                // Re-index labels/data to match frontend expectations (though frontend uses map)
                // But wait, existing code returns sequential arrays.
                return response()->json(['labels' => array_values($labels), 'data' => array_values($data)]);
            }

            $rows = Transaction::selectRaw('DATE(date) as d, SUM(amount) as total')
                ->where('user_id', $userId)
                ->where('type', $type)
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->groupBy('d')
                ->orderBy('d')
                ->get();

            $labels = [];
            $data = [];
            $sum = 0.0;
            foreach ($rows as $r) {
                $labels[] = \Carbon\Carbon::parse($r->d)->day;
                // $sum += (float) $r->total; // Removed cumulative
                $data[] = (float) $r->total;
            }

            return response()->json(['labels' => $labels, 'data' => $data]);
        }

        if ($granularity === 'monthly') {
            if ($type === 'saving') {
                $incomes = Transaction::selectRaw('MONTH(date) as m, SUM(amount) as total')
                    ->where('user_id', $userId)
                    ->where('type', 'income')
                    ->whereYear('date', $year)
                    ->groupBy('m')
                    ->get()
                    ->keyBy('m');
                $expenses = Transaction::selectRaw('MONTH(date) as m, SUM(amount) as total')
                    ->where('user_id', $userId)
                    ->where('type', 'expense')
                    ->whereYear('date', $year)
                    ->groupBy('m')
                    ->get()
                    ->keyBy('m');

                $labels = [];
                $data = [];
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                for ($m = 1; $m <= 12; $m++) {
                    $labels[] = $months[$m - 1];
                    $inc = isset($incomes[$m]) ? (float) $incomes[$m]->total : 0.0;
                    $exp = isset($expenses[$m]) ? (float) $expenses[$m]->total : 0.0;
                    $data[] = $inc - $exp;
                }

                return response()->json(['labels' => $labels, 'data' => $data]);
            }

            $rows = Transaction::selectRaw('MONTH(date) as m, SUM(amount) as total')
                ->where('user_id', $userId)
                ->where('type', $type)
                ->whereYear('date', $year)
                ->groupBy('m')
                ->orderBy('m')
                ->get();

            $labels = [];
            $data = [];
            $map = [];
            foreach ($rows as $r) {
                $map[(int) $r->m] = (float) $r->total;
            }
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = $months[$m - 1];
                $data[] = $map[$m] ?? 0.0;
            }

            return response()->json(['labels' => $labels, 'data' => $data]);
        }

        if ($type === 'saving') {
            $incomes = Transaction::selectRaw('YEAR(date) as y, SUM(amount) as total')
                ->where('user_id', $userId)
                ->where('type', 'income')
                ->groupBy('y')
                ->get()
                ->keyBy('y');
            $expenses = Transaction::selectRaw('YEAR(date) as y, SUM(amount) as total')
                ->where('user_id', $userId)
                ->where('type', 'expense')
                ->groupBy('y')
                ->get()
                ->keyBy('y');

            $years = $incomes->keys()->merge($expenses->keys())->unique()->sort();

            $labels = [];
            $data = [];
            foreach ($years as $y) {
                $labels[] = (string) $y;
                $inc = isset($incomes[$y]) ? (float) $incomes[$y]->total : 0.0;
                $exp = isset($expenses[$y]) ? (float) $expenses[$y]->total : 0.0;
                $data[] = $inc - $exp;
            }

            return response()->json(['labels' => array_values($labels), 'data' => array_values($data)]);
        }

        $rows = Transaction::selectRaw('YEAR(date) as y, SUM(amount) as total')
            ->where('user_id', $userId)
            ->where('type', $type)
            ->groupBy('y')
            ->orderBy('y')
            ->get();

        $labels = [];
        $data = [];
        foreach ($rows as $r) {
            $labels[] = (string) $r->y;
            $data[] = (float) $r->total;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }

    public function distribution(Request $request)
    {
        $this->flushPendingDue();
        $year = $request->query('year');
        $month = $request->query('month');
        $start = $request->query('start');
        $end = $request->query('end');
        $userId = Auth::id();

        $q = Transaction::selectRaw('category as c, SUM(amount) as total')
            ->where('user_id', $userId)
            ->where('type', 'expense');

        if ($start && $end) {
            $q->whereBetween('date', [$start, $end]);
        } elseif ($year) {
            $q->whereYear('date', (int) $year);
        }
        if ($month) {
            $q->whereMonth('date', (int) $month);
        }

        $rows = $q->groupBy('c')->orderByDesc('total')->get();

        $budgetLabels = ExpenseBudget::where('user_id', $userId)->pluck('label')->map(function ($v) {
            return (string) $v;
        })->all();
        $existing = [];
        foreach ($rows as $r) {
            $existing[] = (string) $r->c;
        }
        $missing = array_values(array_diff($budgetLabels, $existing));
        foreach ($missing as $lbl) {
            $rows->push((object) ['c' => (string) $lbl, 'total' => 0.0]);
        }

        $colorForLabel = function (string $label): string {
            $h = 0;
            $len = strlen($label);
            for ($i = 0; $i < $len; $i++) {
                $h = ($h * 31 + ord($label[$i])) % 360;
            }

            return "hsl($h, 70%, 55%)";
        };

        $labels = [];
        $totals = [];
        $icons = [];
        $colors = [];
        $sum = 0.0;

        foreach ($rows as $r) {
            $label = (string) $r->c;
            $labels[] = $label;
            $totals[] = (float) $r->total;
            $sum += (float) $r->total;

            $icon = 'fa-wallet';
            $needle = Str::lower($label);
            if (Str::contains($needle, ['makan', 'minum', 'kuliner'])) {
                $icon = 'fa-utensils';
            } elseif (Str::contains($needle, ['transport', 'bensin', 'ojek', 'bus', 'kereta'])) {
                $icon = 'fa-car-side';
            } elseif (Str::contains($needle, ['hiburan', 'entertain', 'game', 'film'])) {
                $icon = 'fa-ticket';
            } elseif (Str::contains($needle, ['tagihan', 'listrik', 'air', 'internet', 'cicilan'])) {
                $icon = 'fa-file-invoice-dollar';
            } elseif (Str::contains($needle, ['belanja', 'shopping'])) {
                $icon = 'fa-bag-shopping';
            } elseif (Str::contains($needle, ['spp', 'sekolah', 'pendidikan', 'kampus'])) {
                $icon = 'fa-graduation-cap';
            } elseif (Str::contains($needle, ['kesehatan', 'dokter', 'obat', 'rumah sakit'])) {
                $icon = 'fa-briefcase-medical';
            } elseif (Str::contains($needle, ['donasi', 'amal', 'zakat'])) {
                $icon = 'fa-hand-holding-heart';
            } elseif (Str::contains($needle, ['utang', 'pinjam', 'kredit'])) {
                $icon = 'fa-money-bill-transfer';
            }
            $icons[] = $icon;
            $colors[] = $colorForLabel($label);
        }
        $percents = [];
        foreach ($totals as $t) {
            $percents[] = $sum > 0 ? round(($t / $sum) * 100, 2) : 0.0;
        }

        return response()->json([
            'labels' => $labels,
            'totals' => $totals,
            'percents' => $percents,
            'icons' => $icons,
            'colors' => $colors,
            'sum' => $sum,
        ]);
    }

    public function budgetsGet(Request $request)
    {
        $userId = Auth::id();
        $rows = ExpenseBudget::where('user_id', $userId)->orderBy('label')->get(['label', 'max_amount']);
        $data = [];
        foreach ($rows as $r) {
            $data[$r->label] = (int) $r->max_amount;
        }

        return response()->json(['budgets' => $data]);
    }

    public function budgetsSave(Request $request)
    {
        $userId = Auth::id();
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'max_amount' => ['nullable', 'integer', 'min:0'],
            'action' => ['nullable', 'string', 'in:create,update,delete'],
        ]);
        $max = (int) ($data['max_amount'] ?? 0);
        $action = $data['action'] ?? null;
        if ($action === 'delete' || $max <= 0) {
            ExpenseBudget::where('user_id', $userId)->where('label', $data['label'])->delete();
        } else {
            ExpenseBudget::updateOrCreate(
                ['user_id' => $userId, 'label' => $data['label']],
                ['max_amount' => $max]
            );
        }

        return response()->json(['ok' => true]);
    }

    public function summary(Request $request)
    {
        $this->flushPendingDue();
        $period = $request->query('period', 'month');
        $start = $request->query('start');
        $end = $request->query('end');
        $now = now();
        if ($period === 'week') {
            $s = $now->copy()->startOfWeek();
            $e = $now->copy()->endOfWeek();
            $ps = $s->copy()->subWeek();
            $pe = $e->copy()->subWeek();
        } elseif ($period === 'custom' && $start && $end) {
            $s = \Carbon\Carbon::parse($start);
            $e = \Carbon\Carbon::parse($end);
            $days = $s->diffInDays($e) + 1;
            $ps = $s->copy()->subDays($days);
            $pe = $s->copy()->subDay();
        } else {
            $s = $now->copy()->startOfMonth();
            $e = $now->copy()->endOfMonth();
            $ps = $s->copy()->subMonth()->startOfMonth();
            $pe = $s->copy()->subMonth()->endOfMonth();
        }

        $userId = Auth::id();

        $income = (float) Transaction::where('user_id', $userId)->where('type', 'income')
            ->whereBetween('date', [$s->toDateString(), $e->toDateString()])->sum('amount');
        $expense = (float) Transaction::where('user_id', $userId)->where('type', 'expense')
            ->whereBetween('date', [$s->toDateString(), $e->toDateString()])->sum('amount');

        $prevIncome = (float) Transaction::where('user_id', $userId)->where('type', 'income')
            ->whereBetween('date', [$ps->toDateString(), $pe->toDateString()])->sum('amount');
        $prevExpense = (float) Transaction::where('user_id', $userId)->where('type', 'expense')
            ->whereBetween('date', [$ps->toDateString(), $pe->toDateString()])->sum('amount');

        $incomeChange = $prevIncome > 0 ? (($income - $prevIncome) / $prevIncome) * 100 : 0.0;
        $expenseChange = $prevExpense > 0 ? (($expense - $prevExpense) / $prevExpense) * 100 : 0.0;

        return response()->json([
            'range' => [$s->toDateString(), $e->toDateString()],
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'incomeChange' => round($incomeChange, 2),
            'expenseChange' => round($expenseChange, 2),
        ]);
    }

    public function report(Request $request)
    {
        $this->flushPendingDue();
        $userId = Auth::id();
        $transactions = Transaction::where('user_id', $userId)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->take(20)
            ->get();

        return view('reports', compact('transactions'));
    }

    public function pending()
    {
        $this->flushPendingDue();
        $userId = Auth::id();
        $key = 'pending_transactions:'.$userId;
        $items = session()->get($key, []);

        usort($items, function ($a, $b) {
            $da = $a['date'] ?? '';
            $db = $b['date'] ?? '';
            if ($da === $db) {
                return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
            }

            return strcmp($db, $da);
        });

        return view('pending', ['items' => $items]);
    }

    public function pendingUpdate(Request $request, string $id)
    {
        $userId = Auth::id();
        $key = 'pending_transactions:'.$userId;
        $items = session()->get($key, []);
        $data = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);
        $updated = false;
        foreach ($items as &$it) {
            if ((string) ($it['id'] ?? '') === (string) $id) {
                $it['type'] = (string) $data['type'];
                $it['amount'] = (float) $data['amount'];
                $it['category'] = (string) $data['category'];
                $it['date'] = (string) \Carbon\Carbon::parse($data['date'])->toDateString();
                $it['description'] = $data['description'] ?? null;
                $updated = true;
                break;
            }
        }
        unset($it);
        if ($updated) {
            session()->put($key, $items);
        }

        return redirect()->route('pending.index');
    }

    public function pendingDelete(Request $request, string $id)
    {
        $userId = Auth::id();
        $key = 'pending_transactions:'.$userId;
        $items = session()->get($key, []);
        $filtered = [];
        foreach ($items as $it) {
            if ((string) ($it['id'] ?? '') !== (string) $id) {
                $filtered[] = $it;
            }
        }
        session()->put($key, $filtered);

        return redirect()->route('pending.index');
    }
}
