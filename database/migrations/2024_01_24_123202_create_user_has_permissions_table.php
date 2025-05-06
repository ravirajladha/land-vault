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
        Schema::create('user_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('permission_display_name'); // Use string type for permission display_name
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Create a foreign key relationship based on the permission display_name
            $table->foreign('permission_display_name')->references('display_name')->on('permissions')->onDelete('cascade');

            // Set the primary key for the table
            $table->primary(['user_id', 'permission_display_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_has_permissions');
    }
};
