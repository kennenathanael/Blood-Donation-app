<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Users who have this blood group
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * All blood group names as array for dropdowns
     */
    public static function allNames(): array
    {
        return ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    }
}
