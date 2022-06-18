<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('color');
            $table->string('make');
            $table->string('model');
            $table->string('registration_no');
            $table->unsignedBigInteger('category_id')->unsigned(); 
            $table->index('category_id');
            $table->foreign('category_idcls')->references('id')->on('car_categories')->onDelete('cascade');; 

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
        Schema::dropIfExists('cars');
    }
}
