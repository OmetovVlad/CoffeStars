<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'users' => \App\Http\Controllers\Api\UserController::class,
    'logs' => \App\Http\Controllers\Api\LogController::class,
    'accrual' => \App\Http\Controllers\Api\AccrualController::class,
//    'pay' => \App\Http\Controllers\Api\PayController::class,
]);

Route::post('/users/swap', [App\Http\Controllers\Api\UserController::class, 'swap']);

// Route::post('/pay/result', [App\Http\Controllers\Api\PayController::class, 'result']); //PAYOK
Route::get('/pay/result', [App\Http\Controllers\Api\PayController::class, 'result']);
Route::get('/pay/create', [App\Http\Controllers\Api\PayController::class, 'create']);

Route::get('/login', function (Request $request) {
    if ( isset($request['id']) && isset($request['password']) ) {
        if (User::findOrFail($request['id'])) {
            $user_data = User::findOrFail($request['id']);

            if ( $user_data['remember_token'] === $request['password'] ) {
                if (Auth::loginUsingId($request['id'])) {
                    $request->session()->regenerate();

                    return redirect('/');
                }
            }
        }
    }

    abort(404);
});


