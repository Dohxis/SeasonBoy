<?php

namespace App\Http\Controllers;

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
}
