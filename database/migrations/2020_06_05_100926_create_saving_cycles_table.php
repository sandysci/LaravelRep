<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingCyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_cycles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string("name");
            
            $table->uuid('user_id')->index();
            
            $table->decimal('amount', 19, 4)->default(0.0000);
            
            $table->enum( 'plan', [ 'daily', 'weekly', 'monthly'] );
           
            $table->integer('day_of_month')->default(27);
	        $table->integer('day_of_week')->default(1);
            $table->integer('hour_of_day')->default(24);
            
            $table->uuidMorphs('payment_gateway');
            
            $table->date('start_date');
            $table->date('end_date');
           
            $table->date('withdrawal_date')->nullable();

            $table->enum( 'status', [ 'paused', 'active', 'deactivated'] );
            $table->text("description")->nullable();
          
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
        Schema::dropIfExists('saving_cycles');
    }
}
