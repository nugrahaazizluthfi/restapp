<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use Validator;
use App\User;
use Auth;

class UserController extends APIController
{
    public function details()
    {
        $user = Auth::user();
        return $this->baseResponse($user, 'Login Successfuly', 200);
    }
}
