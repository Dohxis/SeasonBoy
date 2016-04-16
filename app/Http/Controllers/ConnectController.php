<?php

namespace App\Http\Controllers;

use App\Board;
use Auth;
use Hash;
use Illuminate\Http\Request;
use App\User;

class ConnectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    public function index()
    {
        return view('welcome');
    }

    public function handle(Request $request)
    {
        if (Auth::attempt(['name' => $request->username, 'password' => $request->password])) {
            return redirect('/play');
        } else {
            if (User::where('name', $request->username)->count() != 1) {
                $user = new User;
                $user->name = $request->username;
                $user->email = $request->username . '@seasonboy.com';
                $user->password = Hash::make($request->password);
                $user->save();
                
                for($i = 0; $i < 100; $i++){
                    $tile = new Board;
                    $tile->user_id = $user->id;
                    $tile->tile = $i;
                    if($i == 90) {
                        $tile->owns = 1;
                        $tile->army = 3;
                    } else if($i == 8 or $i == 9 or $i == 19) {
                        $tile->owns = 3;
                        if($i != 9) $tile->army = 3;
                    } else
                        $tile->owns = 0;
                    $tile->save();
                }
                
                Auth::attempt(['name' => $request->username, 'password' => $request->password]);
                return redirect('/play');
            } else {
                \Session::flash('error_login', 'error');
                return redirect('/');
            }
        }
    }
}