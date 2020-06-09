<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
  
	        $table->string('reference');
	        $table->decimal('amount', 19, 4)->default(0);
	        $table->nullableUuidMorphs('payment_gateway');
            
            $table->enum('status', ['processing', 'success', 'failed'])->nullable('processing');
	        $table->enum('type', ['debit', 'credit']);
            $table->integer('attempt')->nullable()->default(0);
            
            $table->nullableUuidMorphs('model');
            
            $table->string('description')->nullable();
        
            
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
        Schema::dropIfExists('transactions');
    }
}
