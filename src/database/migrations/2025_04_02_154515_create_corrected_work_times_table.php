<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectedWorkTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corrected_work_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_time_id')->constrained('work_times', 'id')->cascadeOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('finish_time');
            $table->string('notes');
            $table->boolean('permission');
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
        Schema::dropIfExists('corrected_work_times');
    }
}
