<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomCollection extends Model
{
    protected $fillable = [
        'collection_text', 
        'collection_description', 
        'meta_description', 
        'meta_keywords', 
        'page_title', 
        'collection_image_file_name_source', 
        'collection_image_file_name_two', 
        'page_name'
    ];
}
