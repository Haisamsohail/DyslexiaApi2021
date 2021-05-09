<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkilllevelheadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skilllevelheading', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('LevelNumber')->unique()->notNullable();
            $table->string('LevelHeading')->unique()->notNullable();
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
        Schema::dropIfExists('skilllevelheading');
    }
}
