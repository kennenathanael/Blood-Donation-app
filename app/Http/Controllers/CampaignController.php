<?php
// ============================================================
// app/Http/Controllers/CampaignController.php
// ============================================================
namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\BloodGroup;
use App\Models\CampaignRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    /**
     * Public campaign listing
     * GET /campaigns
     */
    public function index(Request $request)
    {
        $query = Campaign::withCount(['registrations', 'acceptedRegistrations'])
            ->where('status', '!=', 'cancelled');

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status') && in_array($request->status, ['active', 'upcoming', 'completed'])) {
            $query->where('status', $request->status);
        }

        $campaigns = $query->orderBy('campaign_date', 'asc')->paginate(9)->withQueryString();

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Campaign detail page
     * GET /campaigns/{campaign}
     */
    public function show(Campaign $campaign)
    {
        $campaign->loadCount(['registrations', 'acceptedRegistrations', 'completedDonations']);
        $bloodGroups = BloodGroup::all();

        // Check if logged in donor is already registered
        $myRegistration = null;
        if (Auth::check() && Auth::user()->isDonor()) {
            $myRegistration = CampaignRegistration::where('user_id', Auth::id())
                ->where('campaign_id', $campaign->id)
                ->first();
        }

        return view('campaigns.show', compact('campaign', 'bloodGroups', 'myRegistration'));
    }
}
