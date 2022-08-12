<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    //
    public function send(Request $request){
        $user_id = Auth::id();
        $data = $request->all();
        $message = new Message();
        $message->sender_id = $user_id;
        $message->receiver_id  = $request->receiver_id;
        $message->text = $request->text;
        $message->save();
        return $message;
    }


    public function load($sender_id){
        $user_id = Auth::id();
        $messages = Message::where(function ($query) use($request, $user_id) {
            $query->where('sender_id', $sender_id)->where('recipient_id', $user_id);
        })
        ->orWhere(function ($query) use($request, $user_id) {
            $query->where('sender_id', $user_id)->where('recipient_id', $sender_id);
        })
        ->orderBy('id')
        ->get();
        
        return $messages;
    }

    public function readMessage($sender_id){
       
        $query->where('sender_id', $sender_id)->where('receiver_id', Auth::id());
       

        $message->status = "read";
        $message->save();
    }

    public function delete($message_id){
        $messages = Message::where('id', $message_id)->delete();
        return 'true';
    }
}
