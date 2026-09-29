<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerSettlement extends Model
{
    use HasFactory;

    protected $table = 'seller_settlements';

    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'gross_amount',
        'commission_amount',
        'net_amount',
        'status',
        'paid_at',
        'reference_no',
        'note',
        'processed_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'paid_at' => 'datetime',
        'gross_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
