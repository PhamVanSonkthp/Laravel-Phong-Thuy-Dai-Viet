<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiveElementCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('five_element_calendars', function (Blueprint $table) {
            $table->id();
            $table->text('cat_hung_day');
            $table->text('nap_am_day');
            $table->text('ngu_hanh_day');
            $table->text('hop_day');
            $table->text('khac_day');
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
        Schema::dropIfExists('five_element_calendars');
    }
}
