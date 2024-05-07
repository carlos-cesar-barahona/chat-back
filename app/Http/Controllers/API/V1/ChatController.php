<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Carbon\Carbon;
use App\Models\Chat;
use App\Models\User;
use App\Utils\ApiResponse;
use Illuminate\Support\Facades\Storage;
class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAll(Request $request)
    {
        $users = User::orderBy("id","Desc")->get();
        foreach($users as $user){
            if($user->id == $request->user()->id){
                $user->name = 'My Chat';
            }
        }

        return ApiResponse::success('Success!',$users);
    }


    public function getById(Request $request, $userId)
    {
        if (!$userId) {
            return ApiResponse::error('Error', 500, "ID is required");
        }

        $user_id = $request->user()->id;

        $chats = Chat::where(function ($query) use ($userId, $user_id) {
                $query->where('from_id', $user_id)->where('to_id', $userId);
            })
            ->orWhere(function ($query) use ($userId, $user_id) {
                $query->where('from_id', $userId)->where('to_id', $user_id);
            })
            ->orderBy("id","ASC")
            ->get();

        $chats->transform(function ($chat) {
            if ($chat->type === Chat::TYPE_FILE) {
                $chat->url = url('storage/chats/photos/' . $chat->id . '/' . $chat->message);
            }
            return $chat;
        });

        return ApiResponse::success('Success!', $chats);
    }


    public function getUserInfoById(Request $request, $userId){

        if(!$userId){
            return ApiResponse::error('Error ',500,"Id id required");
        }

        $user = User::where("id",$userId)->first();
        return ApiResponse::success('Success!',$user);
    }    

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|string|max:60',
            'message' => 'required|string|max:255'
        ]);

        if(!$validator->fails()){
            return ApiResponse::error('Error ',500,$validator->errors()->all());
        }

        $chat = new Chat();
        $chat->message = $request->get("message");
        $chat->from_id = $request->user()->id;
        $chat->to_id = $request->id;
        $chat->created_at = Carbon::now();
        $chat->updated_at = Carbon::now();
        if($chat->save()){
            return ApiResponse::success('Success!',$chat);
        }
        return ApiResponse::error('Error ',500,"Error while sent message");
    }


    public function flieUpload(Request $request)
    {
        try{
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');

                $chat = new Chat();
                $chat->message = $photo->getClientOriginalName();
                $chat->from_id = $request->user()->id;
                $chat->to_id = $request->id;
                $chat->created_at = Carbon::now();
                $chat->updated_at = Carbon::now();
                $chat->type = Chat::TYPE_FILE;
                if($chat->save()){
                    $photo->storeAs('chats/photos/'.$chat->id, $photo->getClientOriginalName(),'public');
                };
                return ApiResponse::success('Success!');
            }
            return ApiResponse::error('Error ',500,"Error");
        }catch(Exception $ex){
            return ApiResponse::success('Success!',$ex);
        }
    }

}