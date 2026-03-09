<?php
// ============================================================
// app/Http/Controllers/Admin/AdminDonorController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodGroup;
use Illuminate\Http\Request;

class AdminDonorController extends Controller
{
    /**
     * List all donors
     * GET /admin/donors
     */
    public function index(Request $request)
    {
        $query = User::with('bloodGroup')
            ->withCount('registrations')
            ->where('role', 'donor');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group_id', $request->blood_group);
        }

        if ($request->filled('eligible')) {
            $query->where('is_eligible', $request->eligible === 'yes');
        }

        $donors      = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $bloodGroups = BloodGroup::all();

        return view('admin.donors.index', compact('donors', 'bloodGroups'));
    }

    /**
     * Show donor detail
     * GET /admin/donors/{user}
     */
    public function show(User $user)
    {
        $user->load(['bloodGroup', 'registrations.campaign']);

        $registrationStats = [
            'total'    => $user->registrations->count(),
            'donated'  => $user->registrations->where('status', 'donated')->count(),
            'accepted' => $user->registrations->where('status', 'accepted')->count(),
            'pending'  => $user->registrations->where('status', 'pending')->count(),
        ];

        return view('admin.donors.show', compact('user', 'registrationStats'));
    }

    /**
     * Toggle donor eligibility
     * POST /admin/donors/{user}/toggle-eligibility
     */
    public function toggleEligibility(User $user)
    {
        $user->update(['is_eligible' => !$user->is_eligible]);

        $status = $user->is_eligible ? 'eligible' : 'ineligible';
        return back()->with('success', $user->name . ' has been marked as ' . $status . '.');
    }

    /**
     * Delete donor (soft)
     * DELETE /admin/donors/{user}
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/admin/donors')->with('success', 'Donor account removed.');
    }
}
