<?php

namespace Forge\Forms;

use Forge\Auth\Session;

class Csrf
{
    public static function generateToken()
    {
        if (!Session::exists('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            Session::set('csrf_token', $token);
        }
        return Session::get('csrf_token');
    }

    public static function validateToken($token)
    {
        if (Session::exists('csrf_token') && hash_equals(Session::get('csrf_token'), $token)) {
            return true;
        }
        return false;
    }
}
