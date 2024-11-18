<?php
session_start(); // Démarrer la session

// Connexion à la base de données
$host = 'localhost';
$dbname = 'recette_melanie';
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = ''; // Remplacez par votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion: " . htmlspecialchars($e->getMessage());
    exit;
}

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: login.php"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

// Récupérer les informations de l'utilisateur
$utilisateur_id = $_SESSION['id_utilisateur'];

try {
    // Préparer et exécuter la requête pour récupérer les informations de l'utilisateur
    $query = "SELECT * FROM utilisateurs WHERE id_utilisateur = :id_utilisateur";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_utilisateur', $utilisateur_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifiez si l'utilisateur existe
    if (!$user) {
        echo "Utilisateur introuvable.";
        exit;
    }

    // Récupérer les recettes de l'utilisateur
    $recettesQuery = "SELECT * FROM recettes WHERE id_utilisateur = :id_utilisateur";
    $stmtRecettes = $pdo->prepare($recettesQuery);
    $stmtRecettes->bindParam(':id_utilisateur', $utilisateur_id, PDO::PARAM_INT);
    $stmtRecettes->execute();
    $recettes = $stmtRecettes->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les commentaires de l'utilisateur
    $commentairesQuery = "SELECT * FROM commentaires WHERE id_utilisateur = :id_utilisateur";
    $stmtCommentaires = $pdo->prepare($commentairesQuery);
    $stmtCommentaires->bindParam(':id_utilisateur', $utilisateur_id, PDO::PARAM_INT);
    $stmtCommentaires->execute();
    $commentaires = $stmtCommentaires->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les évaluations de l'utilisateur avec le nom de la recette
    $evaluationsQuery = "
        SELECT e.*, r.nom AS nom_recette 
        FROM evaluations e 
        JOIN recettes r ON e.id_recette = r.id_recette 
        WHERE e.id_utilisateur = :id_utilisateur";
    $stmtEvaluations = $pdo->prepare($evaluationsQuery);
    $stmtEvaluations->bindParam(':id_utilisateur', $utilisateur_id, PDO::PARAM_INT);
    $stmtEvaluations->execute();
    $evaluations = $stmtEvaluations->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . htmlspecialchars($e->getMessage());
    exit;
}

