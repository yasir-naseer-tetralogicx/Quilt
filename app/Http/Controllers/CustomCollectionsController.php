<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HelperController;
use App\ShopifyCollection;
use Illuminate\Http\Request;
use App\CustomCollection;
use App\Product;
use Importer;

class CustomCollectionsController extends Controller
{

    public function uploadCsvToDatabase() {

        $execlPath = public_path('csv\test.xlsx');
        $excel = Importer::make('Excel');
        $excel->load($execlPath);
        $collection = $excel->getCollection();

        for($row=1; $row< sizeof($collection); $row++) {
            try{
                if(!(ShopifyCollection::where('title', $collection[$row][0])->exists())){

                    $col = new ShopifyCollection();
                    $col->title =  $collection[$row][0];
                    $col->collection_text = $collection[$row][1] == '' ? null : $collection[$row][1];
                    $col->meta_description = $collection[$row][2]  == '' ? null : $collection[$row][2];
                    $col->meta_keywords = $collection[$row][3]  == '' ? null : $collection[$row][3];
                    $col->page_title = $collection[$row][4]  == '' ? null : $collection[$row][4];
                    $col->section_image = $collection[$row][5]  == '' ? null : $collection[$row][5];
                    $col->page_name = $collection[$row][6]  == '' ? null : $collection[$row][6];
                    $col->save();


                }
                else {
                    $col = ShopifyCollection::where('title', $collection[$row][0])->first();

                    $col->title =  $collection[$row][0];
                    $col->collection_text = $collection[$row][1] == '' ? null : $collection[$row][1];
                    $col->meta_description = $collection[$row][2]  == '' ? null : $collection[$row][2];
                    $col->meta_keywords = $collection[$row][3]  == '' ? null : $collection[$row][3];
                    $col->page_title = $collection[$row][4]  == '' ? null : $collection[$row][4];
                    $col->section_image = $collection[$row][5]  == '' ? null : $collection[$row][5];
                    $col->page_name = $collection[$row][6]  == '' ? null : $collection[$row][6];
                    $col->save();

                }

            }catch(\Exception $e) {
                dd($e);
            }
        }
    }

    public function push() {

        foreach(ShopifyCollection::where('status', 0)->take(30)->get() as $customCollection)
        {
            $customCollection = $customCollection->toArray();
            $data = [];
            $custom_collection_array_to_be_passed = [];

            $data['title'] = $customCollection['title'] ? $customCollection['title'] : null;
            $data['collection_text'] = $customCollection['collection_text'] ? $customCollection['collection_text'] : null;
            $data['meta_description'] = $customCollection['meta_description'] ? $customCollection['meta_description'] : null;
            $data['meta_description'] = $customCollection['meta_description'] ? $customCollection['meta_description'] : null;
            $data['meta_keywords'] = $customCollection['meta_keywords'] ? $customCollection['meta_keywords'] : null;
            $data['section_image'] = $customCollection['section_image'] ? $customCollection['section_image'] : null;
            $data['page_name'] = $customCollection['page_name'] ? $customCollection['page_name'] : null;
            $data['page_title'] = $customCollection['page_title'] ? $customCollection['page_title'] : null;


            if($data['title']) {
                $custom_collection_array_to_be_passed['smart_collection']['title'] = $data['title'];
            }

            if($data['collection_text']) {
                $custom_collection_array_to_be_passed['smart_collection']['body_html'] = $data['collection_text'];
            }

            if($data['meta_description']) {
                $custom_collection_array_to_be_passed['smart_collection']['metafields_global_description_tag'] = $data['meta_description'];
            }

            if($data['meta_keywords']) {
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][0]['value'] = $data['meta_keywords'];
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][0]['key'] = "meta_keyword";
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][0]['value_type'] = "string";
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][0]['namespace'] = "global";
            }

            if($data['page_name'] && is_null($data['meta_keywords'])) {
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][0]['value'] = $data['page_name'];
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][0]['key'] = "page_name";
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][0]['value_type'] = "string";
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][0]['namespace'] = "global";
            }
            else if($data['page_name'] && $data['meta_keywords']){
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][1]['value'] = $data['page_name'];
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][1]['key'] = "page_name";
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][1]['value_type'] = "string";
                $custom_collection_array_to_be_passed['smart_collection']['metafields'][1]['namespace'] = "global";
            }

            if($data['section_image']) {
                if($this->endsWith($data['section_image'],".jpg") || $this->endsWith($data['section_image'],".png") ) {
                    $custom_collection_array_to_be_passed['smart_collection']['image']['src'] = $data['section_image'];
                    $custom_collection_array_to_be_passed['smart_collection']['image']['alt'] = "no img";

                }

            }


            if($data['page_title']) {
                $custom_collection_array_to_be_passed['smart_collection']['metafields_global_title_tag'] = $data['page_title'];
            }

            $custom_collection_array_to_be_passed['smart_collection']['rules'][0]['column'] = 'tag';
            $custom_collection_array_to_be_passed['smart_collection']['rules'][0]['relation'] = 'equals';
            $custom_collection_array_to_be_passed['smart_collection']['rules'][0]['condition'] = $customCollection['title'];


            if($customCollection['shopify_collection_id'] == null && $customCollection['status'] == 0) {
                $api = HelperController::config();
                $result = $api->rest('POST', '/admin/smart_collections.json', $custom_collection_array_to_be_passed,[],true);
                if($result['errors'] == false) {
                    $coll = ShopifyCollection::find($customCollection['id']);
                    $coll->shopify_collection_id = $result['body']['container']['smart_collection']['id'];
                    $coll->status = 1;
                    $coll->save();

                }
                else {
                    dd('new collection error', $result);
                }
            }
            else if($customCollection['shopify_collection_id'] != null && $customCollection['status'] == 0){
                $api = HelperController::config();
                $result = $api->rest('PUT', '/admin/smart_collections/'.$customCollection['shopify_collection_id'].'.json', $custom_collection_array_to_be_passed,[],true);
                if(!($result['errors'] == false)) {
                    dd('old collection error', $result);
                }
                else {
                    $coll = ShopifyCollection::find($customCollection['id']);
                    $coll->status = 1;
                    $coll->save();
                }
            }
        }
    }


