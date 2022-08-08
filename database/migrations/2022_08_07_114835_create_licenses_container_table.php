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
        Schema::create('licenses_container', function (Blueprint $table) {
            $table->id();
            $table->string('license')->unique();
            $table->bigInteger('product_id');
            $table->string('product_title');
            $table->string('status');
            $table->bigInteger('form_enter_id');
            $table->bigInteger('form_exit_id');
            $table->softDeletes();
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
        Schema::dropIfExists('licenses_container');
    }
};
