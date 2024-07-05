<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $guarded =[];
    protected $fillable = [];

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function invitedUsers()
    {
        return $this->hasMany(Player::class, 'invited');
    }

    public function invitedUser()
    {
        return $this->hasMany(Player::class, 'invited')->with('invitedUsers');
    }
}
