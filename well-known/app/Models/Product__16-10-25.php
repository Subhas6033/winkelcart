<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Product extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'product';
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'image',
        'total_price',
        'offer_price',
        'company',
        'desc',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
