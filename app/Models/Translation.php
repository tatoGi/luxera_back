<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'language_id',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public static function getTranslation($group, $key, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        return static::whereHas('language', function($query) use ($locale) {
                $query->where('code', $locale);
            })
            ->where('group', $group)
            ->where('key', $key)
            ->value('value');
    }
}
