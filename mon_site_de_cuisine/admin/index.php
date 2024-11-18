<?php
session_start();
include '../data/db.php'; // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    // Vérifier les informations d'identification
    $sql = "SELECT id_utilisateur, pseudo, mdp, id_role FROM utilisateurs WHERE pseudo = ?";
    
    // Préparer et exécuter la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pseudo]);
    $user = $stmt->fetch();

    if ($user) {
        // Vérifier le mot de passe en clair
        if ($mdp === $user['mdp']) {
            // Vérifier si l'utilisateur a le rôle d'administrateur (id_role = 1)
            if ($user['id_role'] == 1) { // Supposons que l'id_role 1 est pour l'administrateur
                // Authentification réussie
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['pseudo'] = $user['pseudo'];
                $_SESSION['id_role'] = $user['id_role'];
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Vous n'avez pas les droits d'accès en tant qu'administrateur.";
            }
        } else {
            echo "Identifiants incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec ce pseudo.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <h1 class="title">Connexion admin</h1>
        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <label for="pseudo">Pseudo :</label>
                <input type="text" name="pseudo" required>
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe :</label>
                <input type="password" name="mdp" required>
            </div>
            <button type="submit" class="submit-button">Se connecter</button>
            <button type="button" class="submit-button" onclick="redirectToRegister();">S'inscrire</button>
        </form>
    </div>
    
    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>

</body>
</html>