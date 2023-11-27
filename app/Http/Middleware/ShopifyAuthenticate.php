<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shopify\Auth\OAuth;

class ShopifyAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $shop = $request->query('shop');
        if (!auth()->check()){
            return redirect()->to("login?shop=$shop");
        }
        return $next($request);
    }
}
