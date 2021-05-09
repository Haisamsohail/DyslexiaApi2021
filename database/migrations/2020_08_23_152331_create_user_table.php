<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('UserName');
            $table->string('email')->unique()->notNullable();
            $table->string('password');
            $table->integer('UserTypeID');
            $table->string('GradeLevelTaught')->default("0");
            $table->integer('HearAboutUsId');
            $table->string('HearAboutUsOther')->default("0");
            $table->string('SchoolName')->default("XYZ School");
            $table->string('Suburb')->default("XYZ Suburb");
            $table->string('PostCode')->default("XYZ PostCode");
            $table->string('State')->default("State PostCode");
            $table->integer('Status')->default(2);
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
        Schema::dropIfExists('user');
    }
}
