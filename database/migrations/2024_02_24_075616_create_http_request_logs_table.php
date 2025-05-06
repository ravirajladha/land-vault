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
        Schema::create('http_request_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Assuming users can be unauthenticated
            $table->string('ip_address');
            $table->text('user_agent');
            $table->string('method');
            $table->text('url');
            $table->timestamps(); // This will create both `created_at` and `updated_at` columns

            // Optionally, add a foreign key constraint if you have a users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('http_request_logs');
    }
};
