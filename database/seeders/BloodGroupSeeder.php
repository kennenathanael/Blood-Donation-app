<?php
// ============================================================
// database/seeders/BloodGroupSeeder.php
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodGroup;

class BloodGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'A+',  'description' => 'Most common blood type'],
            ['name' => 'A-',  'description' => 'Universal plasma donor'],
            ['name' => 'B+',  'description' => 'Common blood type'],
            ['name' => 'B-',  'description' => 'Rare blood type'],
            ['name' => 'AB+', 'description' => 'Universal recipient'],
            ['name' => 'AB-', 'description' => 'Rarest blood type'],
            ['name' => 'O+',  'description' => 'Most needed blood type'],
            ['name' => 'O-',  'description' => 'Universal donor'],
        ];

        foreach ($groups as $group) {
            BloodGroup::firstOrCreate(['name' => $group['name']], $group);
        }
    }
}
