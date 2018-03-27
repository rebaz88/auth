<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

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

    protected $appends = ['role', 'agent'];

    // The name to be used by activity logger when logging using activity on this model
    const MODEL_ACTIVITY_NAME = 'Users';

    public function getRoleAttribute()
    {
        $roles = $this->getRoleNames();
        return ($roles->isNotEmpty()) ? $roles->first() : '------';
    }

    public function getAgentAttribute()
    {
        $agent = $this->agents()->get();

        return ($agent->isNotEmpty()) ? $agent->first()->name : '------';
    }

    /**
     * The users that work for the agency
     */
    public function agents()
    {
        return $this->belongsToMany('App\Agent');
    }


}