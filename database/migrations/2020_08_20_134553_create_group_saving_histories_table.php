<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupSavingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_saving_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->uuid('group_saving_id');
            
            $table->string('reference');
	        $table->decimal('amount', 19, 4)->default(0);
	      
            $table->enum('status', ['processing', 'success', 'failed'])->nullable('processing');
	        $table->enum('type', ['debit', 'credit'])->default('credit');
            
            $table->integer('attempt')->nullable()->default(0);

            $table->text("description")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('group_saving_id')
                  ->references('id')
                  ->on('group_savings');
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
        Schema::dropIfExists('group_saving_histories');
    }
}
