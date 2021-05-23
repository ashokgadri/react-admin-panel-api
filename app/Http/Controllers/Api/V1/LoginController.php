<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }



        $credentials = $request->only('email', 'password');

       

        if (Auth::attempt($credentials)) {

            $token = $request->user()->createToken('API Token');

            return response()->json([
                'message' => 'User logged in successfully.',
                'user' => [
                    'email' => $request->user()->email,
                    'name' => $request->user()->name
                ],
                'token' => $token->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Username or Password is incorrect.'
        ], 403);
    }
}
