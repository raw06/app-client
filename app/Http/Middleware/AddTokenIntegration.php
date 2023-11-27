<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use Closure;
use Illuminate\Http\Request;

class AddTokenIntegration
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
        $shop = Shop::query()->select('shop', $request->request('shop'))->first();

        if($shop) {
            return redirect('/');
        }
        $request->headers([
            'Accept' => 'application/json',
            'Authorization' =>  'Bearer ' . auth()->user()->shop()->token->access_token
        ]);
        return $next($request);
    }
}
