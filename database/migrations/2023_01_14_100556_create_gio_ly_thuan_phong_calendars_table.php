<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGioLyThuanPhongCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gio_ly_thuan_phong_calendars', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description');
            $table->integer('min_hour');
            $table->integer('max_hour');
            $table->unsignedBigInteger('calendar_id');
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
        Schema::dropIfExists('gio_ly_thuan_phong_calendars');
    }
}
