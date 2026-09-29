<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    protected $table = 'user_info';

    protected $fillable = [
        'user_id',
        'address',
        'business_category_id',
        'state_id',
        'country_id',
        // add other columns you save using ::create()
    ];

}
