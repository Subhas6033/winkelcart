<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'order_number',
        'status',
        'admin_note',
        'resolved_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
