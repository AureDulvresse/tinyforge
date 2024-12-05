# **TinyForge Documentation**

**TinyForge** est un framework minimaliste en PHP conçu pour développer rapidement des applications web telles que des e-commerces, des blogs, des systèmes e-learning, et bien plus encore. Il est modulaire, léger et extensible, répondant aux besoins spécifiques des développeurs.


## **Installation**

### **Prérequis**

Avant d’installer TinyForge, assurez-vous que les outils suivants sont installés sur votre machine :

- **PHP** : Version 7.4 ou supérieure.
- **Composer** : Gestionnaire de dépendances PHP (version 2.0 ou supérieure).
- **Base de données** : MySQL, MariaDB ou PostgreSQL.
- **Serveur web** : Apache, Nginx, ou le serveur intégré PHP pour le développement.

---

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

---

## **Structure du Projet**

Voici un aperçu de l’arborescence de TinyForge :

```
nom-du-projet/
├── app/
│   ├── Controllers/   # Contrôleurs de l'application
│   ├── Models/        # Modèles de la base de données
│   └── Services/      # Services externes (Stripe, PayPal, etc.)
├── config/            # Fichiers de configuration
├── database/          # Gestion des migrations, seeds et factories
├── public/            # Fichiers accessibles publiquement (index.php, assets)
├── resources/         # Vues, styles, scripts, images, etc.
├── routes/            # Définition des routes
├── storage/           # Logs, cache, et fichiers temporaires
├── tests/             # Tests unitaires
├── .env               # Configuration de l'environnement
├── composer.json      # Dépendances PHP
└── forge              # CLI de TinyForge
```

---

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

---

## **Développement**

### **Définir les Routes**

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

use TinyForge\Http\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return $this->view('product.index', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $product = new Product($request->all());
        $product->save();
        return redirect('/products');
    }
}
```

### **Modèles**

Les modèles, représentant les tables de la base de données, se trouvent dans `app/Models`. Exemple :

```php
namespace App\Models;

use TinyForge\Iron\Database\Model;

class Product extends Model
{
    protected $table = 'products';
}
```

---

## **Configuration**

Les fichiers de configuration sont regroupés dans le dossier `/config` :

- **`app.php`** : Paramètres globaux de l'application (clé, environnement, URL).
- **`database.php`** : Connexions à la base de données.
- **`mail.php`** : Configuration des services d'email.
- **`services.php`** : Paramètres des API tierces (Stripe, PayPal).
- **`admin.php`** : Options de l’interface d’administration.
- **`ember.php`** : Configuration du CMS intégré.

---

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
