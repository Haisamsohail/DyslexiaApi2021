<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ChildStudent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ParentID');
            $table->string('FirstName');
            $table->string('LastName');
            $table->integer('Age');
            $table->string('Gender');
            $table->string('email')->unique()->notNullable();
            $table->string('password');
            $table->text('profilepicture')->default("0");
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
        Schema::dropIfExists('ChildStudent');
    }
}
