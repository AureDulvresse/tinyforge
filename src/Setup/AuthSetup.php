<?php

namespace Forge\Setup;

class AuthSetup
{
    protected string $modelFile;
    protected string $migrationFile;
    protected string $controllerFile;
    protected string $routeFilePath;
    protected $logger;

    public function __construct($logger)
    {
        $this->modelFile = __DIR__ . "/../../app/Models/User.php";
        $this->migrationFile = __DIR__ . "/../../database/migrations/" . date('Y_m_d_His') . "_create_auth_tables.php";
        $this->controllerFile = __DIR__ . "/../../app/Controllers/AuthController.php";
        $this->routeFilePath = __DIR__ . '/../../routes/web.php';
        $this->logger = $logger;
    }

    public function init()
    {
        $this->createModel();
        $this->createMigration();
        $this->createController();
        $this->addRoutes();
    }

    protected function createModel()
    {
        $modelContent = <<<PHP
        <?php

            namespace App\Models;

            use Forge\Database\Iron\Model;

            class User extends Model
            {
                protected static \$table = 'users';

                // Déclaration des attributs correspondant aux colonnes de la table 'users'
                // Exemple d'attributs
                // protected \$name;
                // protected \$email;
                // protected \$password;
                // protected \$is_admin;
                // protected \$isactive;
            }
        PHP;

        file_put_contents($this->modelFile, $modelContent);
    }

    protected function createMigration()
    {
        $migrationContent =
        <<<PHP
        <?php
            
            use Forge\Database\Migration;
            use Forge\Database\Iron\Blueprint;
            use Forge\Database\Iron\Schema;
            
            return new class extends Migration
            {
                public function up()    
                {
                    // Config table
                    Schema::create('phpauth_config', function (Blueprint \$table) {
                        \$table->string('setting', 100)->unique();
                        \$table->string('value', 100)->nullable();
                    });

                    // Attempts table
                    Schema::create('attempts', function (Blueprint \$table) {
                        \$table->id();
                        \$table->char('ip', 39);
                        \$table->datetime('expiredate');
                        \$table->index(['ip']);
                    });

                    // Requests table
                    Schema::create('requests', function (Blueprint \$table) {
                        \$table->id();
                        \$table->integer('uid')->unsigned();
                        \$table->char('token', 20);
                        \$table->datetime('expire');
                        \$table->enum('type', ['activation', 'reset']);
                        \$table->index(['type', 'token', 'uid']);
                    });

                    // Sessions table
                    Schema::create('sessions', function (Blueprint \$table) {
                        \$table->id();
                        \$table->integer('uid')->unsigned();
                        \$table->char('hash', 40);
                        \$table->datetime('expiredate');
                        \$table->string('ip', 39);
                        \$table->uuid('device_id')->nullable();
                        \$table->string('agent', 200);
                        \$table->char('cookie_crc', 40);
                    });

                    // Users table
                    Schema::create('users', function (Blueprint \$table) {
                        \$table->id();
                        \$table->string('email', 100)->unique();
                        \$table->string('name');
                        \$table->string('password', 255);
                        \$table->boolean('is_admin')->default(false);
                        \$table->boolean('isactive')->default(false);
                        \$table->timestamps();
                    });

                    // Banned emails reference
                    Schema::create('emails_banned', function (Blueprint \$table) {
                        \$table->id();
                        \$table->string('domain', 100)->nullable();
                    });

                    Schema::configAuth();

                }
                
                public function down()
                {
                    Schema::dropIfExists('phpauth_config');
                    Schema::dropIfExists('attempts');
                    Schema::dropIfExists('requests');
                    Schema::dropIfExists('sessions');
                    Schema::dropIfExists('users');
                    Schema::dropIfExists('emails_banned');
                }
                
            };
        PHP;

        file_put_contents($this->migrationFile, $migrationContent);
    }