// Affichage d'un message de succès, s'il existe
if (isset($_SESSION['message'])) {
    echo "<div class='success-message'>" . htmlspecialchars($_SESSION['message']) . "</div>";
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        .profile-info {
            width: 80%;
            background-color: rgba(255, 255, 255, 0.9);
            /* Fond blanc semi-transparent */
            border-radius: 10px;
            /* Coins arrondis */
            padding: 30px;
            /* Espacement intérieur */
            margin: 20px auto;
            /* Centrer le conteneur */

            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            /* Ombre légère pour le relief */
        }

        .profile-info h3 {
            font-size: 1.8em;
            /* Taille de police pour les titres */
            color: #333;
            /* Couleur du texte */
            margin-bottom: 15px;
            /* Espacement sous les titres */
            border-bottom: 2px solid #e6b800;
            /* Ligne sous le titre */
            padding-bottom: 5px;
            /* Espacement sous la ligne */
        }

        .profile-info ul {
            width: 100%;
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
        }

        .profile-info ul li {
            background-color: #f8f8f8;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            margin: 0 15px 15px 0;
            flex-grow: 1;
            /* Permet à l'élément de prendre tout l'espace disponible */
            flex-basis: calc(50% - 15px);
            /* Ajustez le calcul pour tenir compte de la marge */
            box-sizing: border-box;
            /* Assurez-vous que le padding et la bordure sont inclus dans la largeur totale */
        }

        .profile-info ul li:hover {
            background-color: #e9f5ff;
            /* Changement de couleur au survol */
            transform: translateY(-2px);
            /* Légère élévation au survol */
        }

        .profile-info ul li a {
            text-decoration: none;
            color: black;
        }


        .profile-info strong {
            color: #007BFF;
            /* Couleur bleue pour les noms dans les commentaires */
        }

        .profile-info form {
            margin-top: 30px;
            /* Espacement au-dessus du formulaire */
            padding-top: 20px;
            /* Espacement intérieur au-dessus du formulaire */
        }

        .profile-info label {
            display: block;
            /* Affichage en bloc pour les étiquettes */
            margin-bottom: 8px;
            /* Espacement sous les étiquettes */
            font-weight: bold;
            /* Gras pour les étiquettes */
            color: #555;
            /* Couleur du texte des étiquettes */
        }

        .profile-info input[type="text"] {
            width: 100%;
            /* Largeur complète */
            padding: 12px;
            /* Espacement intérieur */
            border: 1px solid #ccc;
            /* Bordure grise */
            border-radius: 5px;
            /* Coins arrondis */
            margin-bottom: 20px;
            /* Espacement sous les champs de texte */
            font-size: 1em;
            /* Taille de police */
            transition: border-color 0.3s;
            /* Transition pour le focus */
        }

        .profile-info input[type="text"]:focus {
            border-color: #ffcc00;
            /* Bordure jaune au focus */
            outline: none;
            /* Supprime le contour par défaut */
        }

        .profile-info button {
            float: right;

            background-color: #ffcc00;
            /* Couleur de fond bleue */
            color: #333;
            padding: 12px 20px;
            /* Espacement intérieur */
            border: none;
            /* Pas de bordure */
            border-radius: 5px;
            /* Coins arrondis */
            cursor: pointer;
            /* Curseur pointer */
            font-size: 1em;
            /* Taille de police */
            transition: background-color 0.3s, transform 0.3s;
            /* Transition pour l'effet de survol */
        }

        .profile-info button:hover {
            background-color: #e6b800;
            /* Couleur de fond plus foncée au survol */
            transform: scale(1.05);
            /* Agrandissement au survol */
        }
    </style>
</head>

<body>

    <nav class="main-nav" aria-label="Navigation principale">
        <ul>
            <li><a href="profile.php">Accueil</a></li>
            <li><a href="add_recipe.php">Ajouter une recette</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </nav>

    <h2 class="main-title">Bienvenue, <span><?php echo htmlspecialchars($user['pseudo']); ?></span> !</h2>

    <div class="profile-info">

        <h3>Mes Recettes :</h3>
        <ul>
            <?php if (count($recettes) > 0): ?>
                <?php foreach ($recettes as $recette): ?>
                    <li>
                        <a href="recette.php?id=<?php echo $recette['id_recette']; ?>">
                            <?php echo htmlspecialchars($recette['nom']); ?>
                        </a>
                        <form action="recette.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $recette['id_recette']; ?>">
                            <button type="submit">Voir la recette</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucune recette trouvée.</li>
            <?php endif; ?>
        </ul>

        <h3>Mes Commentaires :</h3>
        <ul>
            <?php if (count($commentaires) > 0): ?>
                <?php foreach ($commentaires as $commentaire): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($commentaire['nom']); ?> :</strong>
                        <?php echo htmlspecialchars($commentaire['text_commentaire']); ?> (<?php echo htmlspecialchars($commentaire['date_com']); ?>)
                        <form action="traitements/update_comment.php" method="post" style="display:inline;">
                            <input type="hidden" name="id_commentaire" value="<?php echo $commentaire['id_com']; ?>">
                            <input type="text" name="text_commentaire" value="<?php echo htmlspecialchars($commentaire['text_commentaire']); ?>" required>
                            <button type="submit">Modifier</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucun commentaire trouvé.</li>
            <?php endif; ?>
        </ul>

        <h3>Mes Évaluations :</h3>
        <ul>
            <?php if (count($evaluations) > 0): ?>
                <?php foreach ($evaluations as $evaluation): ?>
                    <li>
                        <?php echo htmlspecialchars($evaluation['nom_recette']); ?> - Note : <?php echo htmlspecialchars($evaluation['note']); ?> (<?php echo htmlspecialchars($evaluation['date_evaluation']); ?>)
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucune évaluation trouvée.</li>
            <?php endif; ?>
        </ul>

        <h3>Modifier vos informations :</h3>
        <form action="traitements/update_profile.php" method="post">
            <label for="pseudo">Pseudo :</label>
            <input type="text" id="pseudo" name="pseudo" value="<?php echo isset($user['nom']) ? htmlspecialchars($user['nom']) : ''; ?>">

            <label for="mdp">Mot de passe :</label>
            <input type="text" id="mdp" name="mdp" value="<?php echo isset($user['prenom']) ? htmlspecialchars($user['prenom']) : ''; ?>">

            <button type="submit">Mettre à jour</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>

    <script src="js/script.js" defer></script>


</body>

</html>