<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Auth;

class PlayController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){
        $user = User::find(Auth::user()->id);
        $tiles = $user->board;
        return view('play', ['user' => $user, 'tiles' => $tiles]);
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function control($id){
        if(Auth::user()->units != 0) {
            if(Board::where('user_id', Auth::user()->id)
                ->where('tile', $id)
                ->where('owns', 1)
                ->increment('army'))
            User::where('id', Auth::user()->id)
                ->decrement('units');
            return redirect('/play');
        }
        return redirect('/play');
    }
}
