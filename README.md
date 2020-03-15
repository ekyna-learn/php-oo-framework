Framework en PHP objet
===

Le but de ce projet est de développer des componsants pour créer un (mini) framework 
en PHP objet et ainsi, appliquer les bonnes pratiques en matière de programmation.

Le fonctionnement de ces composants est inspiré de vraies librairies (Symfony, Doctrine). 
Dont nous travaillerons ici des versions très simplifiées. Ces composants nous aideront à 
automatiser les tâches récurrentes de la création de sites internet (formulaire, base de données, URLs, templates).

Nous allons développer une gestion d'utilisateurs. À la fin de ce TP, vous pourrez ajouter assez rapidement des gestions
 d'autres éléments (blog, catalogue produits, etc) en réutilisant les composants développés.

### Pré-requis

Bases de la programmation objet en PHP :
* Classes, instances, propriétés et méthodes.
* Héritage, interfaces.
* Classes et méthodes abstraites.
* Espaces de noms (namespaces).

Avoir installé :
* PHP 7+
* Un serveur MySql (et idéalement PhpMyAdmin)

### Installation

Copiez ce projet dans votre compte GitHub en cliquant sur le bouton &laquo; __Fork__ &raquo; en haut à droite de cette 
page.

Une fois le projet copié dans votre compte, clonez votre _Fork_ (la commande suivante créera un sous-dossier nommé 
&laquo; php-oo-framework &raquo;) :

    git clone https://github.com/<votre-compte>/php-oo-framework.git

Placez-vous dans le dossier du projet

    cd php-oo-framework
    
Installez [Composer](https://getcomposer.org/download/)

    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    
Installez les dépendances
 
    composer install

_Cette commande créé un dossier &laquo; vendor &raquo; qui contiendra les librairies PHP nécessaires au fonctionnement 
de ce projet, et initialiser le chargement automatique de classes (via les espaces de noms "namespaces")._

Vous pourrez initialiser l'auto-chargement de classe en ajoutant la ligne suivante au début de vos fichiers PHP :

```php
// La constante __DIR__ équivaut au chemin du dossier dans lequel se trouve ce fichier.
// Adaptez la partie  '/vendor/autoload.php' en fonction !
require __DIR__ . '/vendor/autoload.php';
```


### Avant de commencer

* Vous pouvez lire [cette page](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/tips.md) pour obtenir 
quelques astuces.
* Vous pouvez lire [cette page](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/test.md) pour en 
apprendre d'avantage sur les tests unitaires.

### Let's code !

Dans un premier temps nous allons développer la gestion d'utilisateurs de façon basique 
(partie [Introduction](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/intro.md)) pour nous familiariser avec les traitements à mettre en place.
Puis nous développerons un par un chaque composant afin d'améliorer notre code.

:warning: Lorsque vous aurez fini une partie, pensez sauvergader votre travail (git commit/push)
avant de passer à la suite !

Suivez les différentes phases du projet :

1. [Introduction](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/intro.md)
1. [Formulaire](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/form.md)
1. [Persistance](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/persistence.md)
1. [Services](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/services.md)
1. [Routage](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/routing.md)
