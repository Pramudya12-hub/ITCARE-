<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Model ini merepresentasikan data pengguna sistem (baik user biasa maupun IT Support)
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Kolom-kolom yang boleh diisi secara massal saat membuat atau mengupdate data user.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
        'last_login_at',
        'last_active_at',
    ];

    /**
     * Kolom yang disembunyikan saat data user diubah ke format JSON (misalnya password tidak ikut ditampilkan).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Satu user bisa memiliki banyak tiket keluhan yang pernah diajukan
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Tiket-tiket yang ditugaskan ke user ini (khusus IT Support)
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    // Satu user bisa memiliki banyak komentar pada berbagai tiket
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    // Artikel knowledge base yang pernah dibuat oleh user ini
    public function articles()
    {
        return $this->hasMany(KnowledgeBaseArticle::class, 'created_by');
    }
}
