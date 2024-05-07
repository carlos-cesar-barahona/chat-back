<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Utils\ApiResponse;
use Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function isLoggedIn(Request $request){
        $user = $request->user();
        if($user){
            return ApiResponse::success('Success!',$user);
        }
        return ApiResponse::error('Error',500);
    }

    public function getRequestValue($request, $node){
        return $request->get($node);
    }    

    protected function validator($data){
        return Validator::make($data, [
            'name' => 'bail|required|string|max:60',
            'email' => 'required|string|email:dns|max:45|unique:users'
        ]);
    }

    public function logOut(Request $request){
        $request->user()->tokens()->delete();
        return ApiResponse::success('Success!');
    }

    public function logIn(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('cellia')->accessToken;
            return ApiResponse::success('Success!',$token);
        }

        return ApiResponse::error('Error al iniciar session',500);
    }

}