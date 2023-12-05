<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function redirect(Request $request)
    {
        $queries = http_build_query([
            'client_id' => config('integration.is.id'),
            'redirect_uri' => config('integration.is.redirect'),
            'response_type' => 'code',
            'scope' => ['read_files', 'write_files'],
            'shop_domain' => $request->get('shop'),
        ]);
        $url = config("integration.is.url.authorize");
        return redirect("$url?" . $queries);
    }

    public function callback(Request $request)
    {
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
        if($error) {
            logger()->error("Error authorize",  [
                'err' => $error
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

    public function refresh(Request $request)
    {
        $url = config("integration.is.url.token");
        $response = Http::withoutVerifying()->post($url, [
            'grant_type' => 'refresh',
            'client_id' => config('integration.is.id'),
            'client_secret' => config('integration.is.secret'),
            'redirect_uri' => config('integration.is.redirect'),
            'scope' => ['read_files', 'write_files']
        ]);

        if ($response->status() !== 200) {
            $request->user()->token()->delete();

            return redirect('/')
                ->withStatus('Authorization failed from OAuth server.');
        }

        $response = $response->json();
        Integration::query()->update([
            'provider' => 'is',
            'shop_id' => $request->user()->shop()->id,
            'status' => 1,
            'access_token' => $response['access_token'],
            'expires_in' => $response['expires_in'],
            'refresh_token' => $response['refresh_token']]
        );

        return redirect('/');
    }
}
