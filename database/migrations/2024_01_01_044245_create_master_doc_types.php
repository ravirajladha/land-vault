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
        Schema::create('master_doc_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('created_by')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('compliances', function (Blueprint $table) {
        //     $table->dropForeign(['document_type']); // Adjust the column name if different
        // });
    
        // Schema::dropIfExists('compliances');
        Schema::dropIfExists('master_doc_types');
    }
};
