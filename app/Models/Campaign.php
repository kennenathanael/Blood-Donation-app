<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'location',
        'address',
        'campaign_date',
        'end_time',
        'registration_deadline',
        'max_donors',
        'status',
        'banner_image',
        'contact_phone',
        'contact_email',
        'requirements',
        'benefits',
        'notify_sent',
        'created_by',
    ];

    protected $casts = [
        'campaign_date'         => 'datetime',
        'end_time'              => 'datetime',
        'registration_deadline' => 'datetime',
        'notify_sent'           => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CampaignRegistration::class);
    }

    public function donors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'campaign_registrations')
                    ->withPivot(['status', 'health_notes', 'donated_at', 'registered_at'])
                    ->withTimestamps();
    }

    public function pendingRegistrations(): HasMany
    {
        return $this->hasMany(CampaignRegistration::class)->where('status', 'pending');
    }

    public function acceptedRegistrations(): HasMany
    {
        return $this->hasMany(CampaignRegistration::class)->where('status', 'accepted');
    }

    public function completedDonations(): HasMany
    {
        return $this->hasMany(CampaignRegistration::class)->where('status', 'donated');
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    /**
     * Is campaign open for registration?
     */
    public function isOpen(): bool
    {
        return $this->status === 'active'
            && now()->lessThanOrEqualTo($this->registration_deadline)
            && $this->spotsRemaining() > 0;
    }

    /**
     * How many spots are left?
     */
    public function spotsRemaining(): int
    {
        $accepted = $this->acceptedRegistrations()->count();
        return max(0, $this->max_donors - $accepted);
    }

    /**
     * Percentage of spots filled
     */
    public function fillPercentage(): int
    {
        if ($this->max_donors === 0) return 0;
        $accepted = $this->acceptedRegistrations()->count();
        return min(100, (int) round(($accepted / $this->max_donors) * 100));
    }

    /**
     * Status badge color for UI
     */
    public function statusColor(): string
    {
        return match($this->status) {
            'active'    => 'green',
            'upcoming'  => 'blue',
            'completed' => 'gray',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    /**
     * Days until campaign
     */
    public function daysUntil(): int
    {
        return max(0, (int) now()->diffInDays($this->campaign_date, false));
    }
}
