<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sold_lands', function (Blueprint $table) {
            $table->id();
            $table->string('index_id',255)->nullable();
            $table->string('state',255)->nullable();
            $table->string('district_number',255)->nullable();
            $table->string('district',255)->nullable();
            $table->string('village_number',255)->nullable();
            $table->string('village',255)->nullable();
            $table->string('survey_number',255)->nullable();
            $table->string('wet_land',255)->nullable();
            $table->string('dry_land',255)->nullable();
            $table->string('plot',255)->nullable();
            $table->string('traditional_land',255)->nullable();
            $table->string('total_area',255)->nullable();
            $table->string('total_area_unit',255)->nullable();
            $table->string('total_wet_land',255)->nullable();
            $table->string('total_dry_land',255)->nullable();
            $table->string('gap',255)->nullable();
            $table->string('sale_amount',255)->nullable();
            $table->string('total_sale_amount',255)->nullable();
            $table->string('registration_office',255)->nullable();
            $table->string('register_number',255)->nullable();
            // $table->string('register_date',255)->nullable();
            $table->date('register_date')->nullable();
            $table->string('book_number',255)->nullable();
            $table->string('name_of_the_purchaser',255)->nullable();
            $table->string('balance_land',255)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('file')->nullable();
            $table->string('remark',255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->integer('status_id')->default(0); 
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_lands');
    }
};
