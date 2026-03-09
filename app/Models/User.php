<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'date_of_birth',
        'gender',
        'blood_group_id',
        'is_eligible',
        'medical_conditions',
        'profile_photo',
        'address',
        'city',
        'total_donations',
        'last_donation_date',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth'     => 'date',
        'last_donation_date'=> 'datetime',
        'is_eligible'       => 'boolean',
        'password'          => 'hashed',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CampaignRegistration::class);
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_registrations')
                    ->withPivot(['status', 'health_notes', 'donated_at', 'registered_at'])
                    ->withTimestamps();
    }

    // ─── Role Helpers ─────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    // ─── Donor Helpers ────────────────────────────────────────────────────────

    /**
     * Get age from date_of_birth
     */
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth
            ? $this->date_of_birth->age
            : null;
    }

    /**
     * Check if donor can donate (at least 56 days since last donation)
     */
    public function canDonateNow(): bool
    {
        if (!$this->is_eligible) return false;
        if (!$this->last_donation_date) return true;
        return $this->last_donation_date->diffInDays(now()) >= 56;
    }

    /**
     * Get initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', trim($this->name));
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        return $initials;
    }

    /**
     * Get accepted registrations
     */
    public function acceptedRegistrations(): HasMany
    {
        return $this->hasMany(CampaignRegistration::class)->where('status', 'accepted');
    }

    /**
     * Get donation history (donated status)
     */
    public function donationHistory(): HasMany
    {
        return $this->hasMany(CampaignRegistration::class)->where('status', 'donated');
    }
}
