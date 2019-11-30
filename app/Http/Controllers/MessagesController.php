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
        $message->user_id = $userid;
        $message->message=$request->input('message');
        $message->save();
        $new_message = Message::orderby('created_at', 'desc')->first();
        $this->validate($request, [

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $image = $request->file('image');
        $imagename = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path().'/images/', $imagename);
        $imagee = new MessageImage();
        $imagee->message_id = $new_message->id;
        $imagee->imageuri=$imagename;
        $imagee->save();

		return response()->json([
            'message' => 'Added successfully',
        ], 201);
    }
}
