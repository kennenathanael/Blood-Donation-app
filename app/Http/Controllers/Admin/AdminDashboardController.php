<?php
// ============================================================
// app/Http/Controllers/Admin/AdminDashboardController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use App\Models\CampaignRegistration;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Admin dashboard with stats
     * GET /admin/dashboard
     */
    public function index()
    {
        // Main statistics
        $totalCampaigns    = Campaign::count();
        $activeCampaigns   = Campaign::where('status', 'active')->count();
        $totalDonors       = User::where('role', 'donor')->count();
        $totalDonations    = CampaignRegistration::where('status', 'donated')->count();
        $pendingApprovals  = CampaignRegistration::where('status', 'pending')->count();

        // Recent campaigns
        $recentCampaigns = Campaign::withCount(['registrations', 'acceptedRegistrations'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Recent registrations
        $recentRegistrations = CampaignRegistration::with(['user', 'campaign'])
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // Blood group distribution
        $bloodGroupStats = User::where('role', 'donor')
            ->whereNotNull('blood_group_id')
            ->with('bloodGroup')
            ->get()
            ->groupBy('blood_group_id')
            ->map(fn($users, $id) => [
                'name'  => $users->first()->bloodGroup->name ?? 'Unknown',
                'count' => $users->count(),
            ])
            ->values();

        // Monthly donations chart (last 6 months)
        $monthlyDonations = CampaignRegistration::where('status', 'donated')
            ->where('donated_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(donated_at) as month, YEAR(donated_at) as year, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalCampaigns',
            'activeCampaigns',
            'totalDonors',
            'totalDonations',
            'pendingApprovals',
            'recentCampaigns',
            'recentRegistrations',
            'bloodGroupStats',
            'monthlyDonations'
        ));
    }
}
