<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBvnColumnOnUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->date('date_of_birth')->after('lastname')->nullable();
            $table->string('bvn')->after('avatar')->unique()->nullable();
            $table->boolean('bvn_verified')->after('bvn')->default(false);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth','bvn', 'bvn_verified']);
        });
    }
}
