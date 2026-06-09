<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model ini merepresentasikan komentar yang dikirim pada sebuah tiket keluhan
class TicketComment extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi saat menyimpan komentar baru
    protected $fillable = ['ticket_id', 'user_id', 'comment'];

    // Komentar ini milik satu tiket tertentu
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Komentar ini ditulis oleh satu user (bisa user biasa atau IT Support)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
