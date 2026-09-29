<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Category extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'categories';
    protected $fillable = [
        'id',
        'name',
        'desc',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at'
    ];

    public function products()
    {
        return $this->hasMany(Product::class)
            ->where('deleted', 0)
            ->where('status', 0)
            ->fromVerifiedSellers();
    }
}
