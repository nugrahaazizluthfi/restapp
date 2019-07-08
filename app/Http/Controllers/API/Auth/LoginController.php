<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use App\User;
use Validator;
use Auth;

class LoginController extends APIController
{
    /**
     * Custom Login Message Validation
     *
     * @return array
     */
    private function customMessage()
    {
        return [
            'usernameORemail.required' => 'Username atau Email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ];
    }

    /**
     * Login Action
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */
    public function action(Request $request)
    {
        // Validation request
        $validator = Validator::make(
            $request->all(),
            [
                'usernameORemail' => 'required',
                'password' => 'required',
            ],
            $this->customMessage()
        );
        
        if ($validator->fails()) {
            return $this->errorResponse(['validation' => [$validator->errors()]], 401);
        }

        // Cek Login User
        $attempt = [
            filter_var($request->usernameORemail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $request->usernameORemail,
            'password' => $request->password
        ];
            
        if (Auth::attempt($attempt)) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('indodax_wallet')->accessToken;
            return $this->baseResponse($success, 'Login Successfuly', 200);
        } else {
            return $this->errorResponse(['validation'=>[[ 'credentials' => 'Ooops.. Maaf, informasi akun yang anda masukan salah.']]], 401);
        }
    }
}
