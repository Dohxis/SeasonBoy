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
                $bg = "background: linear-gradient(283deg, #B7D7EB 0%, #6D9BC3 100%)";
                break;
            case "Summer":
                $bg = "background: linear-gradient(283deg, #f3543f 0%, #e2a240 100%)";
                break;
            case "Autumn":
                $bg = "background: linear-gradient(283deg, #ffd4c7 0%, #803E2B 100%)";
                break;
            case "Spring":
                $bg = "background: linear-gradient(283deg, #d9ffd1 0%, #49806B 100%)";
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
}
