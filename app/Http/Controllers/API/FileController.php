<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Traits\InstalledShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FileController extends Controller
{
    use InstalledShop;
    public function index() {
        /** @var Shop $shop */
        $shop = $this->shop();
        $query = http_build_query([
            'shop' => $shop->shop
        ]);
        if(!$shop->token()) {
            return response()->json([
                'success' => false,
                'message' => "Not integrated!"
            ]);
        }
        $url = config("integration.is.url.integration") . 'files';
        $response = Http::withoutVerifying()->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $shop->token()->access_token,
        ])->get($url. "?". $query);

        if($response->unauthorized()) {
            return response()->json([
                'success' => false,
                'message' => "Unauthorized"
            ], 201);
        }
        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }

    public function destroy($id) {
        /** @var Shop $shop */
        $shop = $this->shop();
        if(!$shop->token()) {
            return response()->json([
                'success' => false,
                'message' => "Not integrated!"
            ]);
        }
        if(!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Not found file'
            ]);
        }

        $url = config("integration.is.url.integration") . 'file' . "/$id";
        $response = Http::withoutVerifying()->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $shop->token()->access_token,
        ])->delete($url);
        dd($response);
        if($response->unauthorized()) {
            return response()->json([
                'success' => false,
                'message' => "Unauthorized"
            ], 201);
        }
        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }
}
