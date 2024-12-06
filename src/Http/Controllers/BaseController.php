<?php

namespace Forge\Http\Controllers;

use Forge\Auth\Session;
use Forge\Support\LinkExtension;
use Forge\Support\PayPalExtension;
use Forge\Support\UserExtension;
use Forge\Support\CsrfExtension;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Models\User;
use Forge\Http\Http;

class BaseController extends Http
{
    protected Request $request;
    protected Environment $twig;

    public function __construct()
    {
        $this->initializeTwig();
    }

    /**
     * Initialisation du moteur Twig.
     */
    private function initializeTwig(): void
    {
        $clientId = $_ENV['PAYPAL_CLIENT_ID'] ?? '';

        $loader = new FilesystemLoader(__DIR__ . '/../../../ressources/views');
        $this->twig = new Environment($loader, [
            'cache' => __DIR__ . '/../../../storage/cache/twig',
            'debug' => true,
        ]);

        // Ajouter les extensions Twig nécessaires
        $this->addTwigExtensions($clientId);
    }

    /**
     * Ajoute les extensions Twig nécessaires.
     */
    private function addTwigExtensions(string $clientId): void
    {
        $this->twig->addExtension(new UserExtension());
        $this->twig->addExtension(new CsrfExtension());
        $this->twig->addExtension(new PayPalExtension($clientId));
        $this->twig->addExtension(new LinkExtension());
    }

    /**
     * Récupère l'utilisateur actuellement connecté à partir de la session.
     */
    protected function getUser(): ?User
    {
        $userId = Session::get('user_id');
        return $userId ? User::find($userId) : null;
    }

    /**
     * Envoie une réponse d'erreur avec un code d'état et un message.
     */
    public static function errorResponse(int $statusCode, string $message): void
    {
        self::response($statusCode, ['error' => $message]);
    }

    /**
     * Charge une vue avec les données fournies.
     */
    protected function view(Request $request, string $view, array $data = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            Session::start();
        }

        $data['user'] = $this->getUser();
        $data['APP_LANG'] = $_ENV['APP_LANG'] ?? 'en_US';
        $data['request'] = $request;

        $viewPath = str_replace('.', '/', $view);

        // Vérification si la vue existe avant de la charger
        $fullPath = __DIR__ . "/../../../ressources/views/{$viewPath}.twig";
        if (!file_exists($fullPath)) {
            self::errorResponse(404, "La vue {$view} est introuvable.");
            return;
        }

        echo $this->twig->render("{$viewPath}.twig", $data);
    }
}
