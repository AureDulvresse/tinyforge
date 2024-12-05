<?php

namespace Forge\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    protected $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer()
    {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['MAIL_HOST'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['MAIL_USERNAME'];
            $this->mailer->Password = $_ENV['MAIL_PASSWORD'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $_ENV['MAIL_PORT'];
            $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['APP_NAME']);
            $this->mailer->isHTML(true); // Assure l'usage d'HTML par défaut
            $this->mailer->CharSet = 'UTF-8'; // Assure la compatibilité UTF-8
        } catch (Exception $e) {
            throw new \Exception("Erreur de configuration du mailer : {$e->getMessage()}");
        }
    }

    /**
     * Envoie un email.
     *
     * @param string $to L'adresse de destination.
     * @param string $subject Le sujet de l'email.
     * @param string $body Le contenu HTML de l'email.
     * @throws \Exception
     */
    public function sendEmail($to, $subject, $body)
    {
        try {
            $this->mailer->clearAddresses(); // Supprime toutes les adresses précédentes
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            $this->mailer->send();
        } catch (Exception $e) {
            throw new \Exception("Erreur lors de l’envoi de l’email à {$to} : {$e->getMessage()}");
        }
    }

    /**
     * Envoie un email d'activation de compte
     *
     * @param string $email
     * @param string $token
     */
    public static function sendActivationEmail(string $email, string $token): void
    {
        $service = new self(); // Instancie EmailService pour appeler la méthode d'instance
        $subject = "Activation de votre compte";
        $message = "Bonjour, cliquez sur le lien suivant pour activer votre compte : ";
        $message .= "<a href='" . $_SERVER['HTTP_HOST'] . "/activate?token=" . urlencode($token) . "'>Activer mon compte</a>";

        $service->sendEmail($email, $subject, $message);
    }

    /**
     * Envoie un email de réinitialisation de mot de passe
     *
     * @param string $email
     * @param string $token
     */
    public static function sendResetPasswordEmail(string $email, string $token): void
    {
        $service = new self(); // Instancie EmailService pour appeler la méthode d'instance
        $subject = "Réinitialisation de votre mot de passe";
        $message = "Bonjour, cliquez sur le lien suivant pour réinitialiser votre mot de passe : ";
        $message .= "<a href='" . $_SERVER['HTTP_HOST'] . "/reset-password?token=" . urlencode($token) . "'>Réinitialiser mon mot de passe</a>";

        $service->sendEmail($email, $subject, $message);
    }
}
