<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentactivelevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentactivelevels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('StudentID');
            $table->string('LevelID');
            $table->string('ChapterID');
            $table->string('WordsCount');
            $table->string('Points');
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
        Schema::dropIfExists('studentactivelevels');
    }
}
