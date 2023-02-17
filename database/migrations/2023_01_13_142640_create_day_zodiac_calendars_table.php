<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayZodiacCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_zodiac_calendars', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('status');
            $table->text('description');
            $table->unsignedBigInteger('calendar_id')->unique();
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
        Schema::dropIfExists('day_zodiac_calendars');
    }
}
