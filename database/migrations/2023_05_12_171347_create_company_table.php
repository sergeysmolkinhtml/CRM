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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('logo');
            $table->string('login_background');
            $table->text('address');
            $table->string('website');
            $table->enum('package_type',['monthly','annual']);
            $table->string('timezone');
            $table->string('offset_timezone');
            $table->string('date_format',30);
            $table->string('date_picker_format');
            $table->string('moment_format');
            $table->string('time_format');
            $table->integer('week_start',11);
            $table->string('locale');
            $table->decimal('latitude');
            $table->decimal('longitude');
            $table->enum('leaves_start_from',['joining_date','year_start']);
            $table->enum('active_theme',['default','custom']);
            $table->enum('status',['active','inactive','licence_expired']);
            $table->enum('task_self',['yes','no']);
            $table->enum('status',['active','inactive','licence_expired']);
            $table->string('stripe_id');
            $table->string('card_brand');
            $table->string('card_last_four');
            $table->string('trial_ends_at');
            $table->date('licence_expire_on');
            $table->tinyInteger('rounded_theme');
            $table->dateTime('last_login');
            $table->tinyInteger('rounded_theme');
            $table->integer('default_task_status ');

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
