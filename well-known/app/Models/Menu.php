<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *  
     * @var array
     */
    protected $table = 'menus';
    protected $fillable = [
        'menu_title',
        'parent_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'status'
    ];

    public function url()
    {
        return $this->hasMany(MenuUrl::class, 'menu_id', 'id');
    }
}
