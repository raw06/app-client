<?php

namespace App\Http\Middleware;

use App\Lib\AuthRedirection;
use App\Models\Session;
use Illuminate\Http\Request;
use Shopify\Utils;
use Closure;

class EnsureShopifyInstalled
{
    /**
     * Checks if the shop in the query arguments is currently installed.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $shop = $request->query('shop') ? Utils::sanitizeShopDomain($request->query('shop')) : null;

        $appInstalled = $shop && Session::where('shop', $shop)->where('access_token', '<>', null)->exists();
        return !$appInstalled ? AuthRedirection::redirect($request) : $next($request);
    }
}
