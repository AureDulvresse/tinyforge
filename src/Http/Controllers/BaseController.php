<?php

namespace Forge\Http\Controllers;

use Forge\Auth\Session;
use Forge\Support\LinkExtension;
use Forge\Support\PayPalExtension;
use Forge\Support\UserExtension;
use Forge\Support\CsrfExtension;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use App\Models\User;
use Forge\Http\Http;

class BaseController extends Http
{
    protected $request;
    protected $form;
    protected Environment $twig;

    public function __construct()
    {
        $this->initializeTwig();
        $this->initializeTranslator();
    }

    /**
     * Initialisation du moteur Twig.
     */
    private function initializeTwig(): void
    {
        $clientId = $_ENV['PAYPAL_CLIENT_ID'] ?? '';

        $loader = new FilesystemLoader(__DIR__ . '/../../../ressources/views');
        $this->twig = new Environment($loader, [
            'cache' => __DIR__ . '/../../../cache/twig',
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
     * Initialisation du traducteur.
     */
    private function initializeTranslator(): void
    {
        $appLang = $_ENV['APP_LANG'] ?? 'en_US';

    }

    /**
     * Méthode générique pour uploader, redimensionner et optimiser une image.
     *
     * @param array $fileData - Données du fichier provenant de $_FILES
     * @param string $targetDir - Répertoire de destination de l'image
     * @return string|bool - Chemin du fichier en cas de succès, ou false en cas d'erreur
     */
    protected function uploadImage(array $fileData, string $targetDir = '/uploads'): bool|string
    {
        // Vérification des erreurs d'upload
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            return "Erreur lors de l'upload : code " . $fileData['error'];
        }

        // Validation du type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($fileData['type'], $allowedTypes)) {
            return "Type de fichier non autorisé.";
        }

        // Définir le chemin de destination du fichier
        $targetPath = __DIR__ . '/../../../public/' . trim($targetDir, '/');
        $this->createDirectoryIfNotExists($targetPath);

        $fileName = uniqid() . '_' . basename($fileData['name']);
        $targetFile = $targetPath . '/' . $fileName;

        // Déplacer le fichier vers le répertoire de destination
        if (move_uploaded_file($fileData['tmp_name'], $targetFile)) {
            return $targetDir . '/' . $fileName;
        }

        return false;
    }

    /**
     * Crée le répertoire de destination si nécessaire.
     */
    private function createDirectoryIfNotExists(string $directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true); // Crée le dossier s'il n'existe pas
        }
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
    protected function view(string $view, array $data = []): void
    {
        // Démarrer la session si elle n'est pas déjà active
        if (session_status() === PHP_SESSION_NONE) {
            Session::start();
        }

        // Ajouter l'utilisateur connecté aux données si disponible
        $data['user'] = $this->getUser();

        // Ajouter la langue par défaut aux données
        $data['APP_LANG'] = $_ENV['APP_LANG'];
        $data['current_path'] = $_SERVER['REQUEST_URI'];

        // Remplacer les points par des barres obliques pour le chemin de la vue
        $viewPath = str_replace('.', '/', $view);

        // Rendu de la vue avec les données fournies
        echo $this->twig->render("{$viewPath}.twig", $data);
    }
}
