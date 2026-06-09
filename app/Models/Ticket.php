<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model ini merepresentasikan tiket keluhan yang diajukan oleh user
class Ticket extends Model
{
    use HasFactory;

    // Kolom-kolom yang boleh diisi saat membuat atau mengupdate tiket
    protected $fillable = [
        'user_id', 
        'title', 
        'category', 
        'priority', 
        'description', 
        'image', 
        'status',
        'assigned_to',
        'first_response_at', // Waktu pertama kali IT Support merespons tiket
        'resolved_at',       // Waktu tiket selesai ditangani
        'ai_recommendation', // Hasil rekomendasi dari AI untuk tiket ini
        'ai_summary'
    ];

    // Tiket ini dimiliki oleh satu user (yang mengajukan keluhan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // IT Support yang ditugaskan untuk menangani tiket ini
    public function assignedSupport()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }   

    // Satu tiket bisa memiliki banyak komentar dari user maupun IT Support
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }
}
