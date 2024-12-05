<?php

namespace Forge\Auth\Contracts;

interface AuthInterface
{
    public function handleLogin($email, $password);
    public function handleRegistration($email, $password, $confpassword, $params = []);
    public function handleForgotPassword($email);
    public function handleResetPassword($hash, $newPassword);
    public function handleLogout($userId);


}