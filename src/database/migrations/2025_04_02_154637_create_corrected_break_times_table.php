<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectedBreakTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corrected_break_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('corrected_work_time_id')->constrained('corrected_work_times', 'id')->cascadeOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('finish_time');
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
        Schema::dropIfExists('corrected_break_times');
    }
}
