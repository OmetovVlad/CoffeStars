<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Resources\LogResource;
use App\Http\Resources\PlayerResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class  User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//    ];
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'sur_name',
        'username',
        'language_code',
        'is_premium',
        'card',
        'invited',
        'logs',
        'referals',
        'week_balance_accrual',
        'referrals_tree',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $guarded =[];


    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function invitedUsers()
    {
        return $this->hasMany(User::class, 'invited');
    }

    public function invitedUser()
    {
        return $this->hasMany(User::class, 'invited')->with('invitedUsers');
    }
}
