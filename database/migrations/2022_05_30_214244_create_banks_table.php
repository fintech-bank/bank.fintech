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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bridge_id');
            $table->string('name');
            $table->string('logo');
            $table->string('primary_color');
            $table->string('country');
            $table->string('bic');
            $table->string('process_time');
            $table->boolean('autorize_refund')->default(true);
            $table->boolean('open')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
};
