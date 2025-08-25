<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'code',
        'name',
        'native_name',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(Translation::class);
    }

    public static function getDefault()
    {
        return static::where('is_default', true)->first() ?? static::first();
    }

    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }
}
