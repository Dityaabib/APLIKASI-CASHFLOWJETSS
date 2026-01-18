<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\ExpenseBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        // Count online users (active within last 5 minutes) - ONLY Regular Users
        $onlineThreshold = now()->subMinutes(5);
        $onlineUsersCount = User::where('level', '!=', 'administrator')
            ->where('last_seen', '>=', $onlineThreshold)
            ->count();

        // Administrator: Online dulu, lalu terbaru, ambil lebih banyak agar bisa scroll
        $recentAdmins = User::where('level', 'administrator')
            ->orderByRaw("CASE WHEN last_seen >= ? THEN 1 ELSE 0 END DESC", [$onlineThreshold])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
        $recentAdmins->transform(function ($u) use ($onlineThreshold) {
            $last = $u->last_seen ? \Carbon\Carbon::parse($u->last_seen) : null;
            $u->is_online = $last ? $last->gte($onlineThreshold) : false;
            return $u;
        });

        // Regular Users: Sorted by Online Status (Online First) then Created At
        $recentUsers = User::where('level', '!=', 'administrator')
            ->orderByRaw("CASE WHEN last_seen >= ? THEN 1 ELSE 0 END DESC", [$onlineThreshold])
            ->orderBy('created_at', 'desc')
            ->take(50) // Increased limit to allow scrolling in dashboard
            ->get();
        $recentUsers->transform(function ($u) use ($onlineThreshold) {
            $last = $u->last_seen ? \Carbon\Carbon::parse($u->last_seen) : null;
            $u->is_online = $last ? $last->gte($onlineThreshold) : false;
            return $u;
        });

        return view('admin.dashboard_admin', compact('totalUsers', 'onlineUsersCount', 'recentAdmins', 'recentUsers'));
    }

    public function users(Request $request)
    {
        $q = (string) ($request->query('q') ?? '');
        $level = (string) ($request->query('level') ?? '');
        $status = (string) ($request->query('status') ?? '');

        $totalUsers = User::count();
        $totalAdmins = User::where('level', 'administrator')->count();
        $totalRegulars = User::where('level', '!=', 'administrator')->count();
        $onlineThreshold = now()->subMinutes(5);
        $onlineRegulars = User::where('level', '!=', 'administrator')
            ->where('last_seen', '>=', $onlineThreshold)
            ->count();

        $query = User::query();

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', '%' . $q . '%')
                    ->orWhere('username', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        if ($level === 'administrator') {
            $query->where('level', 'administrator');
        } elseif ($level === 'regular') {
            $query->where('level', '!=', 'administrator');
        }

        if ($status === 'online') {
            $query->where('last_seen', '>=', $onlineThreshold);
        }

        $users = $query->orderByDesc('created_at')->paginate(12)->withQueryString();
        $users->getCollection()->transform(function ($u) use ($onlineThreshold) {
            $last = $u->last_seen ? \Carbon\Carbon::parse($u->last_seen) : null;
            $u->is_online = $last ? $last->gte($onlineThreshold) : false;
            return $u;
        });

        if ($request->ajax()) {
            $items = [];
            foreach ($users->getCollection() as $u) {
                $raw = trim(preg_replace('/\s+/u', ' ', (string) $u->name));
                $display = mb_strlen($raw) > 23 ? (mb_substr($raw, 0, 10) . '…' . mb_substr($raw, -8)) : $raw;
                $items[] = [
                    'id' => (int) $u->id,
                    'display_name' => $display,
                    'initial' => strtoupper(mb_substr($raw, 0, 1)),
                    'sub' => $u->username ?? $u->email,
                    'level' => (string) $u->level,
                    'is_online' => (bool) $u->is_online,
                    'created_human' => $u->created_at ? $u->created_at->diffForHumans(null, true) : '-',
                    'avatar' => $u->avatar,
                    'avatar_url' => $u->avatar ? asset('avatars/' . $u->avatar) : null,
                ];
            }
            return response()->json([
                'items' => $items,
                'total' => $users->total(),
            ]);
        }

        return view('admin.users', compact('totalUsers', 'totalAdmins', 'totalRegulars', 'onlineRegulars', 'users', 'q', 'level', 'status'));
    }

    public function onlineCount(): \Illuminate\Http\JsonResponse
    {
        $onlineThreshold = now()->subMinutes(5);
        $count = User::where('level', '!=', 'administrator')
            ->where('last_seen', '>=', $onlineThreshold)
            ->count();
        return response()->json(['count' => $count]);
    }

    public function analysis()
    {
        $totalTransactions = Transaction::count();
        $totalIncome = (float) Transaction::where('type', 'income')->sum('amount');
        $totalExpense = (float) Transaction::where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $now = now();
        $cmStart = $now->copy()->startOfMonth()->toDateString();
        $cmEnd = $now->copy()->endOfMonth()->toDateString();
        $pmStart = $now->copy()->subMonth()->startOfMonth()->toDateString();
        $pmEnd = $now->copy()->subMonth()->endOfMonth()->toDateString();

        $cmIncome = (float) Transaction::where('type', 'income')->whereBetween('date', [$cmStart, $cmEnd])->sum('amount');
        $cmExpense = (float) Transaction::where('type', 'expense')->whereBetween('date', [$cmStart, $cmEnd])->sum('amount');
        $pmIncome = (float) Transaction::where('type', 'income')->whereBetween('date', [$pmStart, $pmEnd])->sum('amount');
        $pmExpense = (float) Transaction::where('type', 'expense')->whereBetween('date', [$pmStart, $pmEnd])->sum('amount');

        $daysInMonth = (int) $now->daysInMonth;
        $dailyIncome = array_fill(1, $daysInMonth, 0);
        $dailyExpense = array_fill(1, $daysInMonth, 0);

        $incomeRows = Transaction::selectRaw('DAY(date) as d, SUM(amount) as total')
            ->where('type', 'income')
            ->whereBetween('date', [$cmStart, $cmEnd])
            ->groupBy('d')->get();
        foreach ($incomeRows as $r) {
            $day = (int) $r->d;
            if ($day >= 1 && $day <= $daysInMonth) {
                $dailyIncome[$day] = (float) $r->total;
            }
        }
        $expenseRows = Transaction::selectRaw('DAY(date) as d, SUM(amount) as total')
            ->where('type', 'expense')
            ->whereBetween('date', [$cmStart, $cmEnd])
            ->groupBy('d')->get();
        foreach ($expenseRows as $r) {
            $day = (int) $r->d;
            if ($day >= 1 && $day <= $daysInMonth) {
                $dailyExpense[$day] = (float) $r->total;
            }
        }

        $distRows = Transaction::selectRaw('category, SUM(amount) as total')
            ->where('type', 'expense')
            ->whereBetween('date', [$cmStart, $cmEnd])
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();
        $distTotal = 0;
        foreach ($distRows as $r) {
            $distTotal += (float) $r->total;
        }
        $topCategories = [];
        $others = 0;
        foreach ($distRows as $i => $r) {
            if ($i < 5) {
                $pct = $distTotal > 0 ? ((float) $r->total / $distTotal) * 100 : 0;
                $topCategories[] = [
                    'label' => (string) ($r->category ?? 'Lainnya'),
                    'amount' => (float) $r->total,
                    'percent' => (float) $pct,
                ];
            } else {
                $others += (float) $r->total;
            }
        }
        if ($others > 0) {
            $topCategories[] = [
                'label' => 'Lainnya',
                'amount' => (float) $others,
                'percent' => $distTotal > 0 ? ($others / $distTotal) * 100 : 0,
            ];
        }

        $onlineThreshold = now()->subMinutes(5);
        $onlineRegulars = User::where('level', '!=', 'administrator')->where('last_seen', '>=', $onlineThreshold)->count();
        $adminsOnline = User::where('level', 'administrator')->where('last_seen', '>=', $onlineThreshold)->count();
        $usersWithBudgets = ExpenseBudget::distinct()->count('user_id');

        $userAnalyses = collect();
        $allRegularUsers = User::where('level', '!=', 'administrator')
            ->orderBy('name')
            ->get();

        if ($allRegularUsers->isNotEmpty()) {
            $ids = $allRegularUsers->pluck('id')->all();
            $statRows = Transaction::selectRaw("
                    user_id,
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income_total,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense_total
                ")
                ->whereIn('user_id', $ids)
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');

            $currentMonth = now()->month;
            $currentYear = now()->year;

            $budgetRows = ExpenseBudget::selectRaw('user_id, SUM(max_amount) as total_budget')
                ->whereIn('user_id', $ids)
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');

            $budgetByUserAndLabel = ExpenseBudget::whereIn('user_id', $ids)
                ->select('user_id', 'label', 'max_amount')
                ->get()
                ->groupBy('user_id');

            $userAnalyses = $allRegularUsers->map(function ($u) use ($statRows, $budgetRows, $budgetByUserAndLabel, $currentMonth, $currentYear) {
                $row = $statRows->get($u->id);
                $income = $row ? (float) $row->income_total : 0.0;
                $expense = $row ? (float) $row->expense_total : 0.0;
                $totalBudget = 0.0;
                $budgetRow = $budgetRows->get($u->id);
                if ($budgetRow) {
                    $totalBudget = (float) $budgetRow->total_budget;
                }

                $budgetItems = [];
                $sumBudgetTracked = 0.0;
                $sumExpenseTracked = 0.0;
                $labelsForUser = $budgetByUserAndLabel->get($u->id);
                if ($labelsForUser) {
                    foreach ($labelsForUser as $b) {
                        $label = (string) ($b->label ?? '');
                        $max = (float) $b->max_amount;
                        if ($label === '' || $max <= 0) {
                            continue;
                        }
                        $spent = (float) Transaction::where('user_id', $u->id)
                            ->where('type', 'expense')
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->where('category', $label)
                            ->sum('amount');
                        $usagePercent = $max > 0 ? ($spent / $max) * 100 : 0.0;
                        $fillPercent = max(0.0, min(100.0, $usagePercent));
                        $budgetItems[] = [
                            'label' => $label,
                            'max' => $max,
                            'spent' => $spent,
                            'remaining' => $max - $spent,
                            'usagePercent' => $usagePercent,
                            'fillPercent' => $fillPercent,
                        ];
                        $sumBudgetTracked += $max;
                        $sumExpenseTracked += $spent;
                    }
                }

                $savings = $totalBudget - $sumExpenseTracked;
                $totalFlow = $income + $expense;
                $incomePercent = $totalFlow > 0 ? ($income / $totalFlow) * 100 : 0.0;
                $expensePercent = $totalFlow > 0 ? ($expense / $totalFlow) * 100 : 0.0;
                $savingPercent = $totalBudget > 0 ? ($savings / $totalBudget) * 100 : 0.0;

                $u->analysis_income = $income;
                $u->analysis_expense = $expense;
                $u->analysis_savings = $savings;
                $u->analysis_budget = $totalBudget;
                $u->analysis_savings_percentage = $savingPercent;
                $u->analysis_income_percentage = $incomePercent;
                $u->analysis_expense_percentage = $expensePercent;
                $u->analysis_budget_items = $budgetItems;
                return $u;
            });
        }

        $totalSavings = (float) $userAnalyses->sum(function ($u) {
            return (float) ($u->analysis_savings ?? 0.0);
        });

        return view('admin.analysis', [
            'totalTransactions' => (int) $totalTransactions,
            'totalIncome' => (float) $totalIncome,
            'totalExpense' => (float) $totalExpense,
            'netBalance' => (float) $netBalance,
            'cmIncome' => (float) $cmIncome,
            'cmExpense' => (float) $cmExpense,
            'pmIncome' => (float) $pmIncome,
            'pmExpense' => (float) $pmExpense,
            'dailyIncome' => $dailyIncome,
            'dailyExpense' => $dailyExpense,
            'topCategories' => $topCategories,
            'onlineRegulars' => (int) $onlineRegulars,
            'adminsOnline' => (int) $adminsOnline,
            'usersWithBudgets' => (int) $usersWithBudgets,
            'daysInMonth' => (int) $daysInMonth,
            'userAnalyses' => $userAnalyses,
            'totalSavings' => (float) $totalSavings,
        ]);
    }

    public function analysisNewPage(\Illuminate\Http\Request $request)
    {
        $userAnalyses = collect();
        $userId = $request->query('user');
        $allRegularUsersQuery = User::where('level', '!=', 'administrator')
            ->orderBy('name');

        if ($userId !== null && $userId !== '') {
            $allRegularUsersQuery->where('id', (int) $userId);
        }

        $allRegularUsers = $allRegularUsersQuery->get();

        if ($allRegularUsers->isNotEmpty()) {
            $ids = $allRegularUsers->pluck('id')->all();
            $statRows = Transaction::selectRaw("
                    user_id,
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income_total,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense_total
                ")
                ->whereIn('user_id', $ids)
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');

            $currentMonth = now()->month;
            $currentYear = now()->year;

            $budgetRows = ExpenseBudget::selectRaw('user_id, SUM(max_amount) as total_budget')
                ->whereIn('user_id', $ids)
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');

            $budgetByUserAndLabel = ExpenseBudget::whereIn('user_id', $ids)
                ->select('user_id', 'label', 'max_amount')
                ->get()
                ->groupBy('user_id');

            $userAnalyses = $allRegularUsers->map(function ($u) use ($statRows, $budgetRows, $budgetByUserAndLabel, $currentMonth, $currentYear) {
                $row = $statRows->get($u->id);
                $income = $row ? (float) $row->income_total : 0.0;
                $expense = $row ? (float) $row->expense_total : 0.0;
                $totalBudget = 0.0;
                $budgetRow = $budgetRows->get($u->id);
                if ($budgetRow) {
                    $totalBudget = (float) $budgetRow->total_budget;
                }

                $budgetItems = [];
                $sumBudgetTracked = 0.0;
                $sumExpenseTracked = 0.0;
                $labelsForUser = $budgetByUserAndLabel->get($u->id);
                if ($labelsForUser) {
                    foreach ($labelsForUser as $b) {
                        $label = (string) ($b->label ?? '');
                        $max = (float) $b->max_amount;
                        if ($label === '' || $max <= 0) {
                            continue;
                        }
                        $spent = (float) Transaction::where('user_id', $u->id)
                            ->where('type', 'expense')
                            ->whereMonth('date', $currentMonth)
                            ->whereYear('date', $currentYear)
                            ->where('category', $label)
                            ->sum('amount');
                        $usagePercent = $max > 0 ? ($spent / $max) * 100 : 0.0;
                        $fillPercent = max(0.0, min(100.0, $usagePercent));
                        $budgetItems[] = [
                            'label' => $label,
                            'max' => $max,
                            'spent' => $spent,
                            'remaining' => $max - $spent,
                            'usagePercent' => $usagePercent,
                            'fillPercent' => $fillPercent,
                        ];
                        $sumBudgetTracked += $max;
                        $sumExpenseTracked += $spent;
                    }
                }

                $savings = $totalBudget - $sumExpenseTracked;
                $totalFlow = $income + $expense;
                $incomePercent = $totalFlow > 0 ? ($income / $totalFlow) * 100 : 0.0;
                $expensePercent = $totalFlow > 0 ? ($expense / $totalFlow) * 100 : 0.0;
                $savingPercent = $totalBudget > 0 ? ($savings / $totalBudget) * 100 : 0.0;

                $u->analysis_income = $income;
                $u->analysis_expense = $expense;
                $u->analysis_savings = $savings;
                $u->analysis_budget = $totalBudget;
                $u->analysis_savings_percentage = $savingPercent;
                $u->analysis_income_percentage = $incomePercent;
                $u->analysis_expense_percentage = $expensePercent;
                $u->analysis_budget_items = $budgetItems;
                return $u;
            });
        }

        $totalSavings = (float) $userAnalyses->sum(function ($u) {
            return (float) ($u->analysis_savings ?? 0.0);
        });

        return view('admin.analysis_new_page', [
            'userAnalyses' => $userAnalyses,
            'totalSavings' => (float) $totalSavings,
        ]);
    }

    public function userTransactions(Request $request)
    {
        $q = (string) ($request->query('q') ?? '');
        $userId = $request->query('user');

        $query = User::where('level', '!=', 'administrator');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', '%' . $q . '%')
                    ->orWhere('username', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        if ($userId !== null && $userId !== '') {
            $query->where('id', (int) $userId);
        }

        $hasFilter = $q !== '' || ($userId !== null && $userId !== '');

        if ($hasFilter) {
            $ids = (clone $query)->pluck('id');
            $totalRegulars = $ids->count();
            if ($ids->isEmpty()) {
                $totalTransactions = 0;
                $totalAmount = 0.0;
            } else {
                $totalTransactions = Transaction::whereIn('user_id', $ids)->count();
                $totalAmount = (float) Transaction::whereIn('user_id', $ids)->sum('amount');
            }
        } else {
            $totalRegulars = User::where('level', '!=', 'administrator')->count();

            $totalTransactions = Transaction::whereHas('user', function ($u) {
                $u->where('level', '!=', 'administrator');
            })->count();

            $totalAmount = (float) Transaction::whereHas('user', function ($u) {
                $u->where('level', '!=', 'administrator');
            })->sum('amount');
        }

        $users = $query
            ->with(['transactions' => function ($t) {
                $t->orderByDesc('date');
            }])
            ->orderBy('name')
            ->paginate($request->ajax() ? 50 : 10)
            ->withQueryString();

        if ($request->ajax()) {
            $items = [];
            foreach ($users->getCollection() as $u) {
                $raw = trim(preg_replace('/\s+/u', ' ', (string) $u->name));
                $display = mb_strlen($raw) > 24 ? (mb_substr($raw, 0, 12) . '…' . mb_substr($raw, -10)) : $raw;

                $transactions = [];
                foreach ($u->transactions as $t) {
                    $transactions[] = [
                        'date' => $t->date ? $t->date->format('d M Y') : '-',
                        'type' => (string) $t->type,
                        'category' => (string) ($t->category ?? '-'),
                        'amount' => number_format((float) $t->amount, 0, ',', '.'),
                        'description' => (string) ($t->description ?? '-'),
                    ];
                }

                $items[] = [
                    'id' => (int) $u->id,
                    'display_name' => $display,
                    'sub' => $u->username ?? $u->email,
                    'is_online' => (bool) $u->is_online,
                    'avatar' => $u->avatar,
                    'avatar_url' => $u->avatar ? asset('avatars/' . $u->avatar) : null,
                    'created_human' => $u->created_at ? $u->created_at->diffForHumans(null, true) : '-',
                    'transactions' => $transactions,
                ];
            }

            return response()->json(['items' => $items]);
        }

        return view('admin.user_transactions', [
            'users' => $users,
            'q' => $q,
            'totalRegulars' => (int) $totalRegulars,
            'totalTransactions' => (int) $totalTransactions,
            'totalAmount' => (float) $totalAmount,
        ]);
    }

    public function createAdmin()
    {
        $onlineThreshold = now()->subMinutes(5);

        $admins = User::where('level', 'administrator')
            ->orderByRaw("CASE WHEN last_seen >= ? THEN 1 ELSE 0 END DESC", [$onlineThreshold])
            ->orderByDesc('created_at')
            ->take(50)
            ->get();

        $admins->transform(function ($u) use ($onlineThreshold) {
            $last = $u->last_seen ? \Carbon\Carbon::parse($u->last_seen) : null;
            $u->is_online = $last ? $last->gte($onlineThreshold) : false;
            return $u;
        });

        return view('admin.create_admin', [
            'admins' => $admins,
        ]);
    }

    public function updateAdmin(Request $request, User $user)
    {
        if ($user->level !== 'administrator') {
            abort(404);
        }

        $onlineThreshold = now()->subMinutes(5);
        $last = $user->last_seen ? \Carbon\Carbon::parse($user->last_seen) : null;
        $isOnline = $last ? $last->gte($onlineThreshold) : false;
        if ($isOnline) {
            return redirect()->route('admin.admins.create')->with('error', 'Administrator yang sedang online tidak dapat diedit.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('admin.admins.create')->with('success', 'Data administrator berhasil diperbarui.');
    }

    public function storeAdmin(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->password = Hash::make($data['password']);
        $user->level = 'administrator';
        $user->save();

        return redirect()->route('admin.admins.create')->with('success', 'Administrator baru berhasil ditambahkan.');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
