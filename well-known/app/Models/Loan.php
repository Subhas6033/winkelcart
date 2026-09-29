<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Loan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'loan';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
    public function bank_emi()
    {
        return $this->belongsTo(Bank::class, 'emi_bank', 'id');
    }

    protected $fillable = [
        'id',
        'bank_id',
        'product_id',
        'sanction_date',
        'borrower_name',
        'co_borrower_name',
        'amount_sactioin',
        'tenour',
        'repo',
        'mclr',
        'base',
        'plr',
        'spread_rate',
        'calculate_rate',
        'amount_emi',
        'security',
        'closure_charge',
        'any_special_condition',
        'emi_date',
        'distribution_date',
        'emi_bank',
        'emi_acc_no',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
        'status',
        'deleted'
    ];
}
