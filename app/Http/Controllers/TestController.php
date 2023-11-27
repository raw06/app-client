<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Question;
use App\Services\Elasticsearch\ElasticsearchSearchService;
use App\Services\Elasticsearch\ElasticsearchSearchServiceImpl;
use App\Services\Elasticsearch\ElasticsearchService;
use App\Services\Metafield\MetafieldService;
use App\Services\Product\ProductService;
use App\Services\Question\QuestionService;
use App\Traits\InstalledShop;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Shopify\Clients\Rest;
use Shopify\Rest\Admin2023_04\Metafield;
use Shopify\Utils;

class TestController extends Controller
{
    use InstalledShop;
    public function test() {

        return response()->json('1');
    }

}
