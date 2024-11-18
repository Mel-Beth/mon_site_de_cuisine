<?php
session_start();
if (!isset($_GET['id'])) {
    header('Location: manage_recipes.php');
    exit;
}
require '../data/db.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'ID de l'utilisateur est passé dans l'URL
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Aucun ID d'utilisateur fourni.";
    header('Location: manage_users.php');
    exit;
}

$id_utilisateur = intval($_GET['id']);

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$utilisateur) {
    $_SESSION['error'] = "Utilisateur non trouvé.";
    header('Location: manage_users.php');
    exit;
}

// Modifier l'utilisateur (traitement du formulaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mdp = htmlspecialchars($_POST['mdp']);
    $id_role = htmlspecialchars($_POST['id_role']);

    // Si le mot de passe est vide, ne pas le modifier
    if (empty($mdp)) {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET pseudo = ?, id_role = ? WHERE id_utilisateur = ?");
        $stmt->execute([$pseudo, $id_role, $id_utilisateur]);
    } else {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateurs SET pseudo = ?, mdp = ?, id_role = ? WHERE id_utilisateur = ?");
        $stmt->execute([$pseudo, $mdp_hash, $id_role, $id_utilisateur]);
    }
    // Redirection vers la page de gestion des utilisateurs après la mise à jour
    header('Location: manage_users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Styles pour le formulaire de modification d'utilisateur */
        form {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            /* Augmenté le padding pour plus d'espace à l'intérieur */
            margin: 30px auto;
            /* Centrer le formulaire avec une marge supérieure */
            width: 80%;
            /* Largeur du formulaire */
        }

        form input[type="text"],
        form input[type="password"],
        form select {
            width: 100%;
            padding: 12px;
            /* Augmenté le padding pour plus d'espace à l'intérieur */
            margin-bottom: 15px;
            /* Ajout d'une marge inférieure */
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        /* Boutons pour le formulaire */
        form button {
            background-color: #ffcc00;
            color: white;
            padding: 12px 20px;
            /* Augmenté le padding pour plus d'espace à l'intérieur */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
            margin-top: 10px;
            /* Ajout d'une marge supérieure pour séparer du champ précédent */
        }

        form button:hover {
            background-color: #e6b800;
        }

        /* Styles pour les messages d'erreur */
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            /* Centrer le message d'erreur */
            margin: 20px 0;
            /* Ajout d'une marge autour du message */
        }
    </style>
</head>

<body>
    <nav class="main-nav">
        <ul>
            <li><a href="dashboard.php">Accueil</a></li>
            <li><a href="manage_recipes.php">Gérer les Recettes</a></li>
            <li><a href="manage_users.php">Gérer les Utilisateurs</a></li>
            <li><a href="../logout.php">Déconnexion</a></li>
        </ul>
    </nav>

    <h1 class="main-title">Modifier l'Utilisateur</h1>

    <!-- Affichage des messages d'erreur -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="pseudo" placeholder="Pseudo" value="<?php echo htmlspecialchars($utilisateur['pseudo']); ?>" required>
        <input type="password" name="mdp" placeholder="Nouveau Mot de passe (laisser vide pour ne pas changer)">
        <select name="id_role" required>
            <option value="">Sélectionnez le rôle</option>
            <option value="1" <?php if ($utilisateur['id_role'] == 1) echo 'selected'; ?>>Administrateur</option>
            <option value="2" <?php if ($utilisateur['id_role'] == 2) echo 'selected'; ?>>Modérateur</option>
            <option value="3" <?php if ($utilisateur['id_role'] == 3) echo 'selected'; ?>>Utilisateur</option>
        </select>
        <button type="submit" name="update_user">Mettre à Jour</button>
    </form>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>
</body>

</html>