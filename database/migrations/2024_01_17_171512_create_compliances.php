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
        Schema::create('compliances', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('document_type')->nullable();
            $table->unsignedBigInteger('doc_id');
            $table->date('due_date')->nullable();
            $table->integer('status')->default(0);
            $table->boolean('is_recurring')->default(0);

            $table->unsignedBigInteger('created_by')->nullable();
       
            $table->timestamps();
        
            // $table->foreign('created_by')->references('id')->on('users'); // Assuming you have a users table
            // $table->foreign('doc_id')->references('id')->on('master_doc_datas')->onDelete('cascade');
            // $table->foreign('document_type')->references('id')->on('master_doc_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliances');
    }
};
