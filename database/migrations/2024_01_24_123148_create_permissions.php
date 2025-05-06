<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('display_name')->unique(); // Ensure display_name is unique
            $table->integer('action');
            // $table->unsignedBigInteger('created_by');
         
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
          
            $table->timestamps();

            $table->unique(['name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
