<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Team::create([
            'name' => 'IT Developement',
            'code' => 'IT',
        ]);

        Team::create([
            'name' => 'Human Rescourese',
            'code' => 'HR'
        ]);

        Team::create([
            'name' => 'Audit',
            'code' => 'Audit'
        ]);
        // User 1 ဖန်တီးပြီး Employee 1 ချိတ်ဆက်ခြင်း
        $user1 = User::create([
            'name' => 'Zaw Lin Htet',
            'email' => 'zawlinhtet@gmail.com',
            'password' => Hash::make('password'),
            'level' => 3 ,
            

        ]);

        Employee::create([
            'user_id' => $user1->id,
            'employee_code' => 'WCL-001',
            'position' => 'Software Engineer',
            'address' => 'No. 123, Yangon Street, Yangon',
            'nrc' => '12/YGN(N)123456',
            'team_id' => 1,

        ]);

        // User 2 ဖန်တီးပြီး Employee 2 ချိတ်ဆက်ခြင်း
        $user2 = User::create([
            'name' => 'Win Bala Min',
            'email' => 'winbalamin@gmail.com',
            'password' => Hash::make('password'),
            'level' => 2 ,
        ]);

        Employee::create([
            'user_id' => $user2->id,
            'employee_code' => 'WCL-002',
            'position' => 'Project Manager',
            'address' => 'No. 456, Mandalay Road, Mandalay',
            'nrc' => '13/MDY(N)654321',
            'team_id' => 2,

        ]);

        // User 3 ဖန်တီးပြီး Employee 3 ချိတ်ဆက်ခြင်း
        $user3 = User::create([
            'name' => 'Saw Kalo Htoo',
            'email' => 'sawkalohtoo@gmail.com',
            'password' => Hash::make('password'),
            'level' => 1 ,

        ]);

        Employee::create([
            'user_id' => $user3->id,
            'employee_code' => 'WCL-003',
            'position' => 'Designer',
            'address' => 'No. 789, Naypyidaw Avenue, Naypyidaw',
            'nrc' => '14/NPT(N)789123',
            'team_id' => 3,

        ]);

        $user4 = User::create([
            'name' => 'Moe Thin',
            'email' => 'moethin@gmail.com',
            'password' => Hash::make('password'),
            'level' => 1 ,

        ]);

        Employee::create([
            'user_id' => $user4->id,
            'employee_code' => 'WCL-004',
            'position' => 'Designer',
            'address' => 'No. 789, Naypyidaw Avenue, Naypyidaw',
            'nrc' => '12/NPT(N)162828',
            'team_id' => 2,

        ]);

        $user5 = User::create([
            'name' => 'Lwin Ko Aung',
            'email' => 'lwinkoaung@gmail.com',
            'password' => Hash::make('password'),
            'level' => 1 ,

        ]);

        Employee::create([
            'user_id' => $user5->id,
            'employee_code' => 'WCL-005',
            'position' => 'Designer',
            'address' => 'No. 789, Naypyidaw Avenue, Naypyidaw',
            'nrc' => '14/YGN(N)789123',
            'team_id' => 2,

        ]);

        $user6 = User::create([
            'name' => 'Phyo Thura Soe',
            'email' => 'phyothurasoe@gmail.com',
            'password' => Hash::make('password'),
            'level' => 1 ,

        ]);

        Employee::create([
            'user_id' => $user6->id,
            'employee_code' => 'WCL-006',
            'position' => 'Technician',
            'address' => 'No. 789, Naypyidaw Avenue, Naypyidaw',
            'nrc' => '14/MDY(N)789123',
            'team_id' => 2,

        ]);
        $clients = [
            [
                'name' => 'Win Thin Associates',
                'code' => 'WTA',
                'industry_type' => 'Audit',
                'address' => 'No. 123, Naypyidaw Avenue, Naypyidaw',
                'phone' => '09123456789',
                'email' => 'wta@gmail.com',
                'is_active' => true,
                'team_id' => 1,
            ],
            [
                'name' => 'Win Consulting Limited',
                'code' => 'WCL',
                'industry_type' => 'Consulting',
                'address' => 'No. 456, Yangon Street, Yangon',
                'phone' => '09987654321',
                'email' => 'wcl@gmail.com',
                'is_active' => true,
                'team_id' => 2,

            ],
            [
                'name' => 'MAIP Corporation',
                'code' => 'MAIP',
                'industry_type' => 'Legal & Financial Services',
                'address' => 'No. 789, Mandalay Road, Mandalay',
                'phone' => '09456789123',
                'email' => 'maip@gmail.com',
                'is_active' => true,
                'team_id' => 3,
            ],
            [
                'name' => 'Kanbawza Bank Limited',
                'code' => 'KBZ Bank',
                'industry_type' => 'Banking',
                'address' => 'No. 321, Yangon Street, Yangon',
                'phone' => '09321654987',
                'email' => 'kbz@gmail.com',
                'is_active' => true,
                'team_id' => 3,
            ],
            [
                'name' => 'MELIA Hotel',
                'code' => 'MELIA',
                'industry_type' => 'Hotel',
                'address' => 'No. 654, Mandalay Road, Mandalay',
                'phone' => '09654321789',
                'email' => 'melia@gmail.com',
                'is_active' => true,
                'team_id' => 2,
            ],
            [
                'name' => 'Shwe Gon Daing Hospital',
                'code' => 'SSC',
                'industry_type' => 'Hospital',
                'address' => 'No. 987, Yangon Street, Yangon',
                'phone' => '09789123456',
                'email' => 'ssc@gmail.com',
                'is_active' => true,
                'team_id' => 1,
            ],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }
    }
}