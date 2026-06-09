<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model ini merepresentasikan artikel panduan yang ada di knowledge base
class KnowledgeBaseArticle extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi saat membuat atau mengupdate artikel
    protected $fillable = [
        'title', 'category', 'content', 'thumbnail', 
        'status', 'created_by', 'generated_by_ai' // generated_by_ai = true jika artikel dibuat oleh AI
    ];

    // Artikel ini dibuat oleh satu user IT Support tertentu
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
