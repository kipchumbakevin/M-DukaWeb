<?php

namespace App\Http\Controllers;

use App\Message;
use App\MessageImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function insert(Request $request){
        $userid = Auth::user()->id;
        $message = new Message();
        $imagee = new MessageImage();
        $message->user_id = $userid;
        $message->message=$request->input('message');
        $message->save();

        $new_message = Message::orderby('created_at', 'desc')->first();
        $this->validate($request, [

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $image = $request->file('image');
        $imagename = $image->getClientOriginalName();
        $image->move(public_path().'/imagesmessages/', $imagename);
        $image->message_id = $new_message->id;
        $image->imageuri=$imagename;
        $imagee->save();

		return response()->json([
            'message' => 'Added successfully',
        ], 201);
    }
}
