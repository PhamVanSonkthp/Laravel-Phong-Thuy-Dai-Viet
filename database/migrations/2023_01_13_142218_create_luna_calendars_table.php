<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLunaCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('luna_calendars', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('day_of_month');
            $table->integer('month');
            $table->integer('year');
            $table->string('can_day');
            $table->string('chi_day');
            $table->string('luna_day');
            $table->string('can_month');
            $table->string('chi_month');
            $table->string('can_year');
            $table->string('chi_year');
            $table->string('text_year');
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
        Schema::dropIfExists('luna_calendars');
    }
}
