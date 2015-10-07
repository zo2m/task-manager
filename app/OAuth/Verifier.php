<?php
/**
 * Created by PhpStorm.
 * User: alexandrecorrea
 * Date: 10/09/15
 * Time: 21:39
 */

namespace TaskManager\OAuth;

use Auth;


class Verifier
{

    public function verify($username, $password)
    {
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];

        if (Auth::once($credentials)) {
            return Auth::user()->id;
        }

        return false;
    }

}