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
        // Tabel ini menyimpan data tiket keluhan yang diajukan oleh user ke IT Support
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // user yang mengajukan keluhan
            $table->string('title');
            $table->string('category');
            $table->string('priority'); // level urgensi: Low, Medium, High
            $table->text('description');
            $table->string('image')->nullable(); // gambar pendukung dari user (opsional)
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open');
            $table->text('ai_recommendation')->nullable(); // rekomendasi solusi dari AI
            $table->text('ai_summary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
