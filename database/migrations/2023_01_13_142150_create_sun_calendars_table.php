<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSunCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sun_calendars', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('day_of_month');
            $table->integer('month');
            $table->integer('year');
            $table->bigInteger('calendar_id')->unique();
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
        Schema::dropIfExists('sun_calendars');
    }
}
