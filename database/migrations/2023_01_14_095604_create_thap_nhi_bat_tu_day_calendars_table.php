<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThapNhiBatTuDayCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thap_nhi_bat_tu_day_calendars', function (Blueprint $table) {
            $table->id();
            $table->text('star');
            $table->text('status');
            $table->text('should_do');
            $table->text('should_not_do');
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
        Schema::dropIfExists('thap_nhi_bat_tu_day_calendars');
    }
}
