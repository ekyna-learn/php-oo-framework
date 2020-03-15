<?php

// Le fichier boot.php contient la configuration et la connection à la base de données
require __DIR__ . '/boot.php';

use App\Entity\User;

// Créer une instance de la classe \App\Entity\User

// Contrôler si le formulaire à été soumis, en vérfiant la valeur du champ de type 'hidden'.
// On vérifie donc si l'index 'user' existe dans le tableau $_POST (données soumises
// par le formulaire) et si la valeur associée à cet index est égale à 'user'

    // (Si le formulaire a été soumis)
    // Utiliser les mutateurs (méthodes set* de la classe \App\Entity\User)
    // pour mettre à jour l'utilisateur avec les données du formulaire
    // Exemple: $user->setEmail($_POST['email']);

    // Créer une instance de la classe \App\Manager\UserManager

    // Utiliser ce 'manager' pour insérer l'utilisateur dans la base de données

    // Si l'insertion dans la base de donnée a réussi,
    // rediriger vers le détail de l'utilisateur (read.php?id= ???) à l'aide du code suivant
    // (voir la page Astuces (Tips) de la documentation pour un exemple de redirection)

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
            <form action="create.php" method="post">
                <!-- Champ masqué pour déterminer si le formulaire a été soumis -->
                <input type="hidden" name="user" value="user">
                <!-- Champ "Email" -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="" required="required">
                </div>
                <!-- Champ "Nom" -->
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="" required="required">
                </div>
                <!-- Champ "Date de naissance" -->
                <div class="form-group">
                    <label for="birthday">Date de naissance</label>
                    <input type="date" class="form-control" id="birthday" name="birthday" placeholder="Date de naissance" value="">
                </div>
                <!-- Champ "Actif" -->
                <div class="form-group form-check">
                    <!-- value à '0' pour ne pas être cochée par défaut -->
                    <input type="checkbox" class="form-check-input" id="active" name="active" value="1">
                    <label for="active">Actif</label>
                </div>
                <!-- Boutton de soumission -->
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>

        </main>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>

