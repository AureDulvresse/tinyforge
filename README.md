# **🚀 TinyForge Documentation**

**TinyForge** est un framework minimaliste en PHP conçu pour développer rapidement des applications web telles que des e-commerces, des blogs, des systèmes e-learning, et bien plus encore. Il est modulaire, léger et extensible, répondant aux besoins spécifiques des développeurs.

## **🔧 Installation**

### **Prérequis**

Avant d’installer TinyForge, assurez-vous que les outils suivants sont installés sur votre machine :

- **PHP** : Version 7.4 ou supérieure.
- **Composer** : Gestionnaire de dépendances PHP (version 2.0 ou supérieure).
- **Base de données** : MySQL, MariaDB ou PostgreSQL.
- **Serveur web** : Apache, Nginx, ou le serveur intégré PHP pour le développement.

### **Étapes d'installation**

1. **Installer TinyForge avec Composer**  
   Ouvrez un terminal et exécutez :

   ```bash
   composer create-project tinyforge/tinyforge nom-du-projet
   ```

2. **Accéder au répertoire du projet** :

   ```bash
   cd nom-du-projet
   ```

3. **Configurer le fichier `.env`** :  
   Renommez le fichier `.env.example` en `.env` et modifiez-le pour votre environnement :

   ```bash
   cp .env.example .env
   ```

   Exemple de configuration `.env` :

   ```env
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
   ```

4. **Générer une clé d'application** :

   TinyForge utilise une clé unique pour sécuriser certaines fonctionnalités. Générez-la avec :

   ```bash
   php forge key:generate
   ```

5. **Configurer la base de données et exécuter les migrations** :

   Assurez-vous que votre base de données est prête, puis lancez :

   ```bash
   php forge migrate
   ```

