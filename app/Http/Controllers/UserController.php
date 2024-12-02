<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register_user (Request $request){

       

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username|max:255',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }


        try{

            
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make( $request->password );
            $user->save();
    
    
            return response()->json([
                'status'=> 'success',
                'message'=> 'User Created Successfully',
                'created_user' => [
                    'user_id'=> $user->id,
                    'email'=> $user->email,
                ]
            ],201);

        }catch(\Exception $error){
            return response()->json([ 
                'status'=> 'error',
                'message'=> 'An error occurred while creatin gthe user.',
                'error' => $error->getMessage()
            ],500);

          
        }
    }
}
