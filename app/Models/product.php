<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
}
