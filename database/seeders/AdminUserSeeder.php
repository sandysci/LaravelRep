<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@adashi.com',
            'phone_country' => 'NG',
            'phone' => '09012348990',
            'password' => Hash::make('admin1234'),
            'email_verified_at' => Date::now()
        ]);
        $userAdmin->assignRole('admin');
    }
}
