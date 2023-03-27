<?php

use App\Models\knowField;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('know_fields', function (Blueprint $table) {
            $table->id();
            $table->string('codeIlmu')->unique();
            $table->string('name')->unique();
            $table->boolean('validation')->default(knowField::isUNVALIDATED);
            $table->unsignedBigInteger('id_user_propose')->nullable(); //get a user id
            $table->unsignedBigInteger('id_user_validator')->nullable(); //get a user id

            // RELATION
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
        Schema::dropIfExists('know_fields');
    }
}
