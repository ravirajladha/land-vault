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
       // database/migrations/xxxx_xx_xx_xxxxxx_create_log_changes_table.php

       Schema::table('log_changes', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable()->change();
        $table->string('model_type')->nullable()->change();
        $table->unsignedBigInteger('model_id')->nullable()->change();
        $table->text('changes')->nullable()->change();
        $table->text('action')->nullable()->change();
        $table->text('original_values')->nullable()->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_changes');
    }
};
