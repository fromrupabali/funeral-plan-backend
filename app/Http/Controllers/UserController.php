<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

use Tymon\JWTauth\Facades\JWTAuth;
use Tymon\JWTauth\Facades\JWTFactory;
use Tymon\JWTauth\Exceptions\JWTException;
use Tymon\JWTauth\Contracts\JWTSubject;
use Tymon\JWTauth\PayloadFactory;
use Tymon\JWTauth\JWTManager as JWT;

class UserController extends Controller
{
    public function register(Request $request){
        $user = User::create([
            'name' => $request -> json()->get('name'),
            'email' => $request -> json()->get('email'),
            'password' => $request -> json()->get('password'),
        ]);

        return response()->json(compact('user'), 201);
    }

    public function login(Request $request){
        $user =array(
            'email' => $request->get('email'),
            'password' => $request->get('password')
        );
        if(Auth::attempt($user)){
             return response()->json(compact('user'), 201);
        }else{
             return response()->json(['error' => 'invalid credentials'], 400);
        }
       /* $token="usertoken";
        return response()->json(compact('token'), 201);
        $credentials = $request-> json()->all();
        try{
            if(! $token = JWTAuth::attempt($credentials)){
               return response()->json(['error' => 'invalid credentials'], 400);
            }
        }catch(JWTEception $e){
             return response()->json(['error' => 'Could not create token'], 500);
        }
        return response()->json(compact('token'), 201);*/
    }

    public function getAuthenticateUser(){
        try{
            if(! $user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['User not found', 404]);
            }
        }catch(Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
             return response()->json(['token_expired', $e->getStatusCode()]);
        }
        catch(Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
             return response()->json(['token_invalid', $e->getStatusCode()]);
        }
        catch(Tymon\JWTAuth\Exceptions\JWTException $e){
             return response()->json(['token_absent', $e->getStatusCode()]);
        }

        return response()->json(compact('user'));
    }
}
