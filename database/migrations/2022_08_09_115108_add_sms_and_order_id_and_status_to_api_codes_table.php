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
        Schema::table('api_codes', function (Blueprint $table) {
            $table->text('sms')->nullable();
            $table->string('order_id')->nullable();
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_codes', function (Blueprint $table) {
            $table->dropColumn('sms');
            $table->dropColumn('order_id');
            $table->dropColumn('status');
        });
    }
};
