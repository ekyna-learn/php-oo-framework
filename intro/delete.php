<?php

// Le fichier boot.php contient la configuration et la connection à la base de données
require __DIR__ . '/boot.php';

use App\Manager\UserManager;
use App\Repository\UserRepository;
use Form\Field\CheckboxField;
use Form\Form;

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

// Créé un objet comme données à manipuler par le formulaire
$confirm = new \stdClass();
$confirm->confirmed = false;

// Créé un formulaire de suppression
$form = new Form('delete', 'delete.php?id=' . $id);
$form
    ->addField(new CheckboxField('confirmed', 'Confirmer la suppression ?', [
        'required' => true,
    ]))
    ->setData($confirm);

// Lit les données de la requête HTTP
$form->bindRequest($_POST);

// Contrôler si le formulaire à été soumis et que l'utilisateur a coché la case
if ($form->isSubmitted() && $confirm->confirmed) {
    // (Si le formulaire a été soumis)
    // Créer une instance de la classe \App\Manager\UserManager
    $manager = new UserManager($connection);

    // Utiliser ce 'manager' pour supprimer l'utilisateur de la base de données
    $manager->remove($user);

    // Si la suppression dans la base de donnée a réussi,
    // rediriger vers la liste des utilisateurs (list.php)
    // (voir la page Astuces (Tips) de la documentation pour un exemple de redirection)
    http_response_code(302);
    header('Location: list.php');
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
                <h1 class="h2">Supprimer l'utilisateur</h1>
                <!-- Masquer ce calque (div) si l'utilisateur est introuvable -->
                <?php if ($user) { ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <!-- Lien vers la liste des utilisateurs -->
                    <a href="list.php" class="btn btn-default">
                        Retour
                    </a>
                    &nbsp;
                    <!-- Lien vers la page "Modifier l'utiliateur" -->
                    <!-- Ajouter l'identifiant de l'utilisateur dans l'attribut 'href' du lien -->
                    <a href="update.php?id=<?php echo $user->getId(); ?>" class="btn btn-warning">
                        Modifier
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
                // Sinon, afficher le formulaire de suppression de l'utilisateur
                echo $form->render();
            }
            ?>
        </main>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>

