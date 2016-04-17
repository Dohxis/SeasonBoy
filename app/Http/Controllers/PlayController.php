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
                    }
                }
            }
        }
        $season = Auth::user()->season;
        $newSeason = User::genSeason();
        while($newSeason == $season)
            $newSeason = User::genSeason();
        $deploy = ($newSeason == "Summer") ? 6 : 3;

        $dir = [-1, -10, 10];

        $armiestoGive = 4;
        if($newSeason == "Autumn")
            $armiestoGive = 2;

        if($newSeason == "Spring")
            $armiestoGive = 6;

        for($i = 0; $i < 100; $i++){
            if($armiestoGive > 0) {
                if (Board::where('user_id', Auth::user()->id)->where('tile', $i)->first()['owns'] == 3) {
                    $nowArmies = Board::getArmies($i);
                    Board::where('user_id', Auth::user()->id)->where('tile', $i)->update(['army' => ($nowArmies + 2)]);
                    $armiestoGive -= 2;
                    $i = (($i % 10) + 1) * 10;
                }
            }
        }

        if($season != "Winter"){
            $tiles = Board::getEnemiesTiles();
            foreach($tiles as $tile){
                if($tile['army'] > 1){

                        $randDir = mt_rand(0, 2);
                        if (Board::where('user_id', Auth::user()->id)->where('tile', ($tile['tile'] + $dir[$randDir]))->first()['owns'] != 3) {
                            $tileToAttack = $tile['tile'] + $dir[$randDir];
                            if ((Board::getArmies($tile['tile']) - 1) - Board::getArmies($tileToAttack) >= 1) {
                                $armiesToMove = (Board::getArmies($tile['tile']) - 1) - Board::getArmies($tileToAttack);
                                Board::where('user_id', Auth::user()->id)->where('tile', $tileToAttack)->update(['owns' => 3, 'army' => $armiesToMove]);
                                Board::where('user_id', Auth::user()->id)->where('tile', $tile['tile'])->update(['army' => 1]);
                            }
                        }

                }
            }
        }
        
        User::where('id', Auth::user()->id)->update(['units' => $deploy, 'season' => $newSeason]);
        User::where('id', Auth::user()->id)->increment('turn');
        Session::forget('attack');
        return redirect('/play');
    }
}
