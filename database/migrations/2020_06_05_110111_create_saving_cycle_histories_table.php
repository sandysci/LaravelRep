<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingCycleHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_cycle_histories', function (Blueprint $table) {
            $table->uuid('id')->primary;
            $table->uuid('saving_cycle_id');

            
            $table->enum('status', ['processing', 'success', 'failed'])->nullable('processing');
	        $table->enum('type', ['debit', 'credit']);
            $table->string("description");
            $table->integer('attempt')->nullable()->default(0);
           
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saving_cycle_histories');
    }
}
