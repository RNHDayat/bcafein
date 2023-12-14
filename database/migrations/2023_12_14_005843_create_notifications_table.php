<?php

use App\Models\Notifications;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->comment('(receiver)'); //get a users id
            $table->unsignedBigInteger('id_user_follow'); //get a users id
            $table->string('title');
            $table->string('body');
            $table->integer('status_read')->default(Notifications::isWAITING)->comment('0=onhold,1=read');

            $table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('id_user_follow')->references('id')->on('users')->onDelete('CASCADE');
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
        Schema::dropIfExists('notifications');
    }
}
