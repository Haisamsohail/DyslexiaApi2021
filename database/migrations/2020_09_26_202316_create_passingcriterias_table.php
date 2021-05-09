<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassingcriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passingcriterias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('skilllevelheading_id')->unique()->notNullable();
            $table->integer('passingpoints');
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
        Schema::dropIfExists('passingcriterias');
    }
}
