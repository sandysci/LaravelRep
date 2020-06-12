<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            // Reset cached roles and permissions
		app()['cache']->forget( 'spatie.permission.cache' );
        
        Role::create( [ 'name' => 'admin' ] );
        Role::create( [ 'name' => 'user' ] );
    }
}
