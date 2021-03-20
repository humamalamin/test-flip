<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisbursmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disbursments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id')->unique();
            $table->double('amount');
            $table->string('bank_code');
            $table->string('account_number');
            $table->string('beneficiary_name');
            $table->string('remark');
            $table->string('receipt')->nullable();
            $table->integer('fee');
            $table->dateTime('time_served')->nullable();
            $table->timestamp('timestamp');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disbursments');
    }
}
