<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login_user(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>'error',
                'message'=> 'Validation Error',
                'errors' => $validator->errors()
            ],422);
        }


        try {


            $user = User::where('username', $request->username)->first();


            if(! $user || !Hash::check($request->password, $user->password)){
                return response()->json([
                    'status'=> 'error',
                    'message'=> 'Invalid username or password.'
                ],401);
            }

            // Generating a valid token using username and email
            $token = base64_encode(json_encode([
                'username' => $user->username,
                'email' => $user->email,
                'timestamp' => now()->timestamp, // Add a timestamp for additional uniqueness
            ]));


            return response()->json([
                'status'=> 'success',
                'message'=> 'Login Successful',
                'user'=>[
                    'user_id' => $user->id,
                    'username'=>$user->username,
                    'email'=> $user->email,
                ],
                'token'=> $token,
            ],200)->cookie('auth_token', $token);

        } catch (\Exception $error) {

            // Handle unexpected errors
            return response()->json([
                'status'=> 'error',
                'message'=> "An error occurred during login.",
                'error'=> $error->getMessage()
            ] ,500);
        }


    }
}
