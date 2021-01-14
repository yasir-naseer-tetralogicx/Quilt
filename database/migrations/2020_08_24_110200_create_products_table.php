<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('handle')->nullable();
            $table->text('title')->nullable();
            $table->text('body_html')->nullable();
            $table->string('vendor')->nullable();
            $table->string('product_type')->nullable();
            $table->string('tags')->nullable();
            $table->string('published_at')->nullable();
            $table->string('option_one_name')->nullable();
            $table->string('option_one_value')->nullable();
            $table->string('option_two_name')->nullable();
            $table->string('option_two_value')->nullable();
            $table->string('option_three_name')->nullable();
            $table->string('option_three_value')->nullable();
            $table->string('varient_sku')->nullable();
            $table->string('varient_grams')->nullable();
            $table->string('varient_inventory_tracker')->nullable();
            $table->string('varient_inventory_qty')->nullable();
            $table->string('varient_inventory_policy')->nullable();
            $table->string('varient_fullfillment_service')->nullable();
            $table->string('varient_price')->nullable();
            $table->string('varient_compare_at_price')->nullable();
            $table->string('varient_requires_shipping')->nullable();
            $table->string('varient_taxable')->nullable();
            $table->string('varient_barcode')->nullable();
            $table->string('image_src')->nullable();
            $table->string('image_position')->nullable();
            $table->string('img_alt_text')->nullable();
            $table->string('gift_card')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('google_shipping_google_prod_category')->nullable();
            $table->string('google_shipping_gender')->nullable();
            $table->string('google_shipping_age_group')->nullable();
            $table->string('google_shipping_mpn')->nullable();
            $table->string('google_shipping_adword_grouping')->nullable();
            $table->string('google_shipping_adword_labels')->nullable();
            $table->string('google_shipping_condition')->nullable();
            $table->string('google_shipping_custom_product')->nullable();
            $table->string('google_shipping_custom_label_1')->nullable();
            $table->string('google_shipping_custom_label_2')->nullable();
            $table->string('google_shipping_custom_label_3')->nullable();
            $table->string('google_shipping_custom_label_4')->nullable();
            $table->string('varient_image')->nullable();
            $table->string('varient_weight_unit')->nullable();
            $table->string('varient_tax_code')->nullable();
            $table->string('cost_per_item')->nullable();
            $table->string('collection')->nullable();
            $table->string('other_info')->nullable();
            $table->string('embedded_video')->nullable();
            $table->string('html_page_number')->nullable();


            


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
