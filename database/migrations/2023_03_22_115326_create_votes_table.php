<?php

use App\Models\Vote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); //get a user id (sang pelaku)
            $table->unsignedBigInteger('id_postings');
            $table->integer('vote_status')->default(Vote::isWAITING)->comment('0=onhold,1=upvote,2=downvote');

            // RELATION
            $table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('id_postings')->references('id')->on('postings')->onDelete('CASCADE');
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
        Schema::dropIfExists('votes');
    }
}
