<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopifyProduct extends Model
{
    protected $fillable = ['id','title', 'handle', 'body_html'];
}
