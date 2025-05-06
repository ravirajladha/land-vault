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
        Schema::create('advocate_documents', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('doc_id');
        $table->foreign('doc_id')->references('id')->on('master_doc_datas')->onDelete('cascade'); // Assuming there's a 'documents' table
        $table->string('case_name')->nullable();
        $table->string('case_status')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->string('court_name')->nullable();
        $table->string('court_case_location')->nullable();
        $table->string('plantiff_name')->nullable();
        $table->string('defendant_name')->nullable();
        $table->string('urgency_level')->nullable();
        $table->string('case_result')->nullable();
        $table->text('notes')->nullable();
        $table->date('submission_deadline')->nullable();
        $table->unsignedBigInteger('advocate_id');
        $table->foreign('advocate_id')->references('id')->on('advocates')->onDelete('cascade');
        $table->boolean('status')->default(1);
        $table->unsignedBigInteger('created_by')->nullable();
        $table->timestamps();
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
