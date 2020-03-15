Tips
===

### Sauvegarder son travail avec GIT

```git
# Prendre en compte les dernières modifications ou créations de fichiers.
git add .

# Créer un commit
git commit -m "Message pour indiquer à quoi correspondent ces modifications"

# Envoyer le commit sur les serveurs de GitHub
git push origin master
```

### Développer dans un fichier de test

Lorsque vous travaillez sur un algorithme (pour développer une méthode de classe par exemple), 
n'hésitez pas à __travailler dans un fichier séparé__ (à la racine du projet, _test.php_ par exemple) 
et en dehors d'une classe.
Vous gagnerez du temps à vous focaliser sur cet algorithme sans avoir à gérer le reste de la classe, 
l'instanciation, etc. 

Testez les exemples ci-dessous dans un fichier test.php&hellip; avant de les utiliser dans vos classes ! 


### Scripts utiles

Voici quelques exemples de codes PHP qui vous seront utiles pour la réalisation de ce projet.

#### Utiliser la classe DateTime

Créer un objet PHP __DateTime__ d'après une date au format _&laquo; base de données &raquo;_

    ```php
    // Date au format « base de données »
    $string = '2020-03-16';
  
    // Objet PHP DateTime
    $date = new \DateTime($string);
  
    // Formater un objet DateTime pour affichage (format français)
    echo $date->format('d/m/Y'); // Affiche « 16/03/2000 »
  
    // Formater un objet DateTime pour insertion dans la base de données
    $dbValue = $date->format('Y-m-d'); // Equivaut à $dbValue = '2020-03-16';
  
    // Créer une instance de la classe DateTime peut lever une exception 
    // si la chaîne de caractères passée en paramètre a un format non valide
    try {
        $date = new \DateTime("Attention : ça va boguer !");
    } catch (\Exception $e) {
        // Intercepte l'exception
        echo "Date non valide";
    }
    ```

#### Redirection HTTP

Rediriger l'internaute vers une URL _(ou un fichier PHP)_

    ```php
    // Défini un code HTTP de rédirection temporaire
    http_response_code(302);
  
    // Défini l'url vers laquelle l'internaute doit être redirigé
    // Attention à l'orthographe de « Location:  », respecter les majuscule/minuscules,
    // le deux points et l'espace.
    header('Location: fichier-cible.php');
  
    // OU avec une URL
    header('Location: /chemin/vers/la/page');
  
    // On utilise « exit » pour terminer le script afin que la redirection soit effective
    exit;
  
    // Attention : si un 'echo' a été executé avant ce code de redirection, celle-ci n'aura pas lieu.
    // Ce code est a executer avant un quelconque affichage pour être fonctionnel.
    // Un simple espace au début du fichier (avant la balide « <?php ») empêchera la redirection.
    ```

#### Utiliser le composant PropertyAccess

PropertyAccess est un composant du framework Symfony. Nous l'avons 
installé dans ce projet grâce à la commande ```composer install```.

Vous pouvez lire la 
[documentation officelle](https://symfony.com/doc/current/components/property_access.html#reading-from-objects), 
notamment les parties &laquo; _Usage_ &raquo; et &laquo; _Reading from Objects_ &raquo;, mais voici ce qui nous sera utile :


```php
<?php
// test.php

// Configure l'autochargement de classes (grâce à composer)
require __DIR__ . '/vendor/autoload.php';

/**
 * Une classe Product à titre d'exemple
 */
class Product
{
    // Propriétés privées
    private $id;
    private $title;

    // Propriété publique
    public $description;

    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}

// Produit de test
$product = new Product(12, 'iPhone');
$product->description = 'Awesome smarthpone !';

// Importe la classe PropertyAccess
use Symfony\Component\PropertyAccess\PropertyAccess;

// Créé une instance de la classe PropertyAccess
$accessor = PropertyAccess::createPropertyAccessor();



// --- Récupérer la valeur des propriétés de l'objet $product ---

// On peut récupérer la valeur de la propriété privé 'id' ainsi
// (en interne, $accessor appellera le méthode getId() de l'objet)
echo $accessor->getValue($product, 'id'); // Affiche '12'

// Ou le titre
echo $accessor->getValue($product, 'title'); // Affiche 'iPhone'

// Ou une propriété publique
echo $accessor->getValue($product, 'description'); // Affiche 'Awesome smarthpone !'



// --- Modifier la valeur des propriétés de l'objet $product ---

// Propriétés privées
// (en interne $accessor appellera la méthode setId() de l'objet)
$accessor->setValue($product, 'id', 16);
$accessor->setValue($product, 'title', 'Galaxy');
// Propriété publique
$accessor->setValue($product, 'description', 'Cheaper and good !');



// L'intérêt du composant PropertyAccess est de pouvoir récupérer
// ou modifier les propriétés d'un objet d'après un tableau associatif.

$product = new Product(12, 'iPhone');
$product->description = 'Awesome smarthpone !';

$properties = [
    'id'          => 16,
    'title'       => 'Galaxy',
    'description' => 'Cheaper and good !',
];

// Modifie l'objet $product d'après le tableau $properties
foreach ($properties as $property => $value) {
    $accessor->setValue($product, $property, $value);
}

// Affiche les valeurs des propriétés de l'objet $product
// d'après les index du tableau $properties
foreach (array_keys($properties) as $property) {
    echo "$property : " . $accessor->getValue($product, $property);
}

```
