<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_employee'); //get a employee id
            $table->string('school')->comment('nama univ/sekolah');
            $table->string('primary_major');
            $table->string('secondary_major')->nullable();
            $table->integer('strata')->comment('0=sarjana,1=magister,2=doktoral,3=profesi,4=lainnya');
            $table->string('degree_type',12)->nullable()->comment('gelar');
            $table->integer('graduation_year')->comment('tahun lulusan');
            $table->timestamps();

            // RELATIONS
            $table->foreign('id_employee')->references('id')->on('employees')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('education');
    }
}
