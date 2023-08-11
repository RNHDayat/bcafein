<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); //get a users id
            $table->unsignedBigInteger('id_credential'); //get a credential id
            $table->unsignedBigInteger('id_category');//get a category id
            // $table->string('title')->unique();
            $table->string('title');
            $table->string('title_slug')->nullable();
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('status')->default(1)->comment('0=hidden,1=active,2=draft,3=best,4=blocked');

            // RELATION
            $table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('id_credential')->references('id')->on('credentials')->onDelete('CASCADE');
            $table->foreign('id_category')->references('id')->on('categories')->onDelete('CASCADE');
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
        Schema::dropIfExists('postings');
    }
}
