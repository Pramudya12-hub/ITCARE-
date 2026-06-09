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
        // Tabel ini menyimpan artikel panduan/tutorial yang bisa dibaca oleh semua user
        Schema::create('knowledge_base_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->text('content'); // isi artikel dalam format teks panjang
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft'); // hanya artikel 'published' yang bisa dibaca user
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // IT Support yang membuat artikel
            $table->boolean('generated_by_ai')->default(false); // menandai apakah artikel ini dibuat oleh AI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_base_articles');
    }
};
