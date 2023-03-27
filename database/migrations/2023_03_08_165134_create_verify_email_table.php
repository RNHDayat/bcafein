<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerifyEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verify_email', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); //get a user id
            $table->string('email');
            $table->text('token');
            $table->boolean('email_isVerified')->default(0);
            $table->timestamps();

            // RELATIONS
            $table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verify_email');
    }
}
