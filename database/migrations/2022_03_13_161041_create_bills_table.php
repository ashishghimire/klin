<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->integer('estimate_no');
            $table->float('amount');
            $table->float('paid_amount');
            $table->enum('payment_status',['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->string('payment_mode');
            $table->enum('laundry_status', ['unprocessed', 'processing', 'completed', 'delivered'])->default('unprocessed');
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
        Schema::dropIfExists('bills');
    }
}
