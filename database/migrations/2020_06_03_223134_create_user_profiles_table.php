<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique()->index();
        
            $table->string("firstname")->nullable();
            $table->string("lastname")->nullable();
            $table->string("address")->nullable();
            $table->string("avatar")->nullable();
            $table->string("next_of_kin_name")->nullable();
            $table->string("next_of_kin_number")->nullable();
            $table->text('meta')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
