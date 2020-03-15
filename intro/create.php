<?php

// Le fichier boot.php contient la configuration et la connection à la base de données
require __DIR__ . '/boot.php';

use App\Entity\User;
use App\Manager\UserManager;

// Créer une instance de la classe \App\Entity\User
$user = new User();

// $userForm est défini dans boot.php

// Définit la donnée à manipuler (l'instance de la clas User)
$userForm->setData($user);

// Définit l'action (ce fichier)
$userForm->setAction('create.php');

// Lit les données de la requête HTTP
$userForm->bindRequest($_POST);

// Contrôler si le formulaire à été soumis
if ($userForm->isSubmitted()) {
    // Créer une instance de la classe \App\Manager\UserManager
    $manager = new UserManager($connection);

    // Utiliser ce 'manager' pour insérer l'utilisateur dans la base de données
    $manager->persist($user);

    // Si l'insertion dans la base de donnée a réussi,
    // rediriger vers le détail de l'utilisateur (read.php?id= ???) à l'aide du code suivant
    // (voir la page Astuces (Tips) de la documentation pour un exemple de redirection)
    http_response_code(302);
    header('Location: read.php?id=' . $user->getId());
    exit;
}
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
                <h1 class="h2">Créer un nouvel utilisateur</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <!-- Lien vers la liste des utilisateurs -->
                    <a href="list.php" class="btn btn-default">
                        Retour
                    </a>
                </div>
            </div>

            <!-- Formulaire de création d'utilisateur -->
            <?php echo $userForm->render(); ?>

        </main>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>

