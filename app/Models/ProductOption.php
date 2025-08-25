<?php

namespace App\Models;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory, Translatable;
    protected $fillable = [ 'product_id', 'type_id'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
