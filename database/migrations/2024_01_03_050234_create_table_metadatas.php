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
        Schema::create('table_metadatas', function (Blueprint $table) {
            $table->id();
            $table->string('column_name')->nullable();
            $table->integer('table_id')->nullable();
            $table->string('table_name')->nullable();
            $table->string('data_type')->nullable();
            $table->integer('position')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->boolean('special')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_metadatas');
    }
};
