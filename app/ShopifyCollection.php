<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopifyCollection extends Model
{
    protected $fillable = [
        'title',
        'shopify_collection_id',
        'collection_text',
        'meta_description',
        'meta_keywords',
        'page_title',
        'section_image',
        'page_name'
    ];
}