6. **Démarrer le serveur local** :

   Une fois configuré, lancez le serveur de développement avec :

   ```bash
   php forge serve
   ```

   Votre application sera accessible à [http://localhost:8000](http://localhost:8000).

## **📂 Structure du Projet**

Voici un aperçu de l’arborescence de TinyForge :

```text
nom-du-projet/
├── app/
│   ├── Controllers/   # Contrôleurs de l'application
│   ├── Forms/         # Forms de l'application
│   ├── Helpers/       # Fonctions de l'application
│   ├── Middlewares/   # Middlewares de l'application
│   ├── Models/        # Modèles de la base de données
│   └── Services/      # Services externes (Stripe, PayPal, etc.)
├── config/            # Fichiers de configuration
├── database/          # Gestion des migrations, seeds et factories
│   ├── factories/     # Factories
│   ├── migrations/    # Migration de la base de donnée
│   └── seeders/       # Seeders
├── public/            # Fichiers accessibles publiquement (index.php, assets)
├── resources/         # Vues, styles, scripts, images, etc.
│   ├── css/           # Dossier de style
│   ├── js/            # Dossier de scripts js
│   ├── images/        # Images
│   ├── views/         # Vues de l'application
│   └── uploads/       # Dossier des fichiers uploadés sur l'application
├── routes/            # Définition des routes
│   └── web.php        # Dossier des fichiers uploadés sur l'application
├── storage/           # Logs, cache, et fichiers temporaires
├── tests/             # Tests unitaires
├── .env               # Configuration de l'environnement
├── .gitignore         # .gitgnore
├── composer.json      # Dépendances PHP
└── forge              # CLI de TinyForge
```

## **Commandes CLI**

TinyForge propose une interface en ligne de commande simplifiée via le fichier `forge`. Voici quelques commandes utiles :

- **Générer une clé d'application** :

  ```bash
  php forge key:generate
  ```

- **Gestion des migrations** :

  - Exécuter les migrations :

    ```bash
    php forge migrate
    ```

  - Annuler la dernière migration :

    ```bash
    php forge migrate:rollback
    ```

- **Démarrer un serveur local** :

  ```bash
  php forge serve
  ```

- **Afficher la liste des commandes disponibles** :

  ```bash
  php forge list
  ```

## **💡 Développement**

### **⚙️ Configuration du Framework**

Les fichiers de configuration se trouvent dans le dossier `/config`. Vous y trouverez les configurations suivantes :

- **app.php** : Paramètres globaux de l'application (clé secrète, environnement, etc.)
- **database.php** : Configuration de la base de données (connexion, type de base, etc.)
- **mail.php** : Paramètres pour le service de mail.
- **services.php** : Configuration des services externes comme Stripe, PayPal.
- **admin.php** : Paramètres de l'interface admin.
- **ember.php** : Paramètres du CMS intégré.

### **📊 Migrations et Base de Données**

Les migrations permettent de versionner la structure de votre base de données.

#### **Créer une Migration**

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

Exécutez les migrations avec la commande suivante :

```bash
php forge db:migrate
```

### **💬 Seeding et Factories**

TinyForge inclut un système de **seeding** pour remplir automatiquement votre base de données avec des données d'exemple.

#### **Utiliser une Factory**

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

Pour générer des données factices :

```php
namespace Database\Seeders;

use Forge\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory(20)->create();
    }
}

```

Ajouter le seeder dans le ```SeederManager.php```

```php

namespace Database\Seeders;

use Forge\Database\Seeder;

class SeederManager extends Seeder
{
    /**
     * Liste des classes de seeders à exécuter.
     *
     * @var array
     */
    protected $seeders = [
        // Ajouter ici vos seeders supplémentaires
        ProductSeeder::class,
    ];

    /**
     * Exécute tous les seeders enregistrés.
     */
    public function run(): void
    {
        foreach ($this->seeders as $seederClass) {
            try {
                $this->logger->info("Running seeder: $seederClass");

                if (!class_exists($seederClass)) {
                    throw new \Exception("Seeder class $seederClass does not exist.");
                }

                $seeder = new $seederClass();

                if (!method_exists($seeder, 'run')) {
                    throw new \Exception("Seeder class $seederClass must implement a `run` method.");
                }

                $seeder->run();

                $this->logger->success("Seeder completed: $seederClass");
            } catch (\Exception $e) {
                $this->logger->error("Error while running seeder $seederClass: " . $e->getMessage());
            }
        }
    }
}
```

Pour lancer le seeding des données :

```bash
php forge db:seed
```

### **📍Définir les Routes**

Les routes sont définies dans le fichier `routes/web.php`. Exemple :

```php
use Forge\Http\Router\Route;

Route::get('/', function () {
    return 'Bienvenue sur TinyForge !';
});

Route::post('/products', [ProductController::class, 'store']);
```

### **Créer des Contrôleurs**

Les contrôleurs sont situés dans `app/Controllers`. Exemple :

```php
namespace App\Controllers;

use Forge\Http\Request;
use Forge\Http\Controllers\BaseController;
use App\Models\Product;

class ProductController extends BaseController
{
    public function index(Request $request)
    {
        $products = Product::all();
        return $this->view($request, 'product.index', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $form = Product::form()->build();
        $data = [
            'title' => "Ajout d'un produit",
            'form' => $this->form->render(),
            'message' => $request->get('message'),
        ];

        if ($request->method() == "POST") {
            $formData = $request->formData();
            if ($form->validate($formData)) {
                $product = new Product($formData);
                $product->save();
                return $this->redirect('/products');
            } else {
                // Retourner les erreurs de validation
                // return $this->response(400, ['errors' => $form->getErrors()]);
                $data['errors'] = $this->form->getErrors();
            }
        }

        return $this->view($request, 'product', $data);
    }

    public function update(Request $request, $id)
    {
        $form = Product::form()->build();
        $data = [
            'title' => "Mise à jour d'un produit",
            'form' => $this->form->render(),
            'message' => $request->get('message'),
        ];

        if ($request->method() == "POST") {
            $formData = $request->formData();
            if ($form->validate($formData)) {
                Product::update($id, $formData);
                return $this->redirect('/products');
            } else {
                // Retourner les erreurs de validation
                // return $this->response(400, ['errors' => $form->getErrors()]);
                $data['errors'] = $this->form->getErrors();
            }
        }

        return $this->view($request, 'product', $data);
    }

    public function destroy(Request $request, $id)
    {
        $products = Product::delete($id);
        return $this->redirect('/products');
    }
}
```

### **Modèles**

Les modèles, représentant les tables de la base de données, se trouvent dans `app/Models`. Exemple :

```php
namespace App\Models;

use Forge\Iron\Database\Model;

class Product extends Model
{
    protected $table = 'products';
}
```

## **Configuration**

Les fichiers de configuration sont regroupés dans le dossier `/config` :

- **`app.php`** : Paramètres globaux de l'application (clé, environnement, URL).
- **`mail.php`** : Configuration des services d'email.
- **`services.php`** : Paramètres des API tierces (Stripe, PayPal).
- **`admin.php`** : Options de l’interface d’administration.
- **`ember.php`** : Configuration du CMS intégré.

## **Contribution**

Vous souhaitez contribuer à TinyForge ? Voici les étapes :

1. **Forker le projet** : Forkez le dépôt GitHub officiel.
2. **Créer une branche** : Travaillez sur une nouvelle fonctionnalité ou correction.

   ```bash
   git checkout -b ma-branche
   ```

3. **Soumettre une Pull Request** : Proposez vos changements pour examen.

---

## **Ressources**

- **Dépôt GitHub** : [TinyForge Repository](https://github.com/tinyforge/tinyforge)
- **Signalement de bugs** : Utilisez la section "Issues" sur GitHub.
- **Documentation officielle** : [Documentation complète](https://tinyforge.dev/docs)
