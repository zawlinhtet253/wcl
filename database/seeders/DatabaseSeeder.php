<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User 1 ဖန်တီးပြီး Employee 1 ချိတ်ဆက်ခြင်း
        $user1 = User::create([
            'name' => 'Zaw Lin Htet',
            'email' => 'zawlinhtet@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Employee::create([
            'user_id' => $user1->id,
            'employee_code' => 'WCL-001',
            'position' => 'Software Engineer',
            'address' => 'No. 123, Yangon Street, Yangon',
            'nrc' => '12/YGN(N)123456',
        ]);

        // User 2 ဖန်တီးပြီး Employee 2 ချိတ်ဆက်ခြင်း
        $user2 = User::create([
            'name' => 'Win Bala Min',
            'email' => 'winbalamin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Employee::create([
            'user_id' => $user2->id,
            'employee_code' => 'WCL-002',
            'position' => 'Project Manager',
            'address' => 'No. 456, Mandalay Road, Mandalay',
            'nrc' => '13/MDY(N)654321',
        ]);

        // User 3 ဖန်တီးပြီး Employee 3 ချိတ်ဆက်ခြင်း
        $user3 = User::create([
            'name' => 'Saw Kalo Htoo',
            'email' => 'sawkalohtoo@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Employee::create([
            'user_id' => $user3->id,
            'employee_code' => 'WCL-003',
            'position' => 'Designer',
            'address' => 'No. 789, Naypyidaw Avenue, Naypyidaw',
            'nrc' => '14/NPT(N)789123',
        ]);
        $clients = [
            [
                'name' => 'Win Thin Associates',
                'code' => 'WTA',
            ],
            [
                'name' => 'Win Consulting Limited',
                'code' => 'WCL',
            ],
            [
                'name' => 'MAIP Corporation',
                'code' => 'MAIP',
            ],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }
    }
}