<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses_container_history', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->integer('count');
            $table->decimal('enter_price',52);
            $table->decimal('exit_price',52);
            $table->string('order_id');
            $table->bigInteger('user_id')->nullable();
            $table->text('description')->nullable();
            $table->string('product_title');
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
        Schema::dropIfExists('licenses_container_history');
    }
};
