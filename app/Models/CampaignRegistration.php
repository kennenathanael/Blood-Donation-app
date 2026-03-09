<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Notifications\RegistrationStatusNotification;
use App\Notifications\DonationReminderNotification;

class CampaignRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'status',
        'health_notes',
        'emergency_contact',
        'emergency_phone',
        'has_donated_before',
        'admin_notes',
        'registered_at',
        'donated_at',
    ];

    protected $casts = [
        'registered_at'      => 'datetime',
        'donated_at'         => 'datetime',
        'has_donated_before' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    // ─── Status Transitions (notify donor on each change) ─────────────────────

    public function accept(): void
    {
        $this->update(['status' => 'accepted']);
        $this->user->notify(new RegistrationStatusNotification($this->campaign, 'accepted'));
    }

    public function reject(?string $adminNotes = null): void
    {
        $this->update([
            'status'      => 'rejected',
            'admin_notes' => $adminNotes,
        ]);
        $this->user->notify(new RegistrationStatusNotification($this->campaign, 'rejected'));
    }

    public function markDonated(): void
    {
        $this->update([
            'status'     => 'donated',
            'donated_at' => now(),
        ]);

        // Increment donor's total donations and update last donation date
        $this->user->increment('total_donations');
        $this->user->update(['last_donation_date' => now()]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function statusBadgeColor(): string
    {
        return match($this->status) {
            'pending'   => 'yellow',
            'accepted'  => 'green',
            'rejected'  => 'red',
            'donated'   => 'blue',
            'cancelled' => 'gray',
            default     => 'gray',
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'   => 'Pending Review',
            'accepted'  => 'Accepted',
            'rejected'  => 'Rejected',
            'donated'   => 'Donated ✓',
            'cancelled' => 'Cancelled',
            default     => ucfirst($this->status),
        };
    }
}
