<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ExpenseBudget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = (bool) ($credentials['remember'] ?? false);
        unset($credentials['remember']);

        // 1. Cek apakah username ada di database terlebih dahulu
        $user = User::where('username', $credentials['username'])->first();

        // 2. Jika username tidak ditemukan, kembalikan error spesifik
        if (! $user) {
            return back()
                ->withInput($request->only('username'))
                ->with('error_type', 'username'); // Tanda error untuk username
        }

        // 3. Jika username ditemukan, sekarang cek kecocokan password
        if (! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->with('error_type', 'password'); // Tanda error untuk password
        }

        // 4. Jika username dan password cocok, lakukan login manual
        Auth::login($user, $remember);

        // Regenerasi session ID untuk keamanan
        $request->session()->regenerate();

        // Redirect ke halaman yang dituju setelah login
        if ($user->level === 'administrator') {
            return redirect()->intended('/admin');
        }
        return redirect()->intended('/dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('login')->with('just_registered', true);
    }

    public function showForgot()
    {
        return view('auth.forgot');
    }

    public function showReset(string $token)
    {
        return view('auth.reset', compact('token'));
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();

        if ($userId) {
            User::where('id', $userId)->update(['last_seen' => null]);
            Cache::forget('user-is-online-' . $userId);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard()
    {
        $userId = Auth::id();
        $transactions = Transaction::where('user_id', $userId)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->take(20)
            ->get();

        $transactionsTotalCount = Transaction::where('user_id', $userId)->count();

        $income = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $expense = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        $incomeTotal = $income;
        $expenseTotal = $expense;

        // Hitung Penghematan
        // 1. Total Budget (Maksimal Pengeluaran)
        $totalBudget = ExpenseBudget::where('user_id', $userId)->sum('max_amount');

        // 2. Kategori yang di-budgetkan
        $budgetedCategories = ExpenseBudget::where('user_id', $userId)->pluck('label')->toArray();

        // 3. Pengeluaran aktual bulan ini untuk kategori yang di-budgetkan
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $actualExpenseForBudgets = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->whereIn('category', $budgetedCategories)
            ->sum('amount');

        // 4. Savings = Total Budget - Actual Expenses
        $savings = $totalBudget - $actualExpenseForBudgets;

        return view('dashboard', compact('transactions', 'balance', 'incomeTotal', 'expenseTotal', 'transactionsTotalCount', 'savings'));
    }

    public function profile()
    {
        return view('profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path('avatars/' . $user->avatar))) {
                unlink(public_path('avatars/' . $user->avatar));
            }

            $image = $request->file('avatar');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('avatars'), $imageName);
            $user->avatar = $imageName;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }
}
