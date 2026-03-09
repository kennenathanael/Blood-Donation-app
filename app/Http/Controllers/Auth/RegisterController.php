<?php
// ============================================================
// app/Http/Controllers/Auth/RegisterController.php
// ============================================================
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show registration form
     * GET /register
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect('/donor/dashboard');
        }
        $bloodGroups = BloodGroup::all();
        return view('auth.register', compact('bloodGroups'));
    }

    /**
     * Handle registration
     * POST /register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users',
            'password'        => 'required|min:8|confirmed',
            'phone'           => 'nullable|string|max:20',
            'date_of_birth'   => 'nullable|date|before:-18 years',
            'gender'          => 'nullable|in:male,female,other',
            'blood_group_id'  => 'nullable|exists:blood_groups,id',
            'city'            => 'nullable|string|max:100',
        ], [
            'date_of_birth.before' => 'You must be at least 18 years old to donate blood.',
            'password.confirmed'   => 'Passwords do not match.',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => 'donor',
            'phone'          => $request->phone,
            'date_of_birth'  => $request->date_of_birth,
            'gender'         => $request->gender,
            'blood_group_id' => $request->blood_group_id,
            'city'           => $request->city,
        ]);

        Auth::login($user);

        return redirect('/donor/dashboard')
            ->with('success', 'Welcome to the Blood Donation Program, ' . $user->name . '!');
    }
}
