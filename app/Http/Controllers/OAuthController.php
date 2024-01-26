<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use GuzzleHttp\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));
        $queries = http_build_query([
            'client_id' => config('integration.is.id'),
            'redirect_uri' => config('integration.is.redirect'),
            'response_type' => 'code',
            'scope' => ['read_files'],
            'shop_domain' => $request->get('shop'),
            'state' => $state,
        ]);
        $url = config("integration.is.url.authorize");
        return redirect("$url?" . $queries);
    }

    /**
     * @throws \Throwable
     */
    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');
        throw_unless(
            strlen($state) > 0 && $state === $request->state ,
            InvalidArgumentException::class
        );
        $url = config("integration.is.url.token");
        $response = Http::withoutVerifying()->post($url, [
            'grant_type' => 'authorization_code',
            'client_id' => config('integration.is.id'),
            'client_secret' => config('integration.is.secret'),
            'redirect_uri' => config('integration.is.redirect'),
            'code' => $request->code
        ]);

        $response = $response->json();
        $error = $request->get('error');

        if($error || Arr::has($response, 'error')) {
            logger()->error("Error authorize",  [
                'err' => $error,
                'response' => json_encode($response)
            ]);
            return redirect('/');
        }
        if($request->user()->shop()->token()) {
            $request->user()->shop()->token()->delete();
        }
        Integration::query()->updateOrCreate([
            'provider' => 'is',
            'shop_id' => $request->user()->shop()->id,
        ],[
            'status' => 1,
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']]
        );

        return redirect('/');
    }
}
