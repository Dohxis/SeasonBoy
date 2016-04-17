<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        if(Auth::user()->units > 0) {
            if(Board::where('user_id', Auth::user()->id)
                ->where('tile', $id)
                ->where('owns', 1)
                ->increment('army'))
            User::where('id', Auth::user()->id)
                ->decrement('units');
            return redirect('/play');
        } else {
            Session::push('attack', $id);
            return redirect('/play');
        }
    }

    public function next(){
        $attacks = Session::get('attack');
        $handle = (count($attacks) % 2 == 0) ? count($attacks) : count($attacks) - 1;
        for($i = 0; $i < $handle; $i += 2){
            if($attacks[$i] == $attacks[$i+1] + 10 or $attacks[$i] == $attacks[$i+1] - 1 or $attacks[$i] == $attacks[$i+1] + 1 or $attacks[$i] == $attacks[$i+1] - 10) {
                if (Board::isMine($attacks[$i])) {
                    if ((Board::getArmies($attacks[$i]) - 1) - Board::getArmies($attacks[$i + 1]) >= 1){
                        $armiesToMove = (Board::getArmies($attacks[$i]) - 1) - Board::getArmies($attacks[$i + 1]);
                        Board::where('user_id', Auth::user()->id)->where('tile', $attacks[$i+1])->update(['owns' => 1, 'army' => $armiesToMove]);
                        Board::where('user_id', Auth::user()->id)->where('tile', $attacks[$i])->update(['army' => 1]);
                    } else {
                        $armiesToMove = Board::getArmies($attacks[$i + 1]) - (Board::getArmies($attacks[$i]) - 1);
                        Board::where('user_id', Auth::user()->id)->where('tile', $attacks[$i+1])->update(['army' => $armiesToMove]);
                        Board::where('user_id', Auth::user()->id)->where('tile', $attacks[$i])->update(['army' => 1]);
                    }
                }
            }
        }
        $season = Auth::user()->season;
        $newSeason = User::genSeason();
        while($newSeason == $season)
            $newSeason = User::genSeason();
        $deploy = ($newSeason == "Summer") ? 6 : 3;

        if(Auth::user()->Autumn && Auth::user()->Spring && Auth::user()->Summer && Auth::user()->Winter){
            User::where('id', Auth::user()->id)->update(['Autumn' => false, 'Winter' => false, 'Summer' => false, 'Spring' => false]);
            $deploy += 10;

            Board::where('user_id', Auth::user()->id)->where('owns', 3)->update(['army' => 3]);

        }

        $dir = [-1, -10, 10];
        shuffle($dir);

        $armiestoGive = 6;
        if($newSeason == "Autumn")
            $armiestoGive = 4;

        if($newSeason == "Spring")
            $armiestoGive = 8;


        if($season != "Winter"){
            $tiles = Board::getEnemiesTiles();
            foreach($tiles as $tile){
                if($tile['army'] > 1){
                    for($i = 0; $i < 3;$i++) {
                        if (Board::where('user_id', Auth::user()->id)->where('tile', ($tile['tile'] + $dir[$i]))->first()['owns'] != 3 && $tile['army'] > 2) {
                            $tileToAttack = $tile['tile'] + $dir[$i];
                            if (Board::getArmies($tile['tile']) - Board::getArmies($tileToAttack) > 0) {
                                $buff = ($armiestoGive > 0) ? 2 : 0;
                                $armiesToMove = (Board::getArmies($tile['tile']) - 1) - Board::getArmies($tileToAttack) + $buff;
                                //echo($tileToAttack . ' ' . $tile['tile'] . ' ' . $armiesToMove . ' ' . $dir[$i] . '<br>');
                                Board::where('user_id', Auth::user()->id)->where('tile', $tileToAttack)->update(['owns' => 3, 'army' => $armiesToMove]);
                                Board::where('user_id', Auth::user()->id)->where('tile', $tile['tile'])->update(['army' => 1]);
                                $armiestoGive -= 2;
                            } else {
                                $buff = ($armiestoGive > 0) ? 2 : 0;
                                $armiesToMove = Board::getArmies($tileToAttack) - (Board::getArmies($tile['tile']) - 1);
                                    Board::where('user_id', Auth::user()->id)->where('tile', $tileToAttack)->update(['army' => $armiesToMove]);
                                Board::where('user_id', Auth::user()->id)->where('tile', $tile['tile'])->update(['army' => 1 + $buff]);
                                $armiestoGive -= 2;
                            }
                        }
                    }
                }
            }
        }


        
        User::where('id', Auth::user()->id)->update(['units' => $deploy, 'season' => $newSeason, $newSeason => true]);
        User::where('id', Auth::user()->id)->increment('turn');
        $points = User::where('id', Auth::user()->id)->first()->getTiles() * 10;
        User::where('id', Auth::user()->id)->update(['points' => $points]);
        Session::forget('attack');
        return redirect('/play');
    }

    public function stats(){
        return view('stats', ['players' => User::orderBy('points', 'desc')->get(), 'user' => Auth::user()]);
    }
}
