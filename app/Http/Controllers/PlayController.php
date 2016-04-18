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
        $tips = [
            'It is a good idea to attack neutral tiles in autumn, because Blitzkrieg mode is activated and all neutral tiles turns 0.',
            'In winter enemy cannot attack, but all neutral tiles turns 2, maybe it is a good idea to just stack your army?',
            'You will get +2 armies in summer.',
            'Autumn is the season when our enemy gets stronger and gets +2 armies.',
            'Try to occupy orange tiles to get a bonus.',
            'If you will not win until turn 27, evil tile will spawn in neutral territory, they cannot attack you nor the enemy, but sometimes it helps!',
            'It is a good idea to occupy orange tiles as fast as possible.',
            'You can get achievements throughout the game. To check what you already achieved go to Statistics page.',
            'Maybe they will just surrender?',
            'There is a rumour here in Sezonia, that gods can help you win, making one of your tile over 9999.'
        ];
        $whichTip = mt_rand(0, count($tips) - 1);
        return view('play', ['user' => $user, 'tiles' => $tiles, 'tip' => $tips[$whichTip]]);
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
            if($attacks[$i] == $attacks[$i+1] + 4 or $attacks[$i] == $attacks[$i+1] + 6 or $attacks[$i] == $attacks[$i+1] + 5 or $attacks[$i] == $attacks[$i+1] + 1 or $attacks[$i] == $attacks[$i+1] - 5 or $attacks[$i] == $attacks[$i+1] - 4 or $attacks[$i] == $attacks[$i+1] - 1 or $attacks[$i] == $attacks[$i+1] - 6) {
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
        $deploy = ($newSeason == "Summer") ? 5 : 3;

        if(Board::where('user_id', Auth::user()->id)->where('tile', 0)->where('owns', 1)->count() == 1 or Board::where('user_id', Auth::user()->id)->where('tile', 24)->where('owns', 1)->count()){

            $deploy += Board::where('user_id', Auth::user()->id)->where('tile', 0)->where('owns', 1)->count()*5;
            $deploy += Board::where('user_id', Auth::user()->id)->where('tile', 24)->where('owns', 1)->count()*5;
        }

        if($newSeason == "Winter")
            Board::where('user_id', Auth::user()->id)->where('owns', 0)->update(['army' => 2]);
        else if($newSeason == "Autumn")
            Board::where('user_id', Auth::user()->id)->where('owns', 0)->update(['army' => 0]);
        else
            Board::where('user_id', Auth::user()->id)->where('owns', 0)->update(['army' => 1]);

        if(Auth::user()->Autumn && Auth::user()->Spring && Auth::user()->Summer && Auth::user()->Winter){
            User::where('id', Auth::user()->id)->update(['Autumn' => false, 'Winter' => false, 'Summer' => false, 'Spring' => false]);
            $deploy += 6;

            Board::where('user_id', Auth::user()->id)->where('owns', 3)->where('army', '<', 3)->update(['army' => 3]);

        }

        $dir = [-1, 5, 4, -4, -6, 1];
        shuffle($dir);

        $armiestoGive = 4;
        if($newSeason == "Autumn")
            $armiestoGive = 6;

        if($newSeason == "Spring")
            $armiestoGive = 2;


        if($season != "Winter"){
            $tiles = Board::getEnemiesTiles();
            foreach($tiles as $tile){
                if($tile['army'] > 1){
                    for($i = 0; $i < 6;$i++) {
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
        Board::where('user_id', Auth::user()->id)->where('owns', 3)->where('army', '<', 3)->increment('army');
        $points = User::where('id', Auth::user()->id)->first()->getTiles() * 10;
        User::where('id', Auth::user()->id)->update(['points' => $points]);
        Session::forget('attack');
        if(Auth::user()->turn == 27){
            $neutral = Board::where('user_id', Auth::user()->id)->where('owns', 0)->orderByRaw("RAND()")->first();
            if(Board::where('user_id', Auth::user()->id)->where('owns', 0)->count() > 0) {
                Board::where('user_id', Auth::user()->id)->where('tile', $neutral['tile'])->update(['army' => 99, 'owns' => 4]);
            }
        }
        return redirect('/play');
    }

    public function stats(){
        return view('stats', ['players' => User::orderBy('points', 'desc')->get(), 'user' => Auth::user()]);
    }

    public function tut(){
        User::where('id', Auth::user()->id)->update(['tutorial' => true]);
        return redirect('/play');
    }

    public function endtut(){
        User::where('id', Auth::user()->id)->update(['steps' => true]);
        return redirect('/play');
    }
}