    protected function createController()
    {
        $controllerContent = <<<PHP
        <?php

        namespace App\Controllers;

        use Forge\Auth\Authentification;
        use Forge\Auth\Session;

        class AuthController extends Authentification
        {
            /**
             * Logique de connexion
             */
            public function login()
            {
                \$this->form->addField('email', 'email', 'Email', ['required', 'email'], "Entrer votre addresse electronique")
                    ->addField('password', 'password', 'Mot de passe', ['required', 'password'], "Entrer votre mot de passe");

                \$data = [
                    'title' => "Connexion",
                    'form' => \$this->form->render(),
                    'message' => \$this->request->search('message'),
                ];

                if (\$this->request->isPost()) {
                    \$formData = \$this->request->all();
                    if (\$this->form->validate(\$formData)) {
                        \$email = \$formData['email'];
                        \$password = \$formData['password'];
                        \$loginResult = \$this->handleLogin(\$email, \$password);

                        if (!\$loginResult['error']) {
                            \$userId = \$this->auth->getUID(\$email);
                            // Connexion réussie : stocker les informations nécessaires
                            Session::set('user_id', \$userId); // Stocker l'ID utilisateur
                            setcookie(
                                \$loginResult['cookie_name'],
                                \$loginResult['hash'],
                                \$loginResult['expire'],
                                '/',
                                '', // Optionnel : domaine
                                true, // Indique si le cookie doit être transmis uniquement via HTTPS
                                true  // Indique si le cookie est accessible uniquement via HTTP (non accessible en JavaScript)
                            );
                            return \$this->redirect('/');
                        }

                        // Gestion de l'échec de la connexion
                        return \$this->response(400, ['error' => \$loginResult['message']]);
                    } else {
                        \$data['errors'] = \$this->form->getErrors();
                    }
                }

                return \$this->view('auth/login', \$data);
            }

            /**
             * Logique d'inscription avec envoi d'un email d'activation
             */
            public function register()
            {
                \$this->form->addField('name', 'text', 'Nom', ['required'])
                    ->addField('email', 'email', 'Email', ['required', 'email'])
                    ->addField('password', 'password', 'Mot de passe', ['required', 'password'])
                    ->addField('confpassword', 'password', 'Confirmer le mot de passe', ['required']);

                \$data = [
                    "title" => "Inscription",
                    'form' => \$this->form->render(),
                ];

                if (\$this->request->isPost()) {
                    \$formData = \$this->request->all();
                    if (\$this->form->validate(\$formData)) {
                        \$email = \$formData['email'];
                        \$password = \$formData['password'];
                        \$name = \$formData['name'];
                        \$confpassword = \$formData['confpassword'];

                        // Vérification de la confirmation de mot de passe
                        if (\$confpassword !== \$password) {
                            return \$this->response(400, ['error' => 'Le mot de passe et la confirmation ne sont pas les mêmes']);
                        }

                        \$registerResult = \$this->handleRegistration(\$email, \$password, \$confpassword, ['name' => \$name]);

                        if (!\$registerResult['error']) {
                            return \$this->redirect('/login', ['message' => 'Enregistrement réussi. Vérifiez votre email pour activer votre compte.']);
                        }
                    } else {
                        \$data['errors'] = \$this->form->getErrors();
                    }
                }

                return \$this->view('auth/register', \$data);
            }

            /**
             * Activation du compte utilisateur
             */
            public function activateAccount(\$hash)
            {
                \$activationResult = \$this->activateAccount(\$hash);

                if (!\$activationResult['error']) {
                    return \$this->redirect('/login', ['message' => 'Compte activé avec succès ! Vous pouvez maintenant vous connecter.']);
                }

                return \$this->response(400, ['error' => \$activationResult['message']]);
            }

            /**
             * Mot de passe oublié
             */
            public function forgotPassword()
            {
                \$this->form->addField('email', 'email', 'Email', ['required', 'email']);

                \$data = [
                    "title" => "Réinitialiser mot de passe",
                    'form' => \$this->form->render(),
                ];

                if (\$this->request->isPost()) {
                    \$formData = \$this->request->all();
                    if (\$this->form->validate(\$formData)) {
                        \$email = \$this->request->get('email');
                        \$resetResult = \$this->handleForgotPassword(\$email);

                        if (!\$resetResult['error']) {
                            return \$this->redirect('/login', ['message' => 'Un lien de réinitialisation a été envoyé à votre email.']);
                        }

                        return \$this->response(400, ['error' => \$resetResult['message']]);
                    }
                }

                return \$this->view('auth/forgot_password', \$data);
            }

            /**
             * Réinitialisation du mot de passe
             */
            public function resetPassword(\$hash)
            {
                \$this->form->addField('password', 'password', 'Nouveau mot de passe', ['required', 'password'])
                    ->addField('confpassword', 'password', 'Confirmer le mot de passe', ['required']);

                \$data = [
                    "title" => "Réinitialiser votre mot de passe",
                    'form' => \$this->form->render(),
                ];

                if (\$this->request->isPost()) {
                    \$formData = \$this->request->all();
                    if (\$this->form->validate(\$formData)) {
                        \$password = \$formData['password'];
                        \$confpassword = \$formData['confpassword'];

                        if (\$password !== \$confpassword) {
                            return \$this->response(400, ['error' => 'Les mots de passe ne correspondent pas.']);
                        }

                        \$resetResult = \$this->handleResetPassword(\$hash, \$password);

                        if (!\$resetResult['error']) {
                            return \$this->redirect('/login', ['message' => 'Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.']);
                        }

                        return \$this->response(400, ['error' => \$resetResult['message']]);
                    }
                }

                return \$this->view('auth/reset_password', \$data);
            }

            /**
             * Déconnexion
             */
            public function logout()
            {
                Session::destroy();
                return \$this->redirect('/');
            }
        PHP;

        file_put_contents($this->controllerFile, $controllerContent);
    }
    
    protected function addRoutes()
    {
        $routeFile = file_get_contents($this->routeFilePath);
        $newRoute = <<<PHP
            /* Route généré pour le système d'authentification
            Importer le controller : use App\Controllers\AuthController;
            */

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
            PHP;

        if (strpos($routeFile, '/login') === false) {
            file_put_contents($this->routeFilePath, $routeFile . $newRoute);
        }
    }
}

