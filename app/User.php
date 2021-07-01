<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'avatar', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute($value)
    {
        return asset($value ?: '/images/default-avatar.png');
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function replies()
    {
        return $this->hasMany(Reply::class)->with('tweet')->with('parent')->latest();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_participants')->withTimestamps();
    }
    public function path($append = '')
    {
        $path = route('profile', $this->username);

        return $append ? "{$path}/{$append}" : "{$path}";
    }
     public function roles(){
        return $this->belongsToMany('App\Role');
    }
    public function hasAnyRole($roles)
    {
        if (is_array($roles)){
            foreach ($roles as $role){
                if ($this->hasRole($role)){
                    return true;
                }
            }
        }
            else {
                if($this->hasRole($roles)){
                   return true; 
                }
            }
            return false;
    }
    public function hasRole($role)
    {
        if ($this->roles()->where('name',$role)->first){
            return true;
        }
        return false;
    }    

}
