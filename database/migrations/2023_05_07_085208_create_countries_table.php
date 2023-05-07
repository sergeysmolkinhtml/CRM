<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name', 200);
            $table->tinyInteger('is_visible');
            $table->char('iso_alpha2', 2);
            $table->index('iso_alpha2');
            $table->char('iso_alpha3', 2);
            $table->index('iso_alpha3');
            $table->unsignedInteger('iso_numeric');
            $table->string('currency_code', 3);
            $table->string('currency_name', 32);
            $table->string('currency_symbol', 3);
            $table->string('flag', 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
