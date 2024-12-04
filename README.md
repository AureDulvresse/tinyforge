# TinyForge Documentation

TinyForge est un framework minimaliste en PHP conçu pour créer rapidement des applications web comme des e-commerces, des blogs, des systèmes e-learning, et bien plus encore. Son objectif est de fournir une solution modulaire, légère et extensible pour répondre aux besoins spécifiques des développeurs.


---

## Installation

Prérequis

Avant d'installer TinyForge, assurez-vous d'avoir les outils suivants installés sur votre machine :

PHP : Version 7.4 ou supérieure.

Composer : Gestionnaire de dépendances PHP (version 2.0 ou supérieure).

Une base de données : MySQL, MariaDB ou PostgreSQL (configurable).

Serveur Web : Apache, Nginx ou serveur intégré PHP pour le développement.



---

## Étapes d'installation

1. Installez TinyForge avec Composer :
Ouvrez un terminal et exécutez la commande suivante :

``bash
composer create-project tinyforge/tinyforge nom-du-projet``

2. Accédez au répertoire du projet :

cd nom-du-projet


3. Configurez votre fichier .env :
TinyForge utilise un fichier .env pour gérer les variables d'environnement. Renommez le fichier .env.example en .env et modifiez-le en fonction de votre environnement :

cp .env.example .env

Exemple de contenu à adapter :

APP_NAME=TinyForge
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_base
DB_USERNAME=utilisateur
DB_PASSWORD=motdepasse


4. Générez une clé d'application unique :
TinyForge nécessite une clé unique pour sécuriser certaines fonctionnalités.

php forge key:generate


5. Configurez la base de données et exécutez les migrations :
Assurez-vous que votre base de données est configurée et accessible, puis lancez les migrations pour créer les tables nécessaires :

php forge migrate


6. Démarrez le serveur de développement intégré :
Une fois tout configuré, vous pouvez lancer le serveur local avec :

php forge serve

Votre application sera accessible à http://localhost:8000.




---

## Structure du Projet

Voici un aperçu de la structure par défaut de TinyForge :

nom-du-projet/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── css/
│   ├── js/
│   └── index.php
├── routes/
│   └── web.php
├── storage/
├── tests/
├── .env
├── composer.json
└── forge (CLI)

Dossiers principaux :

app/ : Contient les contrôleurs, modèles, et vues de l'application.

config/ : Regroupe tous les fichiers de configuration.

database/ : Gère les migrations, seeders et factories.

routes/ : Définit les routes de l'application.

public/ : Fichiers accessibles publiquement (CSS, JS, index.php).

storage/ : Fichiers de logs, caches, et uploads temporaires.



---

Commandes CLI

TinyForge propose une interface CLI simplifiée via le fichier forge. Voici quelques commandes utiles :

Clé d'application

Générer une nouvelle clé d'application :

php forge key:generate


Migration

Exécuter les migrations :

php forge migrate

Annuler la dernière migration :

php forge migrate:rollback


Serveur de développement

Lancer le serveur local :

php forge serve


Autres commandes

Pour voir toutes les commandes disponibles :

php forge list


---

Développement

Routes

Les routes de l'application sont définies dans le fichier routes/web.php. Voici un exemple simple :

use Core\Routing\Route;

Route::get('/', function () {
    return 'Bienvenue sur TinyForge !';
});

Route::post('/products', [ProductController, 'submit']);

Contrôleurs

Les contrôleurs sont stockés dans le dossier app/Controllers. Exemple de création :

namespace App\Controllers;

use tinyforge\http\controller;
use App\Model\Product;

class ProductController extends Controller
{
    public function index(request)
    {
        $data = Product::all()
        return $this->view(request, 'product.index', $data);
    }
}

Modèles

Les modèles représentent les tables de la base de données et sont situés dans app/Models. Exemple :

namespace App\Models;

use TinyForge\Iron\Database\Model;

class Product extends Model
{
    protected $table = 'products';
}


---

Contributions

Soumettre des modifications

Si vous souhaitez contribuer à TinyForge, suivez ces étapes :

1. Forker le projet : Forkez le dépôt officiel sur GitHub.


2. Créer une branche : Travaillez sur une nouvelle branche pour vos modifications.

git checkout -b ma-branche


3. Proposer une Pull Request : Soumettez votre branche pour examen.




---

Ressources

Dépôt GitHub : https://github.com/tinyforge/tinyforge

Issues : Utilisez la section Issues sur GitHub pour signaler des bugs ou demander des fonctionnalités.


