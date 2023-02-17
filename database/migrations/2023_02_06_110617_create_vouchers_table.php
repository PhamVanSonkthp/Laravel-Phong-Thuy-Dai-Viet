<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('code')->nullable();
            $table->dateTime('begin');
            $table->dateTime('end');
            $table->bigInteger('min_amount');
            $table->bigInteger('max_use_by_time');
            $table->bigInteger('max_use_by_user');
            $table->bigInteger('discount_amount')->default(0)->nullable();
            $table->bigInteger('discount_percent')->default(0)->nullable();
            $table->bigInteger('max_discount_percent_amount')->default(0)->nullable();
            $table->bigInteger('used')->default(0)->nullable();

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
        Schema::dropIfExists('vouchers');
    }
}
