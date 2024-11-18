<?php
session_start();


require '../data/db.php'; // Inclure le fichier de connexion à la base de données

// Récupérer les utilisateurs
$stmt = $pdo->query("SELECT * FROM utilisateurs");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter un utilisateur (traitement du formulaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Hash le mot de passe
    $id_role = htmlspecialchars($_POST['id_role']);

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, mdp, id_role) VALUES (?, ?, ?)");
    $stmt->execute([$pseudo, $mdp, $id_role]);
    header('Location: manage_users.php');
    exit;
}

// Supprimer un utilisateur
if (isset($_GET['delete'])) {
    $id_utilisateur = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
    $stmt->execute([$id_utilisateur]);
    header('Location: manage_users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Utilisateurs</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Styles pour le formulaire d'ajout d'utilisateur */
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

        /* Styles pour le tableau des utilisateurs */
        table {
            width: 80%;
            margin: 20px auto;
            /* Centrer le tableau */
            border-collapse: collapse;
            /* Pour éviter les doubles bordures */
            background-color: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            /* Ajout d'un padding pour les cellules */
            text-align: left;
            border-bottom: 1px solid #ddd;
            /* Ligne de séparation */
        }

        th {
            background-color: #ffcc00;
            /* Couleur d'arrière-plan pour les en-têtes */
            color: #fff;
            /* Couleur du texte pour les en-têtes */
        }

        tr:hover {
            background-color: #f1f1f1;
            /* Couleur de survol pour les lignes */
        }

        /* Styles pour les messages d'erreur ou de succès */
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            /* Centrer le message d'erreur */
            margin: 20px 0;
            /* Ajout d'une marge autour du message */
        }

        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
            /* Centrer le message de succès */
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

    <h1 class="main-title">Gérer les Utilisateurs</h1>

    <h2>Ajouter un Nouvel Utilisateur</h2>
    <form method="POST" action="">
        <input type="text" name="pseudo" placeholder="Pseudo" required>
        <input type="password" name="mdp" placeholder="Mot de passe" required>
        <select name="id_role" required>
            <option value="">Sélectionnez le rôle</option>
            <option value="1">Administrateur</option>
            <option value="2">Modérateur</option>
            <option value="3">Utilisateur</option>
        </select>
        <button type="submit" name="add_user">Ajouter Utilisateur</button>
    </form>

    <h2>Liste des Utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pseudo</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?php echo htmlspecialchars($utilisateur['id_utilisateur']); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur['pseudo']); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur['id_role']); ?></td>
                    <td>
                        <a href="modifier_utilisateur.php?id=<?php echo $utilisateur['id_utilisateur']; ?>">Modifier</a>
                        <a href="?delete=<?php echo $utilisateur['id_utilisateur']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>
</body>

</html>