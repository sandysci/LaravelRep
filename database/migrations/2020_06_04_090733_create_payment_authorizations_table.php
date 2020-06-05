<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_authorizations', function (Blueprint $table) {
            $table->uuid('id')->primary;
            $table->uuid('user_id');

            $table->string('reference')->nullable();
		    $table->string('channel')->nullable();
            
            $table->string('gw_customer_id')->nullable();
	        $table->string('gw_customer_code')->nullable();
	        $table->string('gw_authorization_code')->nullable();
            
            $table->string('card_type')->nullable();
	        $table->string('last4')->nullable();
	        $table->string('exp_month')->nullable();
	        $table->string('exp_year')->nullable();
	        $table->string('country_code')->nullable();
	        $table->string('bank')->nullable();
            $table->string('brand')->nullable();
            $table->string('description')->nullable();
	        $table->boolean('reusable')->nullable();
		    $table->string( 'signature' )->nullable();
            $table->string('bank_number')->nullable();

            $table->timestamps();
            $table->softDeletes();
            
            // $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_authorizations');
    }
}
