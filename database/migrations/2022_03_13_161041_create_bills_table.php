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
            $table->json('service_details');
            $table->decimal('amount', $precision = 8, $scale = 2);
            $table->decimal('paid_amount', $precision = 8, $scale = 2)->default(0);
            $table->enum('payment_status',['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->string('payment_mode')->nullable();
            $table->enum('laundry_status', ['unprocessed', 'processing', 'completed', 'delivered'])->default('unprocessed');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nepali_date');
            $table->string('note')->nullable();
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
