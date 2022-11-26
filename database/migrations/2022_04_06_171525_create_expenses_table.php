<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('txn_no')->nullable();
            $table->string('category');
            $table->string('details');
            $table->decimal('amount', $precision = 10, $scale = 2);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('mode')->default('cash');
            $table->string('payee')->nullable();
            $table->string('receiver')->nullable();
            $table->string('nepali_date');
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
        Schema::dropIfExists('expenses');
    }
}
