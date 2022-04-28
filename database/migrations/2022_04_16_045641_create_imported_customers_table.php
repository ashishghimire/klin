<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nilambar\NepaliDate\NepaliDate;

class CreateImportedCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $englishDate = Carbon::now();
        $year = $englishDate->format('Y');
        $month = $englishDate->format('m');
        $day = $englishDate->format('d');
        $nepaliDateObj = new NepaliDate();
        $nepaliDateArray = $nepaliDateObj->convertAdToBs($year, $month, $day);
        $nepaliDate = $nepaliDateArray['year'] . '-' . $nepaliDateArray['month'] . '-' . $nepaliDateArray['day'];


        Schema::create('imported_customers', function (Blueprint $table) use ($nepaliDate) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->unique();
            $table->double('reward_points',7, 2)->default(0);
            $table->integer('manual_id')->unique();
            $table->string('nepali_date')->default($nepaliDate);
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
        Schema::dropIfExists('imported_customers');
    }
}
