<?php
declare(strict_types=1);

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

/** @noinspection PhpUndefinedMethodInspection */
Auth::routes();

Route::get(
    '{any}',
    function () {

        if (!($appUrl = env('APP_URL'))) {
            $scheme = env('FORCE_HTTPS') ? 'https' : Request::getScheme();
            $host = Request::getHttpHost();
            $appUrl = "$scheme://$host";
        }

        $vars = [
            'apiBaseUrl' => "$appUrl/api",
            'cloudinaryCloudName' => config('cloudinary.defaults.cloud_name'),
            'sentryDsn' => env('SENTRY_LARAVEL_DSN'),
        ];
        return view('spa', ['jsVars' => $vars]);
    }
)->where('any', '.*');
