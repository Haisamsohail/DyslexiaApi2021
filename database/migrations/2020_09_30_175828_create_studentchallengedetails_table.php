<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentchallengedetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentchallengedetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('StudentChallengeMasterID');
            $table->string('QuestionID');
            $table->string('Answer');
            $table->integer('Status')->default(1);
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
        Schema::dropIfExists('studentchallengedetails');
    }
}
