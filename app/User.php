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
                $bg = "background: linear-gradient(283deg, #878BFF 0%, #081a80 100%)";
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
}
