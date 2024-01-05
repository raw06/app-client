<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use App\Traits\InstalledShop;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    use InstalledShop;

    public function index() {
        $shopId = $this->shopId();
        $integration = Integration::query()->where('shop_id', $shopId)->first();

        if(!$integration) {
            return response()->json([
                'success' => false,
                'data' => false
            ]);
        }

        return response()->json([
            'success'=> true,
            'data' => $integration->status
        ]);
    }
}
