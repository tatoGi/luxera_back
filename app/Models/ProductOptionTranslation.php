<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['product_option_id', 'locale', 'title', 'type'];
}
