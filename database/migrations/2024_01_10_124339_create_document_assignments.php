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
        Schema::create('document_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('document_type');
            $table->unsignedBigInteger('doc_id');
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('receiver_type');
            $table->string('access_token', 64)->unique(); // For unique URL token
            $table->timestamp('expires_at'); // Expiration time
            $table->timestamps();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('first_viewed_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->string('otp',4)->nullable();
            $table->string('first_viewed_ip', 45)->nullable();
            // $table->foreign('created_by')->references('id')->on('users'); // Assuming you have a users table

            // $table->foreign('receiver_id')->references('id')->on('receivers')->onDelete('cascade');
            // Add a foreign key for doc_id if it references another table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_assignments');
    }
};
