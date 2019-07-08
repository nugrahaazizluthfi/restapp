<?php

namespace App\Http\Controllers\API\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use App\User;
use Validator;
use Auth;

class ProfileController extends APIController
{
    /**
     * DISPLAY User Information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */
    public function displayInformation(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $userdata = $user->first();
            $success['user'] =  [
                'user_id' => !empty($userdata->id) ? $userdata->id : '',
                'name' => !empty($userdata->name) ? $userdata->name : '',
                'username' => !empty($userdata->username) ? $userdata->username : '',
                'email' => !empty($userdata->email) ? $userdata->email : '',
            ];
            $success['saldo'] = !empty($user->wallet->balance) ? rupiah($user->wallet->balance) : 0;
            $success['last_transaction'] = !empty($user->wallet->last_transaction) ? $user->wallet->last_transaction : '';
            $success['wallet_address'] = !empty($user->wallet->id) ? $user->wallet->id : '';

            return $this->baseResponse($success, 'Login Successfuly', 200);
        } else {
            return $this->errorResponse($th->getMessage(), 401);
        }
    }
}
