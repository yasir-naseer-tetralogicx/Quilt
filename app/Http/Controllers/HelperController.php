<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Osiset\BasicShopifyAPI\BasicShopifyAPI;
use Osiset\BasicShopifyAPI\Options;
use Osiset\BasicShopifyAPI\Session;

class HelperController extends Controller
{
    public static function config() {

        // Create options for the API
        $options = new Options();
        $options->setType(true); // Makes it private
        $options->setVersion('2020-01');
        $options->setApiKey(env('SHOPIFY_API_KEY'));
        $options->setApiSecret(env('SHOPIFY_API_SECRET'));
        $options->setApiPassword(env('SHOPIFY_API_PASSWORD'));


        // Create the client and session
        $api = new BasicShopifyAPI($options);
        $api->setSession(new Session("quilt-direct.myshopify.com"));


        return $api;


    }
}
