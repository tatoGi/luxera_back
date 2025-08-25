<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageOption extends Model
{
    use HasFactory, Translatable;
    protected $fillable = [ 'page_id', 'type_id'];
    public $translatedAttributes = ['locale', 'title', 'type'];
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
    public function images()
    {
        return $this->hasMany(PageOptionsImage::class);
    }
}
