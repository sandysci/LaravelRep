<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->uuid('id')->primary;
            $table->uuid('user_id');
            $table->bigInteger('account_number')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('recipient_code')->nullable();
            $table->text('meta')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['account_number']);
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
        Schema::dropIfExists('bank_details');
    }
}
