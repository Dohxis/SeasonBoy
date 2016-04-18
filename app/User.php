<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function board(){
        return $this->hasMany('App\Board', 'user_id');
    }

    public function getBackground(){
        $bg = "";
        switch ($this->season){
            case "Winter":
                $bg = "background: url(../images/winter.png) no-repeat center center fixed;background-size: cover;";
                break;
            case "Summer":
                $bg = "background: url(../images/summer.png) no-repeat center center fixed;background-size: cover;";
                break;
            case "Autumn":
                $bg = "background: url(../images/autumn.png) no-repeat center center fixed;background-size: cover;";
                break;
            case "Spring":
                $bg = "background: url(../images/spring.png) no-repeat center center fixed;background-size: cover;";
                break;
        }
        return $bg;
    }

    public static function genSeason(){
        $newSeason = mt_rand(0, 3);
        switch ($newSeason){
            case 0:
                return "Winter";
            case 1:
                return "Summer";
            case 2:
                return "Autumn";
            case 3:
                return "Spring";
        }
    }

    public function getTiles(){
        return Board::where('user_id', $this->id)->where('owns', 1)->count();
    }

    public function getEnemyTiles(){
        return Board::where('user_id', $this->id)->where('owns', 3)->count();
    }

    public function getSeason(){
        $season = '<span class="label label-warning">Summer</span>';
        switch($this->season){
            case "Autumn":
                $season = '<span class="label label-danger">Autumn</span>';
                break;
            case "Winter":
                $season = '<span class="label label-primary">Winter</span>';
                break;
            case "Spring":
                $season = '<span class="label label-success">Spring</span>';
                break;
        }
        return $season;
    }


    public static function getSeasonLabel($season){
        switch($season){
            case "Autumn":
                $season = '<span class="label label-danger">Autumn</span>';
                break;
            case "Winter":
                $season = '<span class="label label-primary">Winter</span>';
                break;
            case "Spring":
                $season = '<span class="label label-success">Spring</span>';
                break;
            default:
                $season = '<span class="label label-warning">Summer</span>';
                break;
        }
        return $season;
    }


    public function getTotalArmies(){
        return Board::where('user_id', $this->id)->where('owns', 1)->sum('army');
    }

    public function getTotalEnemyArmies(){
        return Board::where('user_id', $this->id)->where('owns', 3)->sum('army');
    }

    public static function getWonPlayers(){
        $won = 0;
        foreach(User::all() as $user){
            if($user->getEnemyTiles() == 0)
                $won++;
        }
        return $won;
    }

    public static function getLostPlayers(){
        $lost = 0;
        foreach(User::all() as $user){
            if($user->getTiles() == 0)
                $lost++;
        }
        return $lost;
    }

    public static function getArmyPlayers(){
        $army = 0;
        foreach(User::all() as $user){
            $army += $user->getTotalArmies();
        }
        return $army;
    }

    public static function getArmyEnemies(){
        $army = 0;
        foreach(User::all() as $user){
            $army += $user->getTotalEnemyArmies();
        }
        return $army;
    }

    public function getAch(){
        $a = 0;

        if($this->getEnemyTiles() == 0)
            $a++;
        if($this->getTiles() >= 12)
            $a++;
        if($this->getTiles() == 25)
            $a++;
        if($this['turn'] <= 15 && $this->getEnemyTiles() == 0)
            $a++;
        if($this['turn'] <= 10 && $this->getEnemyTiles() == 0)
            $a++;
        if($this->getTotalArmies() > 49)
            $a++;
        if($this->getTotalArmies() > 99)
            $a++;
        return $a;
    }

}
