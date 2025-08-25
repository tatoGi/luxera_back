<?php

namespace App\Models;

use App\Models\PageOption;
use Illuminate\Database\Eloquent\Model;

class PageOptionsImage extends Model
{
    protected $fillable = ['page_option_id', 'image_name'];
    protected $table = 'page_options_image';
    // Define the relationship with the PageOption model
    public function pageOption()
    {
        return $this->belongsTo(PageOption::class);
    }
}
