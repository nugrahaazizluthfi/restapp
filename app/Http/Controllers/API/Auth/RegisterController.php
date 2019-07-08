<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\API\APIController;
use App\User;
use App\Wallet;
use Validator;
use Auth;

class RegisterController extends APIController
{
    /**
     * Custom Register Message Validation
     *
     * @return array
     */
    private function customMessage()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'username.required' => 'Username tidak boleh kosong',
            'username.unique' => 'Username sudah terdaftar dalam database kami',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar dalam database kami',
            'password.required' => 'Password tidak boleh kosong',
        ];
    }

    /**
     * Register User Action
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
                'name' => 'required',
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ],
            $this->customMessage()
        );

        if ($validator->fails()) {
            return $this->errorResponse(['validation' => [$validator->errors()]], 400);
        }

        try {
            // Save data user to db
            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);

            if ($user->save()) {
                $wallet = new Wallet;
                $wallet->balance = 0;
                $wallet->user_id = $user->id;
                $wallet->save();
            }

            // data for json response
            $data['token'] =  $user->createToken('indodax_wallet')->accessToken;
        } catch (\Exception $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }

        return $this->baseResponse($data, 'Login Successfuly', 200);
    }
}
