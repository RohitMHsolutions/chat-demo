<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function getRecords(Request $request){
        $users = User::where('id', '!=', Auth::id())->get();
        return $users;
    }

}
