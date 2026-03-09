<?php
// ============================================================
// app/Http/Controllers/Admin/AdminCampaignController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignRegistration;
use App\Models\User;
use App\Notifications\DonationReminderNotification;
use App\Notifications\RegistrationStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminCampaignController extends Controller
{
    /**
     * List all campaigns
     * GET /admin/campaigns
     */
    public function index(Request $request)
    {
        $query = Campaign::withCount(['registrations', 'acceptedRegistrations', 'completedDonations'])
            ->with('creator');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $campaigns = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    /**
     * Show create form
     * GET /admin/campaigns/create
     */
    public function create()
    {
        return view('admin.campaigns.create');
    }

    /**
     * Store new campaign
     * POST /admin/campaigns
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'location'              => 'required|string|max:255',
            'address'               => 'nullable|string|max:255',
            'campaign_date'         => 'required|date|after:today',
            'end_time'              => 'nullable|date|after:campaign_date',
            'registration_deadline' => 'required|date|before:campaign_date',
            'max_donors'            => 'required|integer|min:1|max:10000',
            'contact_phone'         => 'nullable|string|max:20',
            'contact_email'         => 'nullable|email',
            'requirements'          => 'nullable|string',
            'benefits'              => 'nullable|string',
            'banner_image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'campaign_date.after'         => 'Campaign date must be in the future.',
            'registration_deadline.before'=> 'Deadline must be before the campaign date.',
        ]);

        $data = $request->except(['banner_image', '_token']);
        $data['created_by'] = Auth::id();
        $data['status']     = 'active';

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('campaigns', 'public');
        }

        $campaign = Campaign::create($data);

        return redirect('/admin/campaigns/' . $campaign->id)
            ->with('success', 'Campaign "' . $campaign->title . '" created successfully!');
    }

    /**
     * Show campaign detail (admin view)
     * GET /admin/campaigns/{campaign}
     */
    public function show(Request $request, Campaign $campaign)
    {
        $campaign->loadCount(['registrations', 'acceptedRegistrations', 'completedDonations', 'pendingRegistrations']);

        $registrationsQuery = CampaignRegistration::with(['user.bloodGroup'])
            ->where('campaign_id', $campaign->id);

        if ($request->filled('status')) {
            $registrationsQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $registrationsQuery->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
            );
        }

        $registrations = $registrationsQuery->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.campaigns.show', compact('campaign', 'registrations'));
    }

    /**
     * Show edit form
     * GET /admin/campaigns/{campaign}/edit
     */
    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    /**
     * Update campaign
     * PUT /admin/campaigns/{campaign}
     */
    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'location'              => 'required|string|max:255',
            'address'               => 'nullable|string|max:255',
            'campaign_date'         => 'required|date',
            'registration_deadline' => 'required|date|before:campaign_date',
            'max_donors'            => 'required|integer|min:1',
            'status'                => 'required|in:upcoming,active,completed,cancelled',
            'contact_phone'         => 'nullable|string|max:20',
            'contact_email'         => 'nullable|email',
            'requirements'          => 'nullable|string',
            'benefits'              => 'nullable|string',
            'banner_image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->except(['banner_image', '_token', '_method']);

        if ($request->hasFile('banner_image')) {
            if ($campaign->banner_image) {
                Storage::disk('public')->delete($campaign->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('campaigns', 'public');
        }

        $campaign->update($data);

        return redirect('/admin/campaigns/' . $campaign->id)
            ->with('success', 'Campaign updated successfully!');
    }

    /**
     * Delete campaign
     * DELETE /admin/campaigns/{campaign}
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect('/admin/campaigns')
            ->with('success', 'Campaign deleted successfully.');
    }

    /**
     * Accept a donor registration
     * POST /admin/registrations/{registration}/accept
     */
    public function acceptDonor(CampaignRegistration $registration)
    {
        $registration->load(['user', 'campaign']);
        $registration->accept();

        return back()->with('success', $registration->user->name . ' has been accepted!');
    }

    /**
     * Reject a donor registration
     * POST /admin/registrations/{registration}/reject
     */
    public function rejectDonor(Request $request, CampaignRegistration $registration)
    {
        $registration->load(['user', 'campaign']);
        $registration->reject($request->admin_notes);

        return back()->with('success', $registration->user->name . '\'s registration has been rejected.');
    }

    /**
     * Mark donor as donated
     * POST /admin/registrations/{registration}/donated
     */
    public function markDonated(CampaignRegistration $registration)
    {
        $registration->load(['user', 'campaign']);
        $registration->markDonated();

        return back()->with('success', $registration->user->name . ' has been marked as donated!');
    }

    /**
     * Send reminder notifications to all accepted donors of a campaign
     * POST /admin/campaigns/{campaign}/notify
     */
    public function sendNotifications(Campaign $campaign)
    {
        $acceptedRegistrations = CampaignRegistration::with('user')
            ->where('campaign_id', $campaign->id)
            ->where('status', 'accepted')
            ->get();

        $count = 0;
        foreach ($acceptedRegistrations as $registration) {
            $registration->user->notify(new DonationReminderNotification($campaign));
            $count++;
        }

        $campaign->update(['notify_sent' => true]);

        return back()->with('success', "Reminder notification sent to {$count} donors!");
    }

    /**
     * Export campaign donors as CSV
     * GET /admin/campaigns/{campaign}/export
     */
    public function exportDonors(Campaign $campaign)
    {
        $registrations = CampaignRegistration::with(['user.bloodGroup'])
            ->where('campaign_id', $campaign->id)
            ->where('status', '!=', 'cancelled')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . str_slug($campaign->title) . '-donors.csv"',
        ];

        $callback = function () use ($registrations) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Phone', 'Blood Group', 'Status', 'Registered At', 'Donated At']);

            foreach ($registrations as $reg) {
                fputcsv($handle, [
                    $reg->user->name,
                    $reg->user->email,
                    $reg->user->phone ?? 'N/A',
                    $reg->user->bloodGroup->name ?? 'N/A',
                    $reg->status,
                    $reg->registered_at->format('Y-m-d H:i'),
                    $reg->donated_at ? $reg->donated_at->format('Y-m-d H:i') : 'N/A',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
