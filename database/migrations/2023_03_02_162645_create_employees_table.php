<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); //get a users id
            $table->string('fullname');
            $table->string('nickname');
            $table->string('familyname')->nullable();
            $table->string('gender',1)->comment('[L,P]');
            $table->date('datebirth');
            $table->unsignedBigInteger('birthplace')->nullable(); //get a city id
            $table->boolean('employee_status')->default(0);
            $table->string('phone')->unique();
            $table->unsignedBigInteger('country_phone')->nullable(); //get a phone_countries id
            $table->string('country')->nullable();
            $table->string('description')->nullable();
            // Current Job
            $table->string('company')->default('-');
            $table->string('job_position')->default('-');
            $table->string('start_year',4)->nullable();
            $table->string('end_year',4)->nullable();
            $table->string('npwp')->nullable();
            // Address Home
            $table->string('lat_house')->nullable();
            $table->string('long_house')->nullable();
            $table->string('address_house')->nullable();
            // QR Profile
            $table->string('qrcode')->default('-'); // imagenya
            $table->string('qrcode_path')->default('-'); //alamat dalam storage qrcode disimpan
            $table->string('qrcode_link')->default('-'); //url qrcode mengarah
            $table->timestamps();

            // RELATIONS
            $table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('birthplace')->references('id')->on('cities')->onDelete('CASCADE');
            $table->foreign('country_phone')->references('id')->on('phone_countries')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
