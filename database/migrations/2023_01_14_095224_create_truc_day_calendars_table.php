<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrucDayCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truc_day_calendars', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('should_do');
            $table->text('should_not_do');
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
        Schema::dropIfExists('truc_day_calendars');
    }
}
