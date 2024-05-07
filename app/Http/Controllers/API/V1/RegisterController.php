<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Utils\ApiResponse;
class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request){
        $validator = self::validator($request->all());
        if(!$validator->fails()){
            $user = new User();
            $user->name = self::getRequestValue($request,"name");
            $user->email = self::getRequestValue($request,"email");
            $user->password = self::getRequestValue($request,"password");
            if($user->save()){
                $token = $user->createToken('Chat')->accessToken;
                return ApiResponse::success('User was created!',$token);
            }
            return ApiResponse::error('Error while try to create a new user!');
        }
        return ApiResponse::error('Error while try to create a new user!',500,$validator->errors()->all());
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

}