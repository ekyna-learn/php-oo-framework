<?php

// Le fichier boot.php contient la configuration et la connection à la base de données
require __DIR__ . '/boot.php';

use App\Manager\UserManager;
use App\Repository\UserRepository;

// Créer une instance de la classe \App\Repository\UserRepository
$repository = new UserRepository($connection);

// Utiliser cette instance pour récupérer l'utilisateur à afficher.
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (0 >= $id) {
    http_response_code(302);
    header('Location: list.php');
    exit;
}

$user = $repository->findOneById($id);

// $userForm est défini dans boot.php

// Définit la donnée à manipuler (l'instance de la clas User)
$userForm->setData($user);

// Définit l'action (ce fichier + identifiant de l'utilisateut)
$userForm->setAction('update.php?id=' . $id);

// Lit les données de la requête HTTP
$userForm->bindRequest($_POST);

// Contrôler si le formulaire à été soumis
if ($userForm->isSubmitted()) {
    // Créer une instance de la classe \App\Manager\UserManager
    $manager = new UserManager($connection);

    // Utiliser ce 'manager' pour mettre à jour l'utilisateur dans la base de données
    $manager->persist($user);

    // Si la mise à jour dans la base de donnée a réussi,
    // rediriger vers le détail de l'utilisateur (read.php?id= ???)
    // (voir la page Astuces (Tips) de la documentation pour un exemple de redirection)
    http_response_code(302);
    header('Location: read.php?id=' . $user->getId());
    exit;
}

// Plus bas dans le code HTML, utiliser les accesseurs (méthodes get* de la classe \App\Entity\User)
// pour afficher les valeurs des différentes propriétés de l'utilisateur
// (à insérer dans les attributs value="" des balises <input>)

?>
<!doctype html>
<html lang="fr">
<head>
    <?php include 'includes/head.php'; ?>
    <title>Administration</title>
</head>
<body>

<?php include 'includes/topbar.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Modifier l'utilisateur</h1>
                <!-- Masquer ce calque (div) si l'utilisateur est introuvable -->
                <?php if ($user) { ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <!-- Lien vers la page "Liste des utiliateurs" -->
                    <a href="list.php" class="btn btn-default">
                        Retour
                    </a>
                    &nbsp;
                    <!-- Lien vers la page "Supprimer l'utiliateur" -->
                    <!-- Ajouter l'identifiant de l'utilisateur dans l'attribut 'href' du lien -->
                    <a href="delete.php?id=" class="btn btn-danger">
                        Supprimer
                    </a>
                </div>
                <?php } ?>
            </div>

            <!-- Si l'utilisateur est introuvable, afficher le message d'erreur si dessous -->
            <?php if (!$user) { ?>
            <div class="alert alert-danger">
                Utiliateur introuvable
            </div>

            <?php
            } else {
                // Sinon, afficher le formulaire de mise à jour de l'utilisateur
                echo $userForm->render();
            }
            ?>

        </main>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>

