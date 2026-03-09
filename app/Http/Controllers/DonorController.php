<?php
// ============================================================
// app/Http/Controllers/DonorController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\BloodGroup;
use App\Models\CampaignRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DonorController extends Controller
{
    /**
     * Donor dashboard
     * GET /donor/dashboard
     */
    public function dashboard()
    {
        $user = Auth::user()->load('bloodGroup');

        // Statistics
        $totalRegistrations = CampaignRegistration::where('user_id', $user->id)->count();
        $pendingCount       = CampaignRegistration::where('user_id', $user->id)->where('status', 'pending')->count();
        $acceptedCount      = CampaignRegistration::where('user_id', $user->id)->where('status', 'accepted')->count();
        $donatedCount       = CampaignRegistration::where('user_id', $user->id)->where('status', 'donated')->count();

        // Recent registrations
        $recentRegistrations = CampaignRegistration::with('campaign')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Upcoming accepted donations
        $upcomingDonations = CampaignRegistration::with('campaign')
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->whereHas('campaign', fn($q) => $q->where('campaign_date', '>', now()))
            ->orderBy('created_at')
            ->get();

        // Unread notifications count
        $unreadNotifications = $user->unreadNotifications->count();

        return view('donor.dashboard', compact(
            'user',
            'totalRegistrations',
            'pendingCount',
            'acceptedCount',
            'donatedCount',
            'recentRegistrations',
            'upcomingDonations',
            'unreadNotifications'
        ));
    }

    /**
     * Show donor profile
     * GET /donor/profile
     */
    public function profile()
    {
        $user        = Auth::user()->load('bloodGroup');
        $bloodGroups = BloodGroup::all();
        return view('donor.profile', compact('user', 'bloodGroups'));
    }

    /**
     * Update profile
     * PUT /donor/profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'date_of_birth'    => 'nullable|date|before:-18 years',
            'gender'           => 'nullable|in:male,female,other',
            'blood_group_id'   => 'nullable|exists:blood_groups,id',
            'city'             => 'nullable|string|max:100',
            'address'          => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|string|max:1000',
            'profile_photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['profile_photo', '_method', '_token']);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change password
     * PUT /donor/password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Donor's registrations list
     * GET /donor/registrations
     */
    public function registrations()
    {
        $registrations = CampaignRegistration::with('campaign')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('donor.registrations', compact('registrations'));
    }

    /**
     * Donation history
     * GET /donor/history
     */
    public function history()
    {
        $donations = CampaignRegistration::with('campaign')
            ->where('user_id', Auth::id())
            ->where('status', 'donated')
            ->orderByDesc('donated_at')
            ->paginate(10);

        $totalDonations = $donations->total();

        return view('donor.history', compact('donations', 'totalDonations'));
    }

    /**
     * Notifications page
     * GET /donor/notifications
     */
    public function notifications()
    {
        $user          = Auth::user();
        $notifications = $user->notifications()->paginate(15);

        // Mark all as read
        $user->unreadNotifications->markAsRead();

        return view('donor.notifications', compact('notifications'));
    }
}
