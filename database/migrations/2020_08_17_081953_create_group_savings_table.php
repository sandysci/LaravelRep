<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_savings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string("name");
            $table->uuid('owner_id')->index();

            $table->decimal('amount', 19, 4)->default(0.0000);

            $table->enum('plan', ['daily', 'weekly', 'monthly']);
           
            $table->integer('day_of_month')->nullable()->default(31);
            $table->integer('day_of_week')->nullable()->default(1);
            $table->integer('hour_of_day')->nullable()->default(24);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            $table->integer('no_of_participants');

            $table->enum('status', ['paused', 'active', 'deactivated', 'matured'])->default('paused');

            $table->text("description")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('owner_id')
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
        Schema::dropIfExists('group_savings');
    }
}
