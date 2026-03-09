<?php
// ============================================================
// database/seeders/AdminSeeder.php
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name'     => 'Hospital Admin',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
                'phone'    => '+237 600 000 000',
                'city'     => 'Yaoundé',
            ]
        );

        // Demo donor
        User::firstOrCreate(
            ['email' => 'donor@example.com'],
            [
                'name'     => 'John Donor',
                'password' => Hash::make('Donor@1234'),
                'role'     => 'donor',
                'phone'    => '+237 611 111 111',
                'city'     => 'Yaoundé',
            ]
        );
    }
}

// ============================================================
// database/seeders/DatabaseSeeder.php
// ============================================================
// namespace Database\Seeders;
// use Illuminate\Database\Seeder;
// class DatabaseSeeder extends Seeder {
//     public function run(): void {
//         $this->call([
//             BloodGroupSeeder::class,
//             AdminSeeder::class,
//         ]);
//     }
// }
