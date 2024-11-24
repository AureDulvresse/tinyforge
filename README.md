# **TinyForge Framework Documentation**

**TinyForge** est un framework PHP minimaliste et performant conçu pour vous permettre de créer rapidement des applications web modernes, des site e-commerce, des blogs. Avec des fonctionnalités prêtes à l'emploi comme un ORM (Iron), un moteur de templates Twig, un CMS intégré, un système de paiement avec Stripe et PayPal, un gestionnaire de mails, et bien plus encore, TinyForge vous offre tout ce dont vous avez besoin pour construire vos projets.

---

## **Sommaire**

1. [🚀 Introduction](#introduction)
2. [📂 Structure du Projet](#structure-du-projet)
3. [🔧 Installation](#installation)
4. [⚙️ Configuration du Framework](#configuration-du-framework)
5. [📊 Migrations et Base de Données](#migrations-et-base-de-données)
6. [💬 Seeding et Factories](#seeding-et-factories)
7. [📍 Gestion des Routes](#gestion-des-routes)
8. [👥 Gestion des Utilisateurs et Authentification](#gestion-des-utilisateurs-et-authentification)
9. [💳 Système de Paiement (Stripe & PayPal)](#système-de-paiement-stripe-paypal)
10. [🖋️ Moteur de Template Twig](#moteur-de-template-twig)
11. [🌍 Système de Traduction](#système-de-traduction)
12. [📜 EmberContent CMS](#ember-content-cms)
13. [ CraftPanel](#craft-panel)
14. [ FormForge](#form-forge)
15. [🔒 Services et Middleware](#services-et-middleware)
16. [💡 Exemple Site E-Commerce](#exemple-site-e-commerce)
17. [🔑 Commandes Utiles](#commandes-utiles)

---

## **🚀 Introduction**

**TinyForge** est un framework PHP minimaliste et modulaire, conçu pour la création d'applications web rapides et évolutives. Avec un design intuitif et une approche simplifiée, il vous permet de vous concentrer sur l'essentiel de votre développement tout en bénéficiant d'une architecture robuste. Que vous créiez une application simple, un CMS ou un site e-commerce complet, TinyForge dispose des outils nécessaires pour vous accompagner.

---

## **📂 Structure du Projet**

Voici l’architecture de répertoires du projet **TinyForge** :

```
/app
    /Models         # Modèles de votre application
    /Controllers    # Contrôleurs de votre application
    /Helpers        # Fonctions utilitaires
    /Services       # Services externes comme Stripe, PayPal, etc.
/config
    # Paramètres de configuration
/database
    /migrations     # Migrations pour la base de données
    /factories      # Factories pour générer des données
    /seeds          # Seeds pour peupler la base de données
/resources
    /lang           # Traductions
    /mail           # Templates de mails
    /css            # Styles CSS
    /js             # Scripts JavaScript
    /views          # Vues de l'application
    /images         # Images
    /uploads        # Fichiers téléchargés
/routes
    # Routes de l'application (fichier web.php)
/public
    # Fichiers accessibles publiquement (index.php, assets)
```

---

## **🔧 Installation**

### Prérequis

- PHP 7.4 ou supérieur
- Composer pour gérer les dépendances

### Étapes d'installation

1. Clonez le projet depuis le repository Git :

   ```bash
   git clone https://votre-repository/tinyforge.git
   cd tinyforge
   ```

2. Installez les dépendances via Composer :

   ```bash
   composer install
   ```

3. Configurez les paramètres de votre application, notamment la base de données et les services externes. Assurez-vous de modifier le fichier `.env` avec vos informations.

4. Générez la clé de l'application pour la sécurité des sessions :

   ```bash
   php forge key:generate
   ```

5. Exécutez les migrations pour préparer votre base de données :

   ```bash
   php forge migrate
   ```

6. Lancez le serveur de développement :

   ```bash
   php forge serve
   ```

Vous pouvez maintenant accéder à votre application à l'adresse `http://localhost:8000`.

---

## **⚙️ Configuration du Framework**

Les fichiers de configuration se trouvent dans le dossier `/config`. Vous y trouverez les configurations suivantes :

- **app.php** : Paramètres globaux de l'application (clé secrète, environnement, etc.)
- **database.php** : Configuration de la base de données (connexion, type de base, etc.)
- **mail.php** : Paramètres pour le service de mail.
- **services.php** : Configuration des services externes comme Stripe, PayPal.
- **admin.php** : Paramètres de l'interface admin.
- **ember.php** : Paramètres du CMS intégré.

---

## **📊 Migrations et Base de Données**

Les migrations permettent de versionner la structure de votre base de données.

### Créer une Migration

Exemple pour créer une table `products` :

```php
<?php

use Forge\Database\Migration;
use Forge\Database\Iron\Blueprint;
use Forge\Database\Iron\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->double('price', 8);
            $table->string('image_url');
            $table->timestamps();
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### Appliquer les Migrations

Exécutez les migrations avec la commande suivante :

```bash
php forge migrate:run
```

---

## **💬 Seeding et Factories**

TinyForge inclut un système de **seeding** pour remplir automatiquement votre base de données avec des données d'exemple.

### Exemple de Factory

```php
namespace Database\Factories;

use App\Models\Product;
use Faker\Generator as Faker;

class ProductFactory
{
    public function definition(Faker $faker)
    {
        return [
            'name' => $faker->word,
            'price' => $faker->randomFloat(2, 10, 100),
            'created_at' => $faker->dateTimeThisYear,
            'updated_at' => $faker->dateTimeThisYear,
        ];
    }
}
```

### Utiliser une Factory

Pour générer des données factices :

```php
Product::factory(10)->create();
```

Pour lancer le seeding des données :

```bash
php forge db:seed
```

---

## **📍 Gestion des Routes**

Les routes sont définies dans le fichier `/routes/web.php`. Vous pouvez créer des routes pour différents contrôleurs et actions.

### Exemple de Route

```php
use Forge\Http\Router\Route;
use App\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
```

---

## **👥 Gestion des Utilisateurs et Authentification**

TinyForge inclut un système d'authentification simple. Vous pouvez initialiser l'authentification en utilisant la commande suivante :

```bash
php forge init:auth
```

Cela configure les routes, contrôleurs et modèles nécessaires pour la gestion des utilisateurs.

### Contrôleur d'Authentification

```php
namespace App\Controllers;

use Forge\Auth\Authentification;
use Forge\Auth\Session;
use Forge\Http\Request;

class AuthController extends Authentification
{
    /**
     * Logique de connexion
     */
    public function login(Request $request)
    {

        $this->form->addField('email', 'email', 'Email', ['required', 'email'], "Entrer votre addresse electronique")
            ->addField('password', 'password', 'Mot de passe', ['required', 'password'], "Entrer votre mot de passe");

        $data = [
            'title' => "Connexion",
            'form' => $this->form->render(),
            'message' => $request->get('message'),
        ];

        if ($request->method() == "POST") {
            $formData = $request->formData();
            if ($this->form->validate($formData)) {
                $email = $formData['email'];
                $password = $formData['password'];
                $loginResult = $this->handleLogin($email, $password);

                if (!$loginResult['error']) {
                    $userId = $this->auth->getUID($email);
                    // Connexion réussie : stocker les informations nécessaires

                    Session::set('user_id', $userId); // Stocker l'ID utilisateur
                    setcookie(
                        $loginResult['cookie_name'],
                        $loginResult['hash'],
                        $loginResult['expire'],
                        '/',
                        '', // Optionnel : domaine
                        true, // Indique si le cookie doit être transmis uniquement via HTTPS
                        true  // Indique si le cookie est accessible uniquement via HTTP (non accessible en JavaScript)
                    );
                    return $this->redirect('/');
                }

                // Gestion de l'échec de la connexion
                return $this->response(400, ['error' => $loginResult['message']]);
            } else {
                // Retourner les erreurs de validation
                // return $this->response(400, ['errors' => $form->getErrors()]);
                $data['errors'] = $this->form->getErrors();
            }
        }

        return $this->view('auth/login', $data);
    }

    /**
     * Logique d'inscription avec envoi d'un email d'activation
     */
    public function register(Request $request)
    {

        $this->form->addField('name', 'text', 'Nom', ['required'])
            ->addField('email', 'email', 'Email', ['required', 'email'])
            ->addField('password', 'password', 'Mot de passe', ['required', 'password'])
            ->addField('confpassword', 'password', 'Confirmer le mot de passe', ['required']);

        $data = [
            "title" => "Inscription",
            'form' => $this->form->render(),
        ];

        if ($request->method() == "POST") {
            $formData = $request->formData();
            if ($this->form->validate($formData)) {
                $email = $formData['email'];
                $password = $formData['password'];
                $name = $formData['name'];
                $confpassword = $formData['confpassword'];

                // Vérification de la confirmation de mot de passe
                if ($confpassword !== $password) {
                    return $this->response(400, ['error' => 'Le mot de passe et la confirmation ne sont pas les mêmes']);
                }

                $registerResult = $this->handleRegistration($email, $password, $confpassword, ['name' => $name]);

                if (!$registerResult['error']) {
                    return $this->redirect('/login', ['message' => 'Enregistrement réussi. Vérifiez votre email pour activer votre compte.']);
                }

                // return $this->response(400, ['error' => $registerResult['message']]);
            } else {
                // Retourner les erreurs de validation
                // return $this->response(400, ['errors' => $form->getErrors()]);
                $data['errors'] = $this->form->getErrors();
            }
        }
        return $this->view('auth/register', $data);
    }

    /**
     * Activation du compte utilisateur
     */
    public function activateAccount($hash)
    {
        $activationResult = $this->activateAccount($hash);

        if (!$activationResult['error']) {
            return $this->redirect('/login', ['message' => 'Compte activé avec succès ! Vous pouvez maintenant vous connecter.']);
        }

        return $this->response(400, ['error' => $activationResult['message']]);
    }

    /**
     * Mot de passe oublié
     */
    public function forgotPassword(Request $request)
    {
        $this->form->addField('email', 'email', 'Email', ['required', 'email']);

        $data = [
            "title" => "Réinitialiser mot de passe",
            'form' => $this->form->render(),
        ];

        if ($request->method() == "POST") {
            $formData = $request->formData();
            if ($this->form->validate($formData)) {
                $email = $request->get('email');
                $resetResult = $this->handleForgotPassword($email);

                if (!$resetResult['error']) {
                    return $this->response(200, ['message' => 'Si cet email est associé à un compte, vous recevrez un lien de réinitialisation.']);
                }

                return $this->response(400, ['error' => $resetResult['message']]);
            } else {
                // Retourner les erreurs de validation
                // return $this->response(400, ['errors' => $form->getErrors()]);
                $data['errors'] = $this->form->getErrors();
            }
        }

        return $this->view('auth/forgot_password', $data);
    }

    /**
     * Réinitialisation du mot de passe
     */
    public function resetPassword(Request $request, $hash)
    {
        $this->form->addField('password', 'password', 'Nouveau mot de passe', ['required', 'password'])
            ->addField('confpassword', 'password', 'Confirmer le mot de passe', ['required']);

        $data = [
            "title" => "Réinitialiser mot de passe",
            'form' => $this->form->render(),
        ];

        if ($request->method() == "POST") {
            $formData = $request->formData();
            if ($this->form->validate($formData)) {
            $newPassword = $request->get('password');
            $confpassword = $request->get('confpassword');

            // Vérification de la confirmation de mot de passe
            if ($confpassword !== $newPassword) {
                return $this->response(400, ['error' => 'Le mot de passe et la confirmation ne sont pas les mêmes']);
            }

            $resetResult = $this->handleResetPassword($hash, $newPassword);

            if (!$resetResult['error']) {
                return $this->redirect('/login', ['message' => 'Votre mot de passe a été réinitialisé avec succès']);
            }

            return $this->response(400, ['error' => $resetResult['message']]);
            } else {
                // Retourner les erreurs de validation
                // return $this->response(400, ['errors' => $form->getErrors()]);
                $data['errors'] = $this->form->getErrors();
            }
        }

        $data['token'] = $hash;

        return $this->view('auth/reset_password', $data);
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout(Request $request)
    {
        if (isset($_SESSION['user_id'])) {
            $userId = Session::get('user_id');
            $this->handleLogout($userId);
            session_destroy(); // Détruire la session
        }

        return $this->redirect('/login', ['message' => 'Vous avez été déconnecté avec succès.']);
    }
}

```

### Route d'Authentication
```php
// routes/web.php
use Forge\Http\Router\Route;
use App\Controllers\AuthController;

// Routes pour l'authentification
Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']); // Traite la connexion
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'register']); // Traite l'inscription
Route::get('/logout', [AuthController::class, 'logout'], "logout", AuthMiddleware::class); // Déconnecte l'utilisateur

// Routes pour la gestion des tokens
Route::post('/auth/refresh', [AuthController::class, 'refreshToken']); // Rafraîchir le token d'authentification
Route::get('/activate/{token}', [AuthController::class, 'activateAccount']); // Activer le compte via token
Route::get('/forgot-password', [AuthController::class, 'forgotPassword']); // Affiche le formulaire de réinitialisation
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']); // Traite la demande de réinitialisation

```

---

## **💳 Système de Paiement (Stripe & PayPal)**

### Stripe

TinyForge facilite l'intégration avec Stripe. Configurez votre clé API dans `/config/services.php` au niveau de la clé `stripe` et suivez la documentation officielle de Stripe pour implémenter des paiements en ligne.

### PayPal

La configuration de PayPal se fait dans `/config/services.php`  au niveau de la clé `paypal`. Vous pouvez l'utiliser pour accepter des paiements en ligne de manière sécurisée.

---

## **🖋️ Moteur de Template Twig**

TinyForge utilise **Twig** comme moteur de template. Voici un exemple d'un fichier Twig simple :

```twig
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ title }}</title>
</head>
<body>
    <h1>{{ title }}</h1>
    {% block content %}{% endblock %}
</body>
</html>
```

Dans le contrôleur, vous pouvez rendre la vue ainsi :

```php
namespace App\Controllers;

use Forge\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        $data = [
            "title" => "Bienvenue sur TinyForge 🚀",
        ];

        $this->view('index', $data);
    }
}
```

---

## **🌍 Système de Traduction**

TinyForge inclut un système de traduction basé sur des fichiers PHP situés dans `/resources/lang/`.

### Exemple de Traduction

```php
// resources/lang/en/messages.php
return [
    'welcome' => 'Welcome to TinyForge!',
];
```

Pour utiliser la traduction :

```php
echo __('messages.welcome');
```

---

## **📜 Ember CMS Intégré**

TinyForge inclut un CMS intégré qui vous permet de gérer facilement vos contenus (articles, pages, etc.). Le CMS `Ember` est configurable et peut être étendu selon vos besoins.

---

## **🔒 Services et Middleware**

TinyForge permet d'ajouter des **services** et des **middlewares** personnalisés

. Ceux-ci peuvent être définis dans `/app/Services` et `/app/Middleware` respectivement.

### Exemple de Middleware

```php
namespace App\Middleware;

class Authenticate
{
    public function handle($request, $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
```

---

## **💡 Exemple Site E-Commerce**

### Étapes de Création

1. **Création des Modèles** : Définissez des modèles comme `Product`, `Order`, `Customer`, etc.
2. **Migrations** : Créez les tables associées à vos modèles.
3. **Routes et Contrôleurs** : Définissez des routes pour afficher les produits, gérer les paniers, et traiter les paiements.
4. **Système de Paiement** : Intégrez Stripe ou PayPal pour accepter les paiements en ligne.
5. **Templates** : Utilisez Twig pour rendre vos vues.

---

## **🔑 Commandes Utiles**

- `php forge serve` : Lance le serveur local.
- `php forge migrate` : Applique les migrations.
- `php forge key:generate` : Générez une clé secrète.
- `php forge init:auth` : Initialise le système d'authentification.

---

**TinyForge** est un framework puissant et léger pour vos projets web. Avec une architecture simple, une extensibilité facile, et une multitude de fonctionnalités intégrées, il vous permet de vous concentrer sur l'essentiel : créer des applications web performantes et évolutives.
