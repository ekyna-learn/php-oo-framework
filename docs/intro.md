Introduction
===

Le dossier _intro_ contient l'intégration HTML/CSS de notre administration.

Le dossier _src_ contient le code de nos composants et de l'application (car au final, 
nous n'aurons plus besoin du dossier _intro_).

Avant de commencer à développer les fichiers du dossier _intro_, il faut préparer
 les 3 classes du diagramme ci-dessous.

* Développez entièrement la classe __App\Entity\User__.
* Préparez les classes __App\Repository\UserRepository__ et __App\Manager\UserManager__ : 
vous développerez leurs méthodes au fur et à mesure, lorsque vous en aurez besoin.


### Diagramme de classes

![Diagramme de classes](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/intro-class-diagram.png)

| Classe | Fichier |
| --- | --- |
| Persistence\\__EntityInterface__ | src/Persistence/EntityInterface.php |
| App\Entity\\__User__ | src/App/Entity/User.php |
| App\Repository\\__UserRepository__ | src/App/Repository/UserRepository.php |
| App\Manager\\__UserManager__ | src/App/Manager/UserManager.php |

Ce diagramme contient des points d'intérogation. Ce n'est pas standard en UML, mais cela 
vous indique que l'argument (paramètre d'une méthode) ou la valeur de retour peuvent être 
nuls. Comparez l'interface __EntityInterface__ (le fichier existe déjà) et sa représentation 
dans le diagramme.

Une fois la classe __User__ développée et les autres préparées, développez les fichiers suivants.
(Ces fichiers contiennent des commentaires pour vous guider).

_Dans votre terminal, placez-vous dans le dossier intro avec la commande : ```cd intro```, avant 
de lancer votre serveur web avec la commande : ```php -S localhost:8000```._

* intro/create.php

   Vous devrez développer les méthodes __UserManager::persist()__ et __UserManager::insert()__. 
   La méthode __persist__ enregistre l'utilisateur dans la base de donnée. Si l'utilisateur 
   a un ID c'est qu'il est déjà enregistré dans la base de données, on appelle donc la méthode 
   __update__, sinon la méthode __insert__. Après insertion, La méthode __insert__ doit récupérer 
   l'identifiant généré par la base de données (voir 
   [PDO::getLastInsertId](https://www.php.net/manual/fr/pdo.lastinsertid.php)) et l'assigner 
   à l'utilisateur (méthode __User::setId__).  

* intro/list.php     

    Vous devrez développer la méthode __UserRepository::findAll()__ qui renvoit la liste de 
    tous les utilisateurs enregistrés dans la base de données.

* intro/read.php     

    Vous devrez développer la méthode __UserRepository::findOneById()__ qui renvoit un 
    utilisateur recherché dans la base de données par son identifiant.

* intro/update.php     

    Vous devrez développer les méthodes __UserManager::update()__ qui met à jour un 
    utilisateur dans la base de données.

* intro/delete.php     

    Vous devrez développer les méthodes __UserManager::remove()__ qui supprime un utilisateur
    de la base de données.

Vérifiez que toutes ces pages fonctionnent correctement avant de passer à la suite.
Vous trouverez une correction de cette partie dans la branche 
[intro](https://github.com/ekyna-learn/php-oo-framework/tree/intro) du dépôt original.

Nous allons pouvoir passer à la suite et développer notre premier composant : 
[Formulaire](https://github.com/ekyna-learn/php-oo-framework/blob/master/docs/form.md).
