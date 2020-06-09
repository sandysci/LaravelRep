<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');

            $table->string('reference');
            $table->decimal('amount', 19, 4)->default(0);
	       
            $table->nullableUuidMorphs('source');
            $table->uuidMorphs("destination");
            
            $table->enum('status', ['processing', 'success', 'failed'])->nullable('processing');
	        $table->boolean ('authorize')->default (false);

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
        Schema::dropIfExists('withdrawals');
    }
}
