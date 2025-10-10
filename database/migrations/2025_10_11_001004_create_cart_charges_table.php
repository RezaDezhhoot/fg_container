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
        Schema::create('cart_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unsigned_cart_id')->index();
            $table->decimal('amount',40,3);
            $table->boolean('confirm')->default(0);
            $table->foreignId('panel_id')->index();
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
        Schema::dropIfExists('cart_charges');
    }
};
