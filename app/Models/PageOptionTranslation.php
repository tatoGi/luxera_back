<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageOptionTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['page_option_id', 'locale', 'title', 'type'];

   
}
