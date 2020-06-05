<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBufferAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buffer_accounts', function (Blueprint $table) {
            $table->uuid("id")->primary;
            $table->uuid("user_id");
            $table->decimal('amount', 19, 4)->default(0.0000);
            $table->nullableUuidMorphs("model");
            $table->enum('status', ['processing', 'success', 'failed'])->nullable('processing');
	        $table->enum('type', ['debit', 'credit']);
            $table->string("description");

   
            $table->timestamps();
            $table->softDeletes(); 
                    
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buffer_accounts');
    }
}
