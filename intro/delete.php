<?php

// Le fichier boot.php contient la configuration et la connection à la base de données
require __DIR__ . '/boot.php';

use App\Manager\UserManager;
use App\Repository\UserRepository;

// Créer une instance de la classe \App\Repository\UserRepository
$repository = new UserRepository($connection);

// Utiliser cette instance pour récupérer l'utilisateur à afficher.
// L'identifiant de l'utilisateur à afficher peut être récupéré dans la variable globale $_GET['id']
// Contrôler la valeur de l'identifiant (nombre entier supérieur à zéro).
// Si l'identifiant n'est pas valide (c'est sans doute qu'un lien est mal formatté dans un autre fichier PHP),
// rediriger l'internaute ou afficher un message d'erreur.
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (0 >= $id) {
    http_response_code(302);
    header('Location: list.php');
    exit;
}

$user = $repository->findOneById($id);

// Contrôler si le formulaire à été soumis, en vérfiant la valeur du champ de type 'hidden'.
// On vérifie donc si l'index 'user' existe dans le tableau $_POST (données soumises
// par le formulaire) et si la valeur associée à cet index est égale à 'user'.
if (array_key_exists('user', $_POST) && $_POST['user'] === 'user') {
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

            <?php } else { ?>
            <!-- Sinon, afficher le formulaire de suppression de l'utilisateur -->
            <!-- Ajouter l'identifiant de l'utilisateur dans l'attribut 'action' du formulaire -->
            <form action="delete.php?id=<?php echo $user->getId(); ?>" method="post">
                <!-- Champ masqué pour déterminer si le formulaire a été soumis -->
                <input type="hidden" name="user" value="user">
                <!-- Case à cocher pour confirmer la suppression -->
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="confirmed" name="confirmed" value="1" required="required">
                    <label for="confirmed">Confirmer la suppression ?</label>
                </div>
                <!-- Boutton de soumission -->
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
            <?php } ?>
        </main>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>

