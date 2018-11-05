<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    public $timestamps = false; // enable only to created_at

    protected function storeRecord($email, $token)
    {
        $row = $this->checkIfExists($email);

        if (!$row) {
            $row = $this->create([
                'email' => $email,
                'token' => $token,
                'created_at' => \Carbon\Carbon::now(),
            ]);
        }
        return $row;
    }

    protected function checkIfExists($email)
    {
        return $this->where('email', $email)->first();
    }

    protected function burn($email)
    {
        $row = $this->checkIfExists($email);

        if ($row) {
            $this->where('email', $email)->delete();
            return true;
        }
        return false;
    }
}
