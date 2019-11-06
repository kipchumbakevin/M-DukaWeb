<?php

namespace App\Http\Controllers;

use App\Message;
use App\MessageImage;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function insert(Request $request){
        $userid = $request->user()->id;
        $message = new Message();
        $image = new MessageImage();
        $image->user_id = $userid;
        $message->message=$request->input('message');
        $message->save();

        $new_message = Message::orderby('created_at', 'desc')->first();
        $image->message_id = $new_message->id;
        $image->imageuri=$request->input('image');
        $image->save();

		return response()->json([
            'message' => 'Added successfully',
        ], 201);
    }
}
