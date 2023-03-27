<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('codeCategory')->unique();
            $table->string('name');
            $table->boolean('validation')->default(Category::isUNVALIDATED);
            $table->unsignedBigInteger('id_Ilmu'); //get a knowledge_field or "Ilmu" id
            $table->unsignedBigInteger('id_user_propose')->nullable(); //get a user id
            $table->unsignedBigInteger('id_user_validator')->nullable(); //get a user id

            // RELATION
            $table->foreign('id_Ilmu')->references('id')->on('know_fields')->onDelete('CASCADE');
            $table->foreign('id_user_propose')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('id_user_validator')->references('id')->on('users')->onDelete('CASCADE');
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
        Schema::dropIfExists('categories');
    }
}
