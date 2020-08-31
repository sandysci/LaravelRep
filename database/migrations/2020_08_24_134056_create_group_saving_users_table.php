<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupSavingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_saving_users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('group_saving_id')->index();
            $table->string('participant_email')->index();
           
            $table->nullableUuidMorphs('payment_gateway');
            
            $table->enum('status', ['pending', 'approved', 'disapproved'])->default('pending');
            $table->enum('group_owner_approval', ['pending', 'approved', 'disapproved'])->default('pending');
            
            $table->boolean('payout')->default(false);

            $table->softDeletes();

            $table->timestamps();
            
            $table->foreign('participant_email')
                    ->references('email')
                    ->on('users');

            $table->foreign('group_saving_id')
                    ->references('id')
                    ->on('group_savings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_saving_users');
    }
}
