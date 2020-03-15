<?php

// Le fichier boot.php contient la configuration et la connection à la base de données
require __DIR__ . '/boot.php';

use App\Repository\UserRepository;

// Créer une instance de la classe \App\Repository\UserRepository

// Utiliser cette instance pour récupérer la liste des utilisateurs

// Plus bas dans le code HTML (dans la balise <table>), afficher la liste des utilisateurs

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
                <h1 class="h2">Liste des utilisateurs</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <!-- Lien vers la page "Créer un utilisateur" -->
                    <a href="create.php" class="btn btn-success">
                        Nouvel utilisateur
                    </a>
                </div>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>

                <!-- Début ligne utilisateur (code HTML à répéter pour chaque utilisateur) -->
                <tr>
                    <th scope="row">
                        1 <!-- Identifiant de l'utilisateur -->
                    </th>
                    <td>
                        <!-- Lien vers la page "Détail de l'utilisateur" -->
                        <a href="read.php?id=1">
                            <!-- Adresse email de l'utilisateur -->
                            example@example.org
                        </a>
                    </td>
                    <td>
                        <!-- Lien vers la page "Modifier l'utilisateur" -->
                        <a href="update.php?id=1" class="btn btn-sm btn-warning">
                            Modifier
                        </a>
                        <!-- Lien vers la page "Supprimer l'utilisateur" -->
                        <a href="delete.php?id=1" class="btn btn-sm btn-danger">
                            Supprimer
                        </a>
                    </td>
                </tr>
                <!-- Fin ligne utilisateur -->

                </tbody>
            </table>

        </main>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>

