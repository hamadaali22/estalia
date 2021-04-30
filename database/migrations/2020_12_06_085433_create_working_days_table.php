<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctorId');
            $table->foreign('doctorId')->references('id')->on('doctors');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('day')->nullable();
            $table->string('day_number')->nullable();
            $table->string('from_morning')->nullable();
            $table->string('to_morning')->nullable();
            $table->string('from_afternoon')->nullable();
            $table->string('to_afternoon')->nullable();
            $table->string('from_evening')->nullable();
            $table->string('to_evening')->nullable();
            $table->string('duration')->nullable();
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
        Schema::dropIfExists('working_days');
    }
}
