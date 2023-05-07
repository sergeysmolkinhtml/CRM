<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('street')->nullable();
            $table->string('house')->nullable();
            $table->integer('flat')->nullable();
            $table->integer('postal_code')->nullable();

            $table->unsignedBigInteger('city_id')->nullable();

            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('country_id')->unsigned()->nullable();

            $table->foreign('country_id')->references('id')->on('countries')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();

        });

        Schema::create('offices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');

            $table->unsignedBigInteger('address_id')->nullable();

            $table->foreign('address_id')->references('id')->on('addresses')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();

        });

        Schema::create('language_level', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('short_name');
            $table->string('ext_name')->nullable();
            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();

        });
        Schema::create('employee_languages', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('level_id')->nullable();

            $table->foreign('level_id')->references('id')->on('language_level')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('lang_id')->nullable();

            $table->foreign('lang_id')->references('id')->on('languages')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('employees_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('employee_details', function (Blueprint $table) {

            $table->string('viber')->nullable()->after('hourly_rate');

            $table->string('telegram')->nullable()->after('viber');

            $table->string('whatsapp')->nullable()->after('telegram');

            $table->string('facebook')->nullable()->after('whatsapp');

            $table->string('linkedin')->nullable()->after('facebook');
            $table->string('skype')->nullable()->after('linkedin');

            $table->string('work_email')->nullable()->after('skype');
            $table->string('mobile_work_email')->nullable()->after('work_email');

            $table->string('work_hemail')->nullable()->after('mobile_work_email');
            $table->string('video')->nullable()->after('work_hemail');
            $table->string('pt_ft')->nullable()->after('work_hemail');

            $table->string('name_eng')->nullable()->after('employee_id');
            $table->dateTime('birthday')->nullable()->after('name_eng');
            $table->string('start_position')->nullable()->after('birthday');
            $table->string('resume_url')->nullable()->after('start_position');
            $table->string('photo_url')->nullable()->after('resume_url');


            $table->unsignedBigInteger('status_id')->nullable();

            $table->foreign('status_id')->references('id')->on('employees_statuses')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('office_id')->nullable();

            $table->foreign('office_id')->references('id')->on('offices')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('address_id')->nullable();

            $table->foreign('address_id')->references('id')->on('addresses')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('city_id')->nullable();

            $table->foreign('city_id')->references('id')->on('cities')
                ->onUpdate('cascade')->onDelete('cascade');


        });

        Schema::create('employees_portfolio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');

            $table->foreignId('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('employees_portfolio');

        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropForeign('employee_details_address_id_foreign');
            $table->dropColumn('address_id');
            $table->dropForeign('employee_details_office_id_foreign');
            $table->dropColumn('office_id');
            $table->dropForeign('employee_details_status_id_foreign');
            $table->dropColumn('status_id');
            $table->dropForeign('employee_details_city_id_foreign');
            $table->dropColumn('city_id');
            $table->dropColumn('viber');
            $table->dropColumn('telegram');
            $table->dropColumn('whatsapp');
            $table->dropColumn('facebook');
            $table->dropColumn('linkedin');
            $table->dropColumn('skype');
            $table->dropColumn('work_email');
            $table->dropColumn('mobile_work_email');
            $table->dropColumn('work_hemail');
            $table->dropColumn('video');
            $table->dropColumn('pt_ft');
            $table->dropColumn('name_eng');
            $table->dropColumn('birthday');
            $table->dropColumn('start_position');
            $table->dropColumn('resume_url');
            $table->dropColumn('photo_url');
        });

        Schema::dropIfExists('employees_statuses');
        Schema::dropIfExists('empl_languages');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('lang_level');
        Schema::dropIfExists('offices');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('cities');
    }
}
