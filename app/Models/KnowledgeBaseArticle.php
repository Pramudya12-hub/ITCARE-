<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'content', 'thumbnail', 
        'status', 'created_by', 'generated_by_ai'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
