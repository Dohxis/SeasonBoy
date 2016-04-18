<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Board extends Model
{
    public function getColor(){

        if($this->owns == 3)
            return "#C44D58";

        else if($this->owns == 1)
            return "#519548";

        else if($this->owns == 2)
            return "#E58D1E";

        else if($this->owns == 4)
            return "#EE27A8";

        else return "#556270";

    }

    public static function getArmies($id){
        $tile = Board::where('user_id', Auth::user()->id)
            ->where('tile', $id)->first();
        return $tile['army'];
    }

    public static function isMine($id){
        $tile = Board::where('user_id', Auth::user()->id)
            ->where('tile', $id)->first();
        return $tile['owns'] == 1;
    }

    public static function getEnemiesTiles(){
        return Board::where('user_id', Auth::user()->id)->where('owns', 3)->get();
    }
}
