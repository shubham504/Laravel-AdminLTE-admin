<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Permission;
use Cache;
use App\Models\Config;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    
    use Notifiable;

    use HasFactory;

    use Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function canImpersonate(): bool
    {
        return $this->can('root-dev', '');
    }
    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission(Permission $permission)
    {
        return $this->hasAnyRoles($permission->roles);
    }
    
    public function hasAnyRoles($roles)
    {
        if(is_array($roles) || is_object($roles) ) {
            return !! $roles->intersect($this->roles)->count();
        }
        
        return $this->roles->contains('name', $roles);
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

}