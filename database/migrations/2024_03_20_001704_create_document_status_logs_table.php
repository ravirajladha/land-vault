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
        Schema::create('document_status_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            // $table->foreign('document_id')->references('id')->on('master_doc_data');
            $table->string('status');
            $table->text('message')->nullable();
            $table->unsignedBigInteger('created_by');
            // $table->foreign('created_by')->references('id')->on('users');
            $table->string('temp_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_status_logs');
    }
};

