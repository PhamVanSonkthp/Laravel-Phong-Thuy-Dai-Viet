<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTongHopBangKeCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tong_hop_bang_ke_calendars', function (Blueprint $table) {
            $table->id();
            $table->text('good_star');
            $table->text('bad_star');
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
        Schema::dropIfExists('tong_hop_bang_ke_calendars');
    }
}
