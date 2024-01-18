<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\ShopInfoController;
use App\Http\Controllers\TestController;
use App\Lib\AuthRedirection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Shopify\Utils;
use App\Models\Session;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\API\FileController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/auth', function (Request $request) {
    $shop = Utils::sanitizeShopDomain($request->query('shop'));

    // Delete any previously created OAuth sessions that were not completed (don't have an access token)
    Session::where('shop', $shop)->where('access_token', null)->delete();

    return AuthRedirection::redirect($request);
});
Route::get('/auth/install/callback', [AppController::class, 'authInstallCallback']);
Route::get('/auth/callback', [AppController::class, 'authCallback']);
Route::get('/login', [AppController::class, 'loginAuth'])
    ->name('shopify_login');

Route::group([
    'middleware' => ['auth'],
    'prefix' => 'api'
], function () {
    Route::get('test', [TestController::class, 'test']);
    Route::get('shop-info', [ShopInfoController::class, 'getShopInfo']);
    Route::get('/files', [FileController::class, 'index'])->name('files');
    Route::delete('/file/${id}', [FileController::class, 'destroy'])->name('destroy.file');
    Route::get('/integration-status', [\App\Http\Controllers\IntegrationController::class, 'index']);
});

Route::get('/oauth/redirect', [OAuthController::class, 'redirect']);
Route::get('/oauth/callback', [OAuthController::class, 'callback']);
Route::get('/oauth/refresh', [OAuthController::class, 'refresh']);

Route::post('/api/uninstall', [AppController::class, 'uninstalledWebhook'])->middleware('webhook-request');


Route::fallback([LogInController::class, 'index'])
    ->middleware(['shopify.installed', 'shopify.auth']);
