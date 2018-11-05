<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

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

    /**
     * Attritbutes
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Methods
     */
    protected function profileUpdate($request, $id)
    {
        $user = $this->find($id);

        if ($user) {
            $arrayName = explode(" ", $request->get('full_name'));

            if (count($arrayName) > 3) {
                $user->first_name = $arrayName[0].' '.$arrayName[1];
                $user->last_name = $arrayName[2].' '.$arrayName[3];
            } elseif (count($arrayName) > 2) {
                $user->first_name = $arrayName[0];
                $user->last_name = $arrayName[1].' '.$arrayName[2];
            } elseif (count($arrayName) > 1) {
                $user->first_name = $arrayName[0];
                $user->last_name = $arrayName[1];                
            } else {
                $user->first_name = $arrayName[0];
            }

            if ($request->get('password')) {
                $user->password = bcrypt($request->get('password'));
            }

            $user->save();
        }

        return $user;
    }

    protected function checkEmail($email) {
        return $this->where('email', $email)
            ->first();
    }
}
