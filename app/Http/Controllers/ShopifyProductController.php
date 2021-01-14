<?php

namespace App\Http\Controllers;

use App\ShopifyProduct;
use Illuminate\Http\Request;

class ShopifyProductController extends Controller
{

    public function index() {

        $products = ShopifyProduct::all();
        $api = HelperController::config();

        $total = $products->count();
        $duplicate = 0;

        if($total > 0) {
            $grouped = $products->groupBy('title')->map(function ($row) {
                return $row->count();
            });


            $result = [];
            foreach ($grouped as $index => $count) {
                if($count > 1) {
                    $result[] = $index;
                }
            }

            foreach ($result as $title) {
                $products = ShopifyProduct::where('title', $title)->get();
                if($products[0]->variants_count == $products[1]->variants_count) {
                    $duplicate++;
                }
            }
        }

        return view('welcome')->with('total', $total)->with('duplicate', $duplicate);
    }


    public function storeProducts($next = null)
    {

        $api = HelperController::config();

        $products = $api->rest('GET', '/admin/products.json', [
            'limit' => 250,
            'page_info' => $next
        ]);

        if(!$products['errors']) {
            foreach ($products['body']['container']['products'] as $product) {
                $this->createProduct($product);
            }

            if (isset($products['link']['next'])) {
                $this->storeProducts($products['link']['next']);
            }
        }
        else {
            return redirect()->back()->with('error', 'Something went wrong, please try again!');

        }
        return redirect()->back()->with('success', 'Products Synced Successfully!');
    }

    public function createProduct($product) {

        if(ShopifyProduct::where('id', $product['id'])->exists()) {
            $p = ShopifyProduct::find($product['id']);
        }
        else {
            $p = new ShopifyProduct();
        }

        $p->id = $product['id'];
        $p->title =  $product['title'];
        $p->body_html = $product['body_html'];
        $p->handle = $product['handle'];
        $p->variants_count = count($product['variants']);
        $p->save();

    }

    public function getDuplicates() {
        $products = ShopifyProduct::all();
        $api = HelperController::config();

        $grouped = $products->groupBy('title')->map(function ($row) {
                return $row->count();
        });


        $result = [];
        foreach ($grouped as $index => $count) {
            if($count > 1) {
              $result[] = $index;
            }
        }


        if(count($result) > 0)
        {
            foreach ($result as $title) {
                $products = ShopifyProduct::where('title', $title)->get();

                if($products[0]->variants_count == $products[1]->variants_count) {
                    $products[0]->delete();
                    $products = $api->rest('DELETE', '/admin/products/'.$products[0]->id.'.json');
                }
            }

            return redirect()->back()->with('success', 'Duplicates Removed Successfully!');
        }
    }
}


