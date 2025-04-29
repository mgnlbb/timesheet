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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('department');
            $table->string('project');
            $table->string('role');
            $table->string('location');
            $table->string('acknowledger_name');
            $table->string('acknowledger_position');
            $table->string('approver_name');
            $table->string('approver_position');
            $table->string('signature_path')->nullable(); // untuk upload tanda tangan
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
