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

        $nextTile = Board::
        where('tile', $this->tile - 1)
            ->where('user_id', Auth::user()->id)->first();
        if($nextTile['owns'] == 1)
            return "#D9A750";

        $nextTile = Board::
        where('tile', $this->tile + 10)
            ->where('user_id', Auth::user()->id)->first();
        if($nextTile['owns'] == 1)
            return "#D9A750";

        return "#556270";

    }
}
