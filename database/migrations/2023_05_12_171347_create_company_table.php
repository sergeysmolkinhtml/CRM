<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Companies with no relations
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('logo')->nullable();
            $table->string('login_background')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->enum('package_type',['monthly','annual'])->default('monthly');
            $table->string('timezone')->default('EU')->nullable();
            $table->string('offset_timezone')->nullable();
            $table->string('date_format',30)->default('d-m-Y')->nullable();
            $table->string('date_picker_format')->nullable();
            $table->string('moment_format')->nullable();
            $table->string('time_format')->default('h:i A')->nullable();
            $table->integer('week_start')->nullable();
            $table->string('locale')->default('en')->nullable();
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->enum('leaves_start_from',['joining_date','year_start'])->default('joining_date')->nullable();
            $table->enum('active_theme',['default','custom'])->default('default')->nullable();
            $table->enum('status',['active','inactive','licence_expired'])->default('active')->nullable();
            $table->enum('task_self',['yes','no'])->default('yes')->nullable();
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->string('trial_ends_at')->nullable();
            $table->date('licence_expire_on')->nullable();
            $table->tinyInteger('rounded_theme')->nullable();
            $table->dateTime('last_login')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
