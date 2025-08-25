<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'type_id',
        'thumb',
        'author_id',
        'date',
    ];

    public $translatedAttributes = [
        'title',
        'slug',
        'desc',
    ];

    public function images()
    {
        return $this->hasMany(BannerImage::class);
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'banner_page')
            ->withPivot('sort')
            ->orderBy('banner_page.sort');
    }
}
