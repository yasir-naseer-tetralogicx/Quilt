<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'handle', 
        'title', 
        'body_html', 
        'vendor', 
        'product_type', 
        'tags',
        'published_at', 
        'option_one_name', 
        'option_one_value',
        'option_two_name',
        'option_two_value',
        'option_three_name',
        'option_three_value',
        'varient_sku',
        'varient_grams',
        'varient_inventory_tracker',
        'varient_inventory_qty',
        'varient_inventory_policy',
        'varient_fullfillment_service',
        'varient_price',
        'varient_compare_at_price',
        'varient_requires_shipping',
        'varient_taxable',
        'varient_barcode',
        'image_src',
        'image_position',
        'img_alt_text',
        'gift_card',
        'seo_title',
        'seo_description',
        'google_shipping_google_prod_category',
        'google_shipping_gender',
        'google_shipping_age_group',
        'google_shipping_mpn',
        'google_shipping_adword_grouping',
        'google_shipping_adword_labels',
        'google_shipping_condition',
        'google_shipping_custom_product',
        'google_shipping_custom_label_1',
        'google_shipping_custom_label_2',
        'google_shipping_custom_label_3',
        'google_shipping_custom_label_4',
        'varient_image',
        'varient_weight_unit',
        'varient_tax_code',
        'cost_per_item',
        'collection',
        'other_info',
        'embedded_video',
        'html_page_number'

    ];
}
