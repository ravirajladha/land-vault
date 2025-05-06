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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(); // Type of notification (e.g., 'created', 'settled', 'canceled')
            $table->text('message')->nullable(); 
            $table->integer('is_read')->default(0);
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->unsignedBigInteger('compliance_id')->nullable(); 
            $table->unsignedBigInteger('document_assignment_id')->nullable(); 
            $table->unsignedBigInteger('doc_id')->nullable(); 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        
            $table->foreign('created_by')->references('id')->on('users'); // Assuming you have a users table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
