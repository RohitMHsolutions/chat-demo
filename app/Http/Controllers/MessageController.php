<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use DB;
use Auth;

class MessageController extends Controller
{
    //
    public function send(Request $request){

        $user_id = Auth::id();
        $data = $request->all();

        $message = new Message();
        $message->sender_id = $user_id;
        $message->recipient_id  = $data['recipient_id'];
        $message->text = $data['text'];
        $message->save();
        return $message;
    }


    public function load($sender_id){

        $user_id = Auth::id();
        $messages = Message::where(function ($query) use($sender_id, $user_id) {
            $query->where('sender_id', $sender_id)->where('recipient_id', $user_id);
            $query->orwhere('sender_id', $user_id)->where('recipient_id', $sender_id);
        })
       
        ->orderBy('id')
        ->get();
        return $messages;
    }

    public function readMessage($sender_id){
        Message::where(function ($query) use($sender_id) {
            $query->where('sender_id', $sender_id)->where('recipient_id', Auth::id());
        })->update(['status' =>'read']);
    }


    public function check(Request $request){

         $user_id = Auth::id();
        $messages = Message::select(DB::raw('count(id) as msg_count'), 'sender_id')
            ->where('status', 'unread')
            ->where('recipient_id', $user_id)
            ->groupBy('sender_id')
            ->get();
       
        return ['updates' => $messages];
    }

    public function delete($message_id){
        $messages = Message::where('id', $message_id)->delete();
        return 'true';
    }
}
