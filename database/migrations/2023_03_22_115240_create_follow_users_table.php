<?php

use App\Models\FollowUser;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); //get a user id (sang pelaku)
            $table->unsignedBigInteger('following_id'); //get a user id (mengikuti apa???)
            $table->integer('follow_status')->default(FollowUser::isFOLLOWED)->comment('0=onhold,1=follow,2=blocked,3=unfol');
            // RELATION
            $table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('following_id')->references('id')->on('users')->onDelete('CASCADE');
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
        Schema::dropIfExists('follow_users');
    }
}
