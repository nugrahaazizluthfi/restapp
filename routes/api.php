<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// ACTION LOGIN
Route::post('login', 'API\Auth\LoginController@action');

// ACTION REGISTER
Route::post('register', 'API\Auth\RegisterController@action');

Route::group(['middleware' => 'auth:api'], function () {
    // ACTION LOGOUT
    Route::post('logout', 'API\Auth\LogoutController@action');
    
    // ACTION TRANSFER
    Route::post('transfer', 'API\Trx\WALLET_TransferSaldoController@action');
    
    // INITIAL LOAD DATA
    Route::get('userinfo', 'API\Profile\ProfileController@displayInformation');

    // GET SALDO DATA
    Route::get('saldo', 'API\Wallet\CheckBalanceController@getSaldoData');
    Route::get('saldo/detail/{id}', 'API\Wallet\CheckBalanceController@getDetail');
    Route::get('saldo/total', 'API\Wallet\CheckBalanceController@getTotal');

    // GET TRANSACTION DATA
    Route::get('transaction', 'API\TRX\TRX_CheckTransactionController@getTransactionData');
    Route::get('transaction/detail/{id}', 'API\TRX\TRX_CheckTransactionController@getDetail');
    Route::get('transaction/total', 'API\TRX\TRX_CheckTransactionController@getTotal');

    // DEPOSIT
    Route::post('deposit', 'API\TRX\TRX_DepositSaldoController@action');
});