//    public function pushCollectionToShopify() {
//
//        foreach(ShopifyCollection::all() as $customCollection) {
//            $customCollection = $customCollection->toArray();
//            dd($customCollection);
//            $data = [];
//            $custom_collection_array_to_be_passed = [];
//            $data['collection_text'] = $customCollection['collection_text'] ? $customCollection['collection_text'] : null;
//            $data['collection_description'] = $customCollection['collection_description'] ? $customCollection['collection_description'] : null;
//            $data['meta_description'] = $customCollection['meta_description'] ? $customCollection['meta_description'] : null;
//            $data['meta_keywords'] = $customCollection['meta_keywords'] ? $customCollection['meta_keywords'] : null;
//            $data['collection_image_file_name_source'] = $customCollection['collection_image_file_name_source'] ? $customCollection['collection_image_file_name_source'] : null;
//            $data['collection_image_file_name_two'] = $customCollection['collection_image_file_name_two'] ? $customCollection['collection_image_file_name_two'] : null;
//            $data['page_title'] = $customCollection['page_title'] ? $customCollection['page_title'] : null;
//
//
//            if($data['collection_text']) {
//                $custom_collection_array_to_be_passed['custom_collection']['title'] = $data['collection_text'];
//            }
//
//            if($data['collection_description']) {
//                $custom_collection_array_to_be_passed['custom_collection']['body_html'] = $data['collection_description'];
//            }
//
//            if($data['meta_description']) {
//                $custom_collection_array_to_be_passed['custom_collection']['metafields_global_description_tag'] = $data['meta_description'];
//            }
//
//            if($data['meta_keywords']) {
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][0]['value'] = $data['meta_keywords'];
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][0]['key'] = "meta_keyword";
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][0]['value_type'] = "string";
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][0]['namespace'] = "global";
//            }
//
//            if($data['collection_image_file_name_source']) {
//                if($this->endsWith($data['collection_image_file_name_source'],".jpg") || $this->endsWith($data['collection_image_file_name_source'],".png") ) {
//                    $custom_collection_array_to_be_passed['custom_collection']['image']['src'] = $data['collection_image_file_name_source'];
//                    $custom_collection_array_to_be_passed['custom_collection']['image']['alt'] = "no img";
//                }
//
//            }
//
//            if($data['collection_image_file_name_two'] && is_null($data['meta_keywords'])) {
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][0]['value'] = $data['collection_image_file_name_two'];
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][0]['key'] = "collection_image_file_name_two";
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][0]['value_type'] = "string";
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][0]['namespace'] = "global";
//            }
//            else if($data['collection_image_file_name_two'] && $data['meta_keywords']){
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][1]['value'] = $data['collection_image_file_name_two'];
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][1]['key'] = "collection_image_file_name_two";
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][1]['value_type'] = "string";
//                $custom_collection_array_to_be_passed['custom_collection']['metafields'][1]['namespace'] = "global";
//            }
//
//            if($data['page_title']) {
//                $custom_collection_array_to_be_passed['custom_collection']['metafields_global_title_tag'] = $data['page_title'];
//            }
//
//
//            $api = HelperController::config();
//
//            $result = $api->rest('POST', '/admin/custom_collections.json', $custom_collection_array_to_be_passed,[],true);
//
//            $coll = CustomCollection::find($customCollection['id']);
//            $coll->status = 1;
//            $coll->collection_id = $result['body']['container']['custom_collection']['id'];
//            $coll->save();
//
//        }
//
//
//
//    }

    function endsWith($string, $endString)
    {
        $len = strlen($endString);
        if ($len == 0) {
            return true;
        }
        return (substr($string, -$len) === $endString);
    }

    public function uploadCsvTwoToDatabase() {


        $execlPath = public_path('csv\cvs_two.xlsx');

        $excel = Importer::make('Excel');
        $excel->load($execlPath);
        $collection = $excel->getCollection();


            for($row=1; $row< sizeof($collection); $row++) {
                try{
                    Product::create([
                        'handle' => $collection[$row][0],
                        'title' => $collection[$row][1],
                        'body_html' => $collection[$row][2],
                        'vendor' => $collection[$row][3],
                        'product_type' => $collection[$row][4],
                        'tags' => $collection[$row][5],
                        'published_at' => $collection[$row][6],
                        'option_one_name' => $collection[$row][7],
                        'option_one_value' => $collection[$row][8],
                        'option_two_name' => $collection[$row][9],
                        'option_two_value' => $collection[$row][10],
                        'option_three_name' => $collection[$row][11],
                        'option_three_value' => $collection[$row][12],
                        'varient_sku' => $collection[$row][13],
                        'varient_grams' => $collection[$row][14],
                        'varient_inventory_tracker' => $collection[$row][15],
                        'varient_inventory_qty' => $collection[$row][16],
                        'varient_inventory_policy' => $collection[$row][17],
                        'varient_fullfillment_service' => $collection[$row][18],
                        'varient_price' => $collection[$row][19],
                        'varient_compare_at_price' => $collection[$row][20],
                        'varient_requires_shipping' => $collection[$row][21],
                        'varient_taxable' => $collection[$row][22],
                        'varient_barcode' => $collection[$row][23],
                        'image_src' => $collection[$row][24],
                        'image_position' => $collection[$row][25],
                        'img_alt_text' => $collection[$row][26],
                        'gift_card' => $collection[$row][27],
                        'seo_title' => $collection[$row][28],
                        'seo_description' => $collection[$row][29],
                        'google_shipping_google_prod_category' => $collection[$row][30],
                        'google_shipping_gender' => $collection[$row][31],
                        'google_shipping_age_group' => $collection[$row][32],
                        'google_shipping_mpn' => $collection[$row][33],
                        'google_shipping_adword_grouping' => $collection[$row][34],
                        'google_shipping_adword_labels' => $collection[$row][35],
                        'google_shipping_condition' => $collection[$row][36],
                        'google_shipping_custom_product' => $collection[$row][37],
                        'google_shipping_custom_label_1' => $collection[$row][38],
                        'google_shipping_custom_label_2' => $collection[$row][39],
                        'google_shipping_custom_label_3' => $collection[$row][40],
                        'google_shipping_custom_label_4' => $collection[$row][41],
                        'varient_image' => $collection[$row][42],
                        'varient_weight_unit' => $collection[$row][43],
                        'varient_tax_code' => $collection[$row][44],
                        'cost_per_item' => $collection[$row][45],
                        'collection' => $collection[$row][46],
                        'other_info' => $collection[$row][47],
                        'embedded_video' => $collection[$row][48],
                        'html_page_number' => $collection[$row][49],

                    ]);
                }catch(\Exception $e) {
                    dd($e);
                }
            }

    }




}
