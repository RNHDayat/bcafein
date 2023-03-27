<?php

use App\Models\FollowCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user'); //get a user id
            $table->unsignedBigInteger('follow_cat_id'); //get a category id
            $table->integer('follow_status')->default(FollowCategory::isFOLLOWED)->comment('0=follow,1=unfollow,2=blocked');

            // RELATION
            $table->foreign('id_user')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('follow_cat_id')->references('id')->on('categories')->onDelete('CASCADE');
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
        Schema::dropIfExists('follow_categories');
    }
}
