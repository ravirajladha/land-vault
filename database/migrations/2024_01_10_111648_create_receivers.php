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
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('name', 255)->nullable(); // If the name is always required
            $table->string('phone')->nullable(); // Changed to string to accommodate various formats
            $table->string('city', 255)->nullable(); // If the city is not always required
            $table->unsignedBigInteger('receiver_type_id')->nullable(); // Assuming it is nullable
            $table->foreign('receiver_type_id')->references('id')->on('receiver_types');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users'); // Assuming you have a users table
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivers');
    }
};
