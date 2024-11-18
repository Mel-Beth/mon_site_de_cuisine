<?php
session_start();
require '../data/db.php'; // Inclure le fichier de connexion à la base de données

$error = ''; // Initialiser la variable d'erreur

// Vérifiez si l'ID de la recette est passé dans l'URL
if (!isset($_GET['id'])) {
    header('Location: manage_recipes.php'); // Redirigez vers la page de gestion des recettes si l'ID n'est pas fourni
    exit;
}

$id_recette = intval($_GET['id']);

// Récupérer les détails de la recette à modifier
$stmt = $pdo->prepare("SELECT * FROM recettes WHERE id_recette = ?");
$stmt->execute([$id_recette]);
$recette = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifiez si la recette existe
if (!$recette) {
    header('Location: manage_recipes.php'); // Redirigez si la recette n'existe pas
    exit;
}

// Récupérer le temps de préparation et de cuisson
$stmt = $pdo->prepare("SELECT temps_preparation, temps_cuisson FROM temps_preparation WHERE id_recette = ?");
$stmt->execute([$id_recette]);
$temps = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les ingrédients
$stmt = $pdo->prepare("SELECT i.id_ingredient, i.nom, ir.quantite, uq.nom AS unite FROM ingredients_recettes ir 
                        JOIN ingredients i ON ir.id_ingredient = i.id_ingredient 
                        JOIN unites_quantites uq ON ir.id_quantite = uq.id_quantite 
                        WHERE ir.id_recette = ?");
$stmt->execute([$id_recette]);
$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les instructions
$stmt = $pdo->prepare("SELECT description FROM instructions WHERE id_recette = ? ORDER BY ordre");
$stmt->execute([$id_recette]);
$instructions = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_recipe'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $id_difficulte = intval($_POST['id_difficulte']);
    $id_catg = intval($_POST['id_catg']);
    $temps_preparation = intval($_POST['temps_preparation']);
    $temps_cuisson = intval($_POST['temps_cuisson']);
    $ingredients = $_POST['ingredients']; // Cela devrait être une chaîne de texte
    $instructions = $_POST['instructions']; // Cela devrait être un tableau

    // Vous pourriez ajouter une vérification ici pour gérer les erreurs
    if (empty($nom) || empty($id_difficulte) || empty($id_catg) || empty($temps_preparation) || empty($temps_cuisson) || empty($ingredients) || empty($instructions)) {
        $error = "Tous les champs sont requis.";
    } else {
        // Mise à jour de la recette
        $stmt = $pdo->prepare("UPDATE recettes SET nom = ?, id_difficulte = ?, id_catg = ? WHERE id_recette = ?");
        $stmt->execute([$nom, $id_difficulte, $id_catg, $id_recette]);

        // Mise à jour du temps de préparation et de cuisson
        $stmt = $pdo->prepare("UPDATE temps_preparation SET temps_preparation = ?, temps_cuisson = ? WHERE id_recette = ?");
        $stmt->execute([$temps_preparation, $temps_cuisson, $id_recette]);

        // Mettre à jour les ingrédients
        // Vous devez d'abord supprimer les anciens ingrédients
        $stmt = $pdo->prepare("DELETE FROM ingredients_recettes WHERE id_recette = ?");
        $stmt->execute([$id_recette]);

        // Insérer les nouveaux ingrédients
        if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
            foreach ($_POST['ingredients'] as $ingredient) {
                // Vérifiez que l'élément contient un tiret
                if (strpos($ingredient, '-') !== false) {
                    list($id_ingredient, $quantite) = explode('-', $ingredient);

                    // Vérifiez si les valeurs sont définies et valides
                    if (isset($id_ingredient) && isset($quantite)) {
                        $stmt = $pdo->prepare("INSERT INTO ingredients_recettes (id_recette, id_ingredient, quantite) VALUES (?, ?, ?)");
                        $stmt->execute([$id_recette, intval($id_ingredient), intval($quantite)]);
                    } else {
                        // Gérer le cas où id_ingredient ou quantite n'est pas défini
                        $error = "Erreur : id_ingredient ou quantité non définis pour l'ingrédient '$ingredient'.";
                    }
                } else {
                    // Gérer le cas où le format est incorrect
                    $error = "Erreur : Le format de l'ingrédient '$ingredient' est incorrect. Utilisez 'id-quantite'.";
                }
            }
        }

        // Mettre à jour les instructions
        // Vous devez d'abord supprimer les anciennes instructions
        $stmt = $pdo->prepare("DELETE FROM instructions WHERE id_recette = ?");
        $stmt->execute([$id_recette]);

        // Insérer les nouvelles instructions
        foreach ($instructions as $instruction) {
            $stmt = $pdo->prepare("INSERT INTO instructions (id_recette, description, ordre) VALUES (?, ?, ?)");
            $stmt->execute([$id_recette, $instruction, array_search($instruction, $instructions) + 1]);
        }

        $_SESSION['message'] = "Recette mise à jour avec succès.";
        header('Location: manage_recipes.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Recette</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Détails de la recette */
        .recipe-details {
            width: 80%;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin: 40px auto;
            /* Augmenté la marge supérieure et inférieure */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .recipe-title {
            text-align: center;
            font-size: 2em;
            color: #333;
            margin-bottom: 30px;
            /* Augmenté la marge inférieure */
        }

        .recipe-image {
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 20px auto;
            /* Ajouté une marge supérieure et inférieure */
        }

        /* Sections des ingrédients et instructions */
        .section-title {
            text-align: center;
            font-size: 1.5em;
            color: #555;
            margin: 30px 0;
            /* Augmenté la marge supérieure et inférieure */
        }

        /* Listes d'ingrédients et d'instructions */
        .ingredient-list,
        .instruction-list {
            list-style-type: none;
            padding: 0;
        }

        .ingredient-list li,
        .instruction-list li,
        .para {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            /* Augmenté le padding pour plus d'espace à l'intérieur */
            margin-bottom: 15px;
            /* Augmenté la marge inférieure */
        }

        /* Styles pour les formulaires */
        form {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            /* Augmenté le padding pour plus d'espace à l'intérieur */
            margin-top: 30px;
            /* Augmenté la marge supérieure */
        }

        form input[type="text"],
        form textarea,
        form select {
            width: 100%;
            padding: 12px;
            /* Augmenté le padding pour plus d'espace à l'intérieur */
            margin-bottom: 15px;
            /* Augmenté la marge inférieure */
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        form textarea {
            height: 120px;
            /* Augmenté la hauteur spécifique pour le champ de texte */
        }

        /* Boutons pour les formulaires */
        form button {
            float: right;
            background-color: #ffcc00;
            color: white;
            padding: 12px 20px;
            /* Augmenté le padding pour plus d'espace à l'intérieur */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
            margin: 10px;
        }

        form button:hover {
            background-color: #e6b800;
        }

        /* Styles pour les messages d'erreur ou d'information */
        .error-message {
            color: red;
            font-weight: bold;
            margin: 20px 0;
            /* Ajouté une marge autour des messages d'erreur */
        }

        .success-message {
            color: green;
            font-weight: bold;
            margin: 20px 0;
            /* Ajouté une marge autour des messages de succès */
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

    <h2 class="main-title">Modifier la Recette</h2>

    <!-- Affichage des messages d'erreur -->
    <?php if ($error): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="nom">Nom de la recette :</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($recette['nom']); ?>" required>

        <label for="id_difficulte">Difficulté :</label>
        <select id="id_difficulte" name="id_difficulte" required>
            <option value="1" <?php if ($recette['id_difficulte'] == 1) echo 'selected'; ?>>Facile</option>
            <option value="2" <?php if ($recette['id_difficulte'] == 2) echo 'selected'; ?>>Moyenne</option>
            <option value="3" <?php if ($recette['id_difficulte'] == 3) echo 'selected'; ?>>Difficile</option>
        </select>

        <label for="id_catg">Catégorie :</label>
        <select id="id_catg" name="id_catg" required>
            <option value="1" <?php if ($recette['id_catg'] == 1) echo 'selected'; ?>>Entrées</option>
            <option value="2" <?php if ($recette['id_catg'] == 2) echo 'selected'; ?>>Plats</option>
            <option value="3" <?php if ($recette['id_catg'] == 3) echo 'selected'; ?>>Desserts</option>
            <option value="4" <?php if ($recette['id_catg'] == 4) echo 'selected'; ?>>Soupes</option>
        </select>

        <label for="temps_preparation">Temps de préparation (en minutes) :</label>
        <input type="number" id="temps_preparation" name="temps_preparation" value="<?php echo htmlspecialchars($temps['temps_preparation']); ?>" required>

        <label for="temps_cuisson">Temps de cuisson (en minutes) :</label>
        <input type="number" id="temps_cuisson" name="temps_cuisson" value="<?php echo htmlspecialchars($temps['temps_cuisson']); ?>" required>

        <label for="ingredients">Ingrédients :</label>
        <textarea id="ingredients" name="ingredients[]" rows="4" required>
            <?php
            foreach ($ingredients as $ingredient) {
                echo htmlspecialchars($ingredient['id_ingredient'] . '-' . $ingredient['quantite']) . ", ";
            }
            ?>
        </textarea>
        <small>Veuillez séparer les ingrédients par des virgules. (ex: 1-200, 2-50 pour 200g de Tomate et 50g d'Oignon)</small>

        <label>Instructions :</label>
        <div id="instructionsContainer" class="instructions-container">
            <?php
            foreach ($instructions as $instruction) {
                echo '<div class="instruction">';
                echo '<input type="text" name="instructions[]" value="' . htmlspecialchars($instruction) . '" required>';
                echo '<button type="button" class="remove-instruction">Supprimer</button>';
                echo '</div>';
            }
            ?>
        </div>
    
            <button type="button" id="addInstruction">Ajouter une instruction</button>

            <button type="submit" name="update_recipe">Mettre à jour la recette</button>
    </form>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>

    <script>
        // JavaScript pour ajouter des instructions dynamiquement
        document.getElementById('addInstruction').addEventListener('click', function() {
            var newInstruction = document.createElement('div');
            newInstruction.className = 'instruction';
            newInstruction.innerHTML = '<input type="text" name="instructions[]" required> <button type="button" class="remove-instruction">Supprimer</button>';
            document.getElementById('instructionsContainer').appendChild(newInstruction);
        });

        // Event listener pour supprimer une instruction
        document.getElementById('instructionsContainer').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-instruction')) {
                e.target.parentElement.remove();
            }
        });
    </script>
</body>

</html>