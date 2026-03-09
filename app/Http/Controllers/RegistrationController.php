<?php
// ============================================================
// app/Http/Controllers/RegistrationController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * Register donor for a campaign
     * POST /campaigns/{campaign}/register
     */
    public function store(Request $request, Campaign $campaign)
    {
        // Only donors can register
        if (Auth::user()->isAdmin()) {
            return back()->with('error', 'Admins cannot register as donors.');
        }

        // Check campaign is open
        if (!$campaign->isOpen()) {
            return back()->with('error', 'This campaign is not open for registration.');
        }

        // Check if already registered
        $existing = CampaignRegistration::where('user_id', Auth::id())
            ->where('campaign_id', $campaign->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You are already registered for this campaign.');
        }

        $request->validate([
            'blood_group_id'    => 'required|exists:blood_groups,id',
            'health_notes'      => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone'   => 'nullable|string|max:20',
            'has_donated_before'=> 'boolean',
        ]);

        // Update user's blood group
        Auth::user()->update(['blood_group_id' => $request->blood_group_id]);

        // Create registration
        CampaignRegistration::create([
            'user_id'            => Auth::id(),
            'campaign_id'        => $campaign->id,
            'status'             => 'pending',
            'health_notes'       => $request->health_notes,
            'emergency_contact'  => $request->emergency_contact,
            'emergency_phone'    => $request->emergency_phone,
            'has_donated_before' => $request->boolean('has_donated_before'),
            'registered_at'      => now(),
        ]);

        return redirect('/donor/dashboard')
            ->with('success', 'You have successfully registered for "' . $campaign->title . '"! You will be notified about your application status.');
    }

    /**
     * Cancel a registration
     * POST /registrations/{registration}/cancel
     */
    public function cancel(CampaignRegistration $registration)
    {
        // Ensure the registration belongs to the logged-in user
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        // Can only cancel pending registrations
        if (!in_array($registration->status, ['pending', 'accepted'])) {
            return back()->with('error', 'You cannot cancel this registration.');
        }

        $registration->cancel();

        return back()->with('success', 'Your registration has been cancelled.');
    }
}
