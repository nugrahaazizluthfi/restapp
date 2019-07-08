<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use App\User;
use Validator;
use Auth;

class LogoutController extends APIController
{
    //
    /**
     * Logout Action
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */
    public function action(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user()->token();
            if ($user->revoke()) {
                return $this->baseResponse(["logout" => true], 'Logout Successfuly', 200);
            }
        }
        return $this->errorResponse(['error'=>'Something went wrong'], 500);
    }
}
