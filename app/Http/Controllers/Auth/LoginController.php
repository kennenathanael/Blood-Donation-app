<?php
// ============================================================
// app/Http/Controllers/Auth/LoginController.php
// ============================================================
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show login form
     * GET /login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended(Auth::user()->isAdmin() ? '/admin/dashboard' : '/donor/dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login
     * POST /login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'Welcome back, ' . $user->name . '!');
            }

            return redirect()->intended('/donor/dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Logout
     * POST /logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out.');
    }
}
