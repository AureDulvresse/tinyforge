<?php

namespace Forge\Auth;

use Forge\Auth\Contracts\AuthInterface;
use Forge\Database\Database;
use Forge\Http\Controllers\BaseController;
use Forge\Http\Request;
use PHPAuth\Auth;
use PHPAuth\Config;

abstract class Authentification extends BaseController implements AuthInterface
{
    protected Auth $auth;

    public function __construct()
    {
        $dbh = Database::getInstance()->getConnection();
        $config = new Config($dbh, null, '', 'fr_FR');

        // Tu peux aussi passer des options supplémentaires
        $options = [
            "site_name" => $_ENV['APP_NAME'],
            "site_email" => $_ENV['MAIL_FROM_ADDRESS'],
            "min_password_length" => 8,
            "bcrypt_cost" => 10,
            "cookie_name" => "{$_ENV['APP_NAME']}_COOKIE",
            "cookie_forget" => "+1 hours",
            "cookie_remember" => "+1 month",
            "email_activation" => $_ENV['MAIL_ACTIVATION']
        ];

        foreach ($options as $key => $value) {
            $config->override($key, $value);
        }

        $this->auth = new Auth($dbh, $config);
    }

    abstract public function login(Request $request);
    abstract public function register(Request $request);
    abstract public function forgotPassword(Request $request);
    abstract public function resetPassword(Request $request, $hash);
    abstract public function logout(Request $request);

    /**
     * Méthode pour gérer la connexion
     */
    public function handleLogin($email, $password)
    {
        return $this->auth->login($email, $password);
    }

    /**
     * Méthode pour gérer l'inscription
     */
    public function handleRegistration($email, $password, $confpassword, $params = [])
    {
        return $this->auth->register($email, $password, $confpassword, $params);
    }

    /**
     * Méthode pour activer le compte avec un hash
     */
    protected function activateAccount($hash)
    {
        return $this->auth->activate($hash);
    }

    /**
     * Méthode pour gérer le mot de passe oublié
     */
    public function handleForgotPassword($email)
    {
        return $this->auth->requestReset($email);
    }

    /**
     * Méthode pour réinitialiser le mot de passe
     */
    public function handleResetPassword($hash, $newPassword)
    {
        return $this->auth->resetPass($hash, $newPassword, $newPassword);
    }

    /**
     * Méthode pour gérer la déconnexion
     */
    public function handleLogout($userId)
    {
        $this->auth->logout($userId);
    }

    public function is_authenticated ()
    {
        return $this->auth->isAuthenticated;
    }
}
