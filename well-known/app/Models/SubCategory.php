<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'status',
        'deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    /**
     * The 5 fixed main categories (mirrors ProductController::FIXED_CATEGORIES).
     */
    public const MAIN_CATEGORIES = [
        1 => 'Electronics',
        2 => 'Mobiles',
        3 => 'Fashion',
        5 => 'Appliances',
        4 => 'Hotels Resorts',
    ];

    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
