<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'title', 
        'category', 
        'priority', 
        'description', 
        'image', 
        'status',
        'assigned_to',
        'first_response_at',
        'resolved_at',
        'ai_recommendation', 
        'ai_summary'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedSupport()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }   

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }
}
