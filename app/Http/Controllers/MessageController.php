<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use DB;
use Auth;

class MessageController extends Controller
{
    //
    public function send(Request $request){
        $user_id = 1;
        $data = $request->all();
        $message = new Message();
        $message->sender_id = $user_id;
        $message->recipient_id  = $request->recipient_id;
        $message->text = $request->text;
        $message->save();
        return $message;
    }


    public function load($sender_id){
        $user_id = 1;
        $messages = Message::where(function ($query) use($sender_id, $user_id) {
            $query->where('sender_id', $sender_id)->where('recipient_id', $user_id);
            $query->orwhere('sender_id', $user_id)->where('recipient_id', $sender_id);
        })
       
        ->orderBy('id')
        ->get();
        
        return $messages;
    }

    public function readMessage($sender_id){
        $message = Message::where('sender_id', $sender_id)->where('recipient_id', 2)->first();
       

        $message->status = "read";
        $message->save();
    }

    public function delete($message_id){
        $messages = Message::where('id', $message_id)->delete();
        return 'true';
    }
}
