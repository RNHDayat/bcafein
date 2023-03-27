<?php

use App\Models\Credential;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_employee'); //get a employee id
            $table->integer('type')->comment('0=expertise,1=edu,2=loc,3=employment,4=honor,5=answ,6=repost,7=etc');
            $table->string('description');
            $table->boolean('hide')->default(Credential::UNHIDE);

            // RELATIONS
            $table->foreign('id_employee')->references('id')->on('employees')->onDelete('CASCADE');

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
        Schema::dropIfExists('credentials');
    }
}
