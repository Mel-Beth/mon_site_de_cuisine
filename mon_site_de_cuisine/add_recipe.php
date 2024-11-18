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

// Initialiser les messages
$message = '';
$error = '';

// Vérifiez si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
    $id_difficulte = isset($_POST['id_difficulte']) ? $_POST['id_difficulte'] : null;
    $id_catg = isset($_POST['id_catg']) ? $_POST['id_catg'] : null;
    $ingredients = isset($_POST['ingredients']) ? $_POST['ingredients'] : null;
    $instructions = isset($_POST['instructions']) ? $_POST['instructions'] : null;
    $temps_preparation = isset($_POST['temps_preparation']) ? $_POST['temps_preparation'] : null;
    $temps_cuisson = isset($_POST['temps_cuisson']) ? $_POST['temps_cuisson'] : null;
    $appellations = isset($_POST['appellations']) ? $_POST['appellations'] : null;
    $histoires = isset($_POST['histoires']) ? $_POST['histoires'] : null;
    $id_utilisateur = $_SESSION['id_utilisateur']; // ID de l'utilisateur connecté

    // Vérifiez si les valeurs nécessaires sont présentes
    if (!$nom || !$id_difficulte || !$id_catg || !$ingredients || !$instructions || !$temps_preparation || !$temps_cuisson) {
        $error = "Tous les champs sont requis.";
    } else {
        try {
            // Préparer et exécuter la requête d'insertion dans la table recettes
            $query = "INSERT INTO recettes (nom, id_difficulte, id_utilisateur, id_catg, temps_preparation, temps_cuisson, appellations, histoires) VALUES (:nom, :id_difficulte, :id_utilisateur, :id_catg, :temps_preparation, :temps_cuisson, :appellations, :histoires)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':id_difficulte', $id_difficulte);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur);
            $stmt->bindParam(':id_catg', $id_catg);
            $stmt->bindParam(':temps_preparation', $temps_preparation);
            $stmt->bindParam(':temps_cuisson', $temps_cuisson);
            $stmt->bindParam(':appellations', $appellations);
            $stmt->bindParam(':histoires', $histoires);
            $stmt->execute();

            // Récupérer l'ID de la recette insérée
            $id_recette = $pdo->lastInsertId();

            // Ajouter les ingrédients
            $ingredientsArray = explode(',', $ingredients); // Supposons que les ingrédients sont séparés par des virgules
            foreach ($ingredientsArray as $ingredient) {
                $ingredient = trim($ingredient); // Enlever les espaces
                // Récupérer ou insérer l'ingrédient dans la table des ingrédients
                $queryIngredient = "INSERT INTO ingredients (nom) VALUES (:nom) ON DUPLICATE KEY UPDATE id_ingredient=LAST_INSERT_ID(id_ingredient)";
                $stmtIngredient = $pdo->prepare($queryIngredient);
                $stmtIngredient->bindParam(':nom', $ingredient);
                $stmtIngredient->execute();

                // Récupérer l'ID de l'ingrédient
                $id_ingredient = $pdo->lastInsertId();

                // Insérer l'ingrédient dans la table ingredients_recettes
                $queryIngredientsRecette = "INSERT INTO ingredients_recettes (id_recette, id_ingredient, quantite, id_quantite) VALUES (:id_recette, :id_ingredient, :quantite, :id_quantite)";
                $stmtIngredientsRecette = $pdo->prepare($queryIngredientsRecette);
                $quantite = 1; // Vous pouvez ajuster cela pour permettre à l'utilisateur de spécifier une quantité
                $id_quantite = 1; // Remplacez cela par l'ID de l'unité de quantité appropriée
                $stmtIngredientsRecette->bindParam(':id_recette', $id_recette);
                $stmtIngredientsRecette->bindParam(':id_ingredient', $id_ingredient);
                $stmtIngredientsRecette->bindParam(':quantite', $quantite);
                $stmtIngredientsRecette->bindParam(':id_quantite', $id_quantite);
                $stmtIngredientsRecette->execute();
            }

            // Ajouter les instructions
            $instructionsArray = explode("\n", $instructions); // Séparer les instructions par nouvelle ligne
            foreach ($instructionsArray as $index => $instruction) {
                $instruction = trim($instruction); // Enlever les espaces
                if (!empty($instruction)) { // Vérifiez que l'instruction n'est pas vide
                    $queryInstructions = "INSERT INTO instructions (id_recette, ordre, description) VALUES (:id_recette, :ordre, :description)";
                    $stmtInstructions = $pdo->prepare($queryInstructions);
                    $ordre = $index + 1; // L'ordre commence à 1
                    $stmtInstructions->bindParam(':id_recette', $id_recette);
                    $stmtInstructions->bindParam(':ordre', $ordre);
                    $stmtInstructions->bindParam(':description', $instruction);
                    $stmtInstructions->execute();
                }
            }

            // Message de succès
            $_SESSION['message'] = "Recette ajoutée avec succès !";
            header("Location: profile.php"); // Redirige vers le profil de l'utilisateur
            exit;
        } catch (PDOException $e) {
            $error = "Erreur : " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Recette</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Détails de la recette */
        .recipe-details {
            width: 80%;
            background-color: rgba(255, 255, 255, 0.95);
            /* Légèrement plus opaque */
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* Titre de la recette */
        .recipe-title {
            text-align: center;
            font-size: 2.5em;
            /* Agrandi pour plus de visibilité */
            color: #333;
            margin-bottom: 20px;
        }

        /* Image de la recette */
        .recipe-image {
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 0 auto;
        }

        /* Sections des ingrédients et instructions */
        .section-title {
            text-align: center;
            font-size: 1.8em;
            /* Agrandi pour plus de visibilité */
            color: #555;
            margin-top: 20px;
            border-bottom: 2px solid #ffcc00;
            /* Ligne en dessous du titre */
            padding-bottom: 10px;
        }

        /* Listes d'ingrédients et d'instructions */
        .ingredient-list,
        .instruction-list {
            list-style-type: none;
            /* Supprime les puces */
            padding: 0;
            /* Supprime le remplissage par défaut */
        }

        .ingredient-list li,
        .instruction-list li,
        .para {
            background-color: #f9f9f9;
            /* Couleur de fond plus claire */
            border: 1px solid #ddd;
            /* Bordure grise */
            border-radius: 5px;
            /* Coins arrondis */
            padding: 12px;
            /* Espacement intérieur */
            margin-bottom: 10px;
            /* Espacement entre les éléments de liste */
            transition: background-color 0.3s;
            /* Transition pour l'effet de survol */
        }

        .ingredient-list li:hover,
        .instruction-list li:hover {
            background-color: #e6e6e6;
            /* Fond gris plus foncé au survol */
        }

        /* Styles pour les formulaires */
        form {
            background-color: #f9f9f9;
            /* Fond clair pour les formulaires */
            border: 1px solid #ddd;
            /* Bordure grise */
            border-radius: 5px;
            padding: 20px;
            /* Espacement intérieur */
            margin-top: 20px;
            margin-bottom: 10px;
        }

        form input[type="text"],
        form textarea,
        form select {
            width: 100%;
            /* Prend toute la largeur */
            padding: 10px;
            /* Espacement intérieur */
            margin-bottom: 15px;
            /* Espacement entre les champs */
            border: 1px solid #ccc;
            /* Bordure grise */
            border-radius: 4px;
            /* Coins arrondis */
            font-size: 1em;
            /* Taille de police */
        }

        form textarea {
            height: 100px;
            /* Hauteur spécifique pour le champ de texte */
        }

        /* Boutons pour les formulaires */
        form button {
            background-color: #ffcc00;
            /* Couleur de fond jaune */
            color: white;
            /* Couleur du texte */
            padding: 10px 15px;
            /* Espacement intérieur */
            border: none;
            /* Pas de bordure */
            border-radius: 5px;
            /* Coins arrondis */
            cursor: pointer;
            /* Curseur de pointeur */
            font-size: 1em;
            /* Taille de police */
            transition: background-color 0.3s;
            /* Transition pour l'effet de survol */
        }

        form button:hover {
            background-color: #e6b800;
            /* Couleur de fond plus foncée au survol */
        }

        /* Styles pour les sections de commentaires et d'évaluations */
        h3.section-title {
            margin-top: 30px;
            /* Espace au-dessus des titres de section */
            color: #333;
            /* Couleur du texte */
        }

        .ingredient-list {
            margin-top: 10px;
            /* Espace au-dessus de la liste des ingrédients */
        }

        .ingredient-list li {
            background-color: #f0f0f0;
            /* Fond gris clair pour les éléments de liste */
            border: 1px solid #ddd;
            /* Bordure grise */
            border-radius: 5px;
            /* Coins arrondis */
            padding: 10px;
            /* Espacement intérieur */
            margin-bottom: 10px;
            /* Espacement entre les éléments de liste */
        }

        /* Styles pour les messages d'erreur ou d'information */
        .error-message {
            color: red;
            /* Couleur rouge pour les messages d'erreur */
            font-weight: bold;
            /* Met le texte en gras */
        }

        .success-message {
            color: green;
            /* Couleur verte pour les messages de succès */
            font-weight: bold;
            /* Met le texte en gras */
        }

        /* Styles pour le bouton "Retour aux Recettes" */
        .back-button {
            display: inline-block;
            /* Affiche le bouton en ligne */
            background-color: #ffcc00;
            /* Couleur de fond jaune */
            color: white;
            /* Couleur du texte */
            padding: 10px 15px;
            /* Espacement intérieur */
            border-radius: 5px;
            /* Coins arrondis */
            text-decoration: none;
            /* Supprime le soulignement */
            font-size: 1em;
            /* Taille de police */
            transition: background-color 0.3s;
            /* Transition pour l'effet de survol */
        }

        .back-button:hover {
            background-color: #e6b800;
            /* Couleur de fond plus foncée au survol */
        }

        /* Styles pour les sections de commentaires */
        .commentaires-container {
            margin-top: 20px;
            /* Espace au-dessus de la section commentaires */
        }

        .commentaires-container h3 {
            margin-bottom: 10px;
            /* Espace au-dessous du titre des commentaires */
        }

        /* Styles pour les commentaires individuels */
        .commentaires-container li {
            background-color: #f9f9f9;
            /* Fond clair pour les commentaires */
            border: 1px solid #ddd;
            /* Bordure grise */
            border-radius: 5px;
            /* Coins arrondis */
            padding: 10px;
            /* Espacement intérieur */
            margin-bottom: 10px;
            /* Espacement entre les commentaires */
        }

        /* Styles pour les instructions ajoutées dynamiquement */
        .instructions-container {
            margin-top: 20px;
            /* Espace au-dessus de la section instructions */
        }

        .instruction {
            display: flex;
            /* Utilise flexbox pour aligner les éléments */
            align-items: center;
            /* Centre verticalement les éléments */
            margin-bottom: 10px;
            /* Espacement entre les instructions */
        }

        .instruction input[type="text"] {
            flex: 1;
            /* Prend tout l'espace disponible */
            padding: 10px;
            /* Espacement intérieur */
            border: 1px solid #ccc;
            /* Bordure grise */
            border-radius: 5px;
            /* Coins arrondis */
            margin-right: 10px;
            /* Espace entre le champ de texte et le bouton */
        }

        .instruction button {
            background-color: #dc3545;
            /* Rouge pour le bouton de suppression */
            color: white;
            /* Couleur du texte */
            border: none;
            /* Pas de bordure */
            padding: 5px 10px;
            /* Espacement intérieur */
            border-radius: 5px;
            /* Coins arrondis */
            cursor: pointer;
            /* Curseur de pointeur */
            transition: background-color 0.3s;
            /* Transition pour l'effet de survol */
        }

        .instruction button:hover {
            background-color: #c82333;
            /* Rouge plus foncé au survol */
        }

        /* Styles pour le bouton "Ajouter une instruction" */
        #addInstruction {
            background-color: #ffcc00;
            /* Jaune pour le bouton d'ajout */
            color: #333;
            /* Couleur du texte */
            padding: 10px 15px;
            /* Espacement intérieur */
            border: none;
            /* Pas de bordure */
            border-radius: 5px;
            /* Coins arrondis */
            cursor: pointer;
            /* Curseur de pointeur */
            transition: background-color 0.3s;
            /* Transition pour l'effet de survol */
            margin-top: 10px;
            /* Espace au-dessus du bouton */
        }

        #addInstruction:hover {
            background-color: #e6b800;
            /* Jaune plus foncé au survol */
        }

        /* Ajout de marges autour des sections */
        .section-title,
        .ingredient-list,
        .instruction-list,
        .edit-form {
            margin-bottom: 20px;
            /* Ajoute de l'espace entre les sections */
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

    <h2>Ajouter une Recette :</h2>

    <!-- Affichage des messages d'erreur ou de succès -->
    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="success-message"><?php echo $_SESSION['message'];
                                        unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="nom">Nom de la recette :</label>
        <input type="text" id="nom" name="nom" required>

        <label for="id_difficulte">Difficulté :</label>
        <select id="id_difficulte" name="id_difficulte" required>
            <option value="">Sélectionnez une difficulté</option>
            <option value="1">Facile</option>
            <option value="2">Moyenne</option>
            <option value="3">Difficile</option>
        </select>

        <label for="id_catg">Catégorie :</label>
        <select id="id_catg" name="id_catg" required>
            <option value="">Sélectionnez une catégorie</option>
            <option value="1">Entrées</option>
            <option value="2">Plats</option>
            <option value="3">Desserts</option>
            <option value="4">Soupes</option>
        </select>

        <label for="temps_preparation">Temps de préparation (en minutes) :</label>
        <input type="number" id="temps_preparation" name="temps_preparation" required>

        <label for="temps_cuisson">Temps de cuisson (en minutes) :</label>
        <input type="number" id="temps_cuisson" name="temps_cuisson" required>

        <label for="ingredients">Ingrédients :</label>
        <textarea id="ingredients" name="ingredients" rows="4" required></textarea>
        <small>Veuillez séparer les ingrédients par des virgules. (ex: Ail - 5 Grammes, Oignon - 50 Grammes)</small>

        <label>Instructions :</label>
        <div id="instructionsContainer" class="instructions-container">
            <?php
            // Récupérer les instructions de la recette
            $instructionsArray = isset($recette['instructions']) ? explode('<br>', $recette['instructions']) : [];
            foreach ($instructionsArray as $index => $instruction): ?>
                <div class="instruction">
                    <input type="text" name="instructions[]" value="<?php echo htmlspecialchars($instruction); ?>" required>
                    <button type="button" class="remove-instruction">Supprimer</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="addInstruction">Ajouter une instruction</button>

        <label for="appellations">Appellations :</label>
        <input type="text" id="appellations" name="appellations" placeholder="Ex: Boulettes de Viande à la Sauce Tomate">

        <label for="histoires">Histoires :</label>
        <textarea id="histoires" name="histoires" rows="2" placeholder="Partagez l'histoire de ce plat..."></textarea>

        <button type="submit">Ajouter la recette</button>
    </form>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>

    <script src="js/script.js" defer></script>

</body>

</html>