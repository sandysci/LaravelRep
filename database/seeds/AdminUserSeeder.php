<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

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
            'id' => 2,
            'name' => 'Admin',
            'email' => 'admin@adashi.com',
            'phone' => '0909909099',
            'password' => Hash::make('admin1234'),
            'email_verified_at' => Date::now()
        ]);
        $userAdmin->assignRole('admin');

    }
}
