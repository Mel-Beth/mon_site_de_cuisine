<?php
session_start();
$host = 'localhost';
$dbname = 'recette_melanie';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion: " . htmlspecialchars($e->getMessage());
    exit;
}

// Vérifiez si l'ID de la recette est passé dans l'URL
if (isset($_GET['id'])) {
    $recette_id = $_GET['id'];

    // Récupérer les détails de la recette
    $query = "
        SELECT r.*, 
            d.nom AS difficulte, 
            c.nom AS categorie,
            tp.temps_preparation AS temps_preparation, 
            tp.temps_cuisson AS temps_cuisson, 
            p.chemin AS photo_chemin,
            GROUP_CONCAT(DISTINCT CONCAT(i.nom, ' - ', ir.quantite, ' ', uq.nom) SEPARATOR ', ') AS ingredients,
            GROUP_CONCAT(DISTINCT CONCAT(ins.ordre, '. ', ins.description) ORDER BY ins.ordre SEPARATOR '<br>') AS instructions,
            AVG(e.note) AS note_moyenne,
            COUNT(cmt.id_com) AS nombre_commentaires,
            GROUP_CONCAT(DISTINCT a.nom_local SEPARATOR ', ') AS appellations,
            GROUP_CONCAT(DISTINCT a.histoire SEPARATOR '<br>') AS histoires,
            GROUP_CONCAT(DISTINCT a.differences SEPARATOR '<br>') AS differences
        FROM recettes r
        LEFT JOIN difficultes d ON r.id_difficulte = d.id_difficulte
        LEFT JOIN categories c ON r.id_catg = c.id_catg
        LEFT JOIN temps_preparation tp ON r.id_recette = tp.id_recette
        LEFT JOIN photos p ON r.id_recette = p.id_recette
        LEFT JOIN ingredients_recettes ir ON r.id_recette = ir.id_recette
        LEFT JOIN ingredients i ON ir.id_ingredient = i.id_ingredient
        LEFT JOIN unites_quantites uq ON ir.id_quantite = uq.id_quantite
        LEFT JOIN instructions ins ON r.id_recette = ins.id_recette
        LEFT JOIN evaluations e ON r.id_recette = e.id_recette
        LEFT JOIN commentaires cmt ON r.id_recette = cmt.id_recette
        LEFT JOIN appellations a ON r.id_recette = a.id_recette
        WHERE r.id_recette = :id_recette
        GROUP BY r.id_recette
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
    $stmt->execute();
    $recette = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recette) {
        echo "Recette introuvable.";
        exit;
    }

    // Récupérer les ingrédients
    $ingredientsQuery = "SELECT i.nom, ir.quantite, uq.nom AS unite 
                          FROM ingredients_recettes ir 
                          JOIN ingredients i ON ir.id_ingredient = i.id_ingredient 
                          JOIN unites_quantites uq ON ir.id_quantite = uq.id_quantite 
                          WHERE ir.id_recette = :id_recette";
    $stmtIngredients = $pdo->prepare($ingredientsQuery);
    $stmtIngredients->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
    $stmtIngredients->execute();
    $ingredients = $stmtIngredients->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les instructions
    $instructionsQuery = "SELECT * FROM instructions WHERE id_recette = :id_recette ORDER BY ordre";
    $stmtInstructions = $pdo->prepare($instructionsQuery);
    $stmtInstructions->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
    $stmtInstructions->execute();
    $instructions = $stmtInstructions->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les commentaires
    $commentairesQuery = "SELECT * FROM commentaires WHERE id_recette = :id_recette";
    $stmtCommentaires = $pdo->prepare($commentairesQuery);
    $stmtCommentaires->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
    $stmtCommentaires->execute();
    $commentaires = $stmtCommentaires->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les évaluations
    $evaluationsQuery = "SELECT AVG(note) AS note_moyenne FROM evaluations WHERE id_recette = :id_recette";
    $stmtEvaluations = $pdo->prepare($evaluationsQuery);
    $stmtEvaluations->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
    $stmtEvaluations->execute();
    $evaluation = $stmtEvaluations->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Aucune recette spécifiée.";
    exit;
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $difficulte = $_POST['id_difficulte'];
    $categorie = $_POST['id_catg'];
    $instructions = $_POST['instructions']; // Récupération des instructions sous forme de tableau

    // Mettre à jour la recette dans la base de données
    $updateQuery = "UPDATE recettes SET nom = :nom, id_difficulte = :id_difficulte, id_catg = :id_catg WHERE id_recette = :id_recette";
    $stmtUpdate = $pdo->prepare($updateQuery);
    $stmtUpdate->bindParam(':nom', $nom);
    $stmtUpdate->bindParam(':id_difficulte', $difficulte);
    $stmtUpdate->bindParam(':id_catg', $categorie);
    $stmtUpdate->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
    $stmtUpdate->execute();

    // Mettre à jour les instructions dans la base de données
    $updateInstructionsQuery = "DELETE FROM instructions WHERE id_recette = :id_recette"; // Supprimer les anciennes instructions
    $stmtDeleteInstructions = $pdo->prepare($updateInstructionsQuery);
    $stmtDeleteInstructions->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
    $stmtDeleteInstructions->execute();

    // Ajouter les nouvelles instructions
    foreach ($instructions as $ordre => $instruction) {
        $instruction = trim($instruction); // Enlever les espaces superflus
        if (!empty($instruction)) { // Ne pas insérer les instructions vides
            $insertInstructionQuery = "INSERT INTO instructions (id_recette, ordre, description) VALUES (:id_recette, :ordre, :description)";
            $stmtInsertInstruction = $pdo->prepare($insertInstructionQuery);
            $stmtInsertInstruction->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
            $stmtInsertInstruction->bindParam(':ordre', $ordre + 1); // Ordre commence à 1
            $stmtInsertInstruction->bindParam(':description', $instruction);
            $stmtInsertInstruction->execute();
        }
    }

    // Rediriger vers la page de profil avec un message de succès
    $_SESSION['message'] = "Recette mise à jour avec succès.";
    header("Location: profil.php"); // Redirigez vers la page de profil ou une autre page
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Recette</title>
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Détails de la recette */
        .recipe-details {
            width: 80%;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* Titre de la recette */
        .recipe-title {
            text-align: center;
            font-size: 2em;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        /* Image de la recette */
        .recipe-image {
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 0 auto;
            /* Centre l'image */
        }

        /* Sections des ingrédients et instructions */
        .section-title {
            text-align: center;
            font-size: 1.5em;
            color: #555;
            margin-top: 20px;
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
        p {
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

        /* Formulaire de modification */
        .edit-form {
            margin-top: 30px;
            padding-top: 20px;
        }

        .edit-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .edit-form input[type="text"],
        .edit-form select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .edit-form input[type="text"]:focus,
        .edit-form select:focus {
            border-color: #ffcc00;
            /* Bordure jaune au focus */
            outline: none;
            /* Supprime le contour par défaut */
        }

        .edit-form button {
            background-color: #ffcc00;
            /* Couleur de fond jaune */
            color: #333;
            margin: 10px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.3s;
        }

        .edit-form button:hover {
            background-color: #e6b800;
            /* Couleur de fond plus foncée au survol */
            transform: scale(1.05);
            /* Agrandissement au survol */
        }

        /* Instructions supplémentaires pour les boutons de suppression */
        .remove-instruction {
            background-color: #dc3545;
            /* Couleur rouge pour le bouton de suppression */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            /* Ajoute un peu d'espace entre l'input et le bouton */
        }

        .remove-instruction:hover {
            background-color: #c82333;
            /* Rouge plus foncé au survol */
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

    <div class="recipe-details">
        <h2 class="recipe-title"><?php echo htmlspecialchars($recette['nom']); ?></h2>
        <img class="recipe-image" src="<?php echo htmlspecialchars($recette['photo_chemin']); ?>" alt="<?php echo htmlspecialchars($recette['nom']); ?>" width="200">
        <p><strong>Difficulté :</strong> <?php echo htmlspecialchars($recette['difficulte']); ?></p>
        <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($recette['categorie']); ?></p>
        <p><strong>Temps de préparation :</strong> <?php echo isset($recette['temps_preparation']) && $recette['temps_preparation'] !== '' ? htmlspecialchars($recette['temps_preparation']) . ' minutes' : 'Non spécifié'; ?></p>
        <p><strong>Temps de cuisson :</strong> <?php
                                                if (isset($recette['temps_cuisson'])) {
                                                    if ($recette['temps_cuisson'] > 0) {
                                                        echo htmlspecialchars($recette['temps_cuisson']) . ' minutes';
                                                    } else {
                                                        echo 'Pas de cuisson';
                                                    }
                                                } else {
                                                    echo 'Non spécifié';
                                                }
                                                ?></p>
        <h3 class="section-title">Ingrédients :</h3>
        <ul class="ingredient-list">
            <?php
            $ingredients = explode(', ', $recette['ingredients']);
            foreach ($ingredients as $ingredient): ?>
                <li><?php echo htmlspecialchars($ingredient); ?></li>
            <?php endforeach; ?>
        </ul>

        <h3 class="section-title">Instructions :</h3>
        <ul class="instruction-list">
            <?php
            // Récupérer les instructions de la recette
            $instructions = $recette['instructions']; // Récupérer les instructions sous forme de texte brut
            // Vérifiez si les instructions contiennent des balises <br>
            if (strpos($instructions, '<br>') !== false) {
                // Remplacer les balises <br> par des sauts de ligne
                $instructionsArray = explode('<br>', $instructions);
            } else {
                // Si pas de balises <br>, utiliser un autre séparateur si nécessaire
                $instructionsArray = explode("\n", $instructions);
            }

            foreach ($instructionsArray as $instruction): ?>
                <li><?php echo htmlspecialchars(trim($instruction)); ?></li>
            <?php endforeach; ?>
        </ul>

        <h3 class="section-title">Appellations :</h3>
        <ul class="ingredient-list">
            <?php
            $appellations = explode(', ', $recette['appellations']);
            foreach ($appellations as $appellation): ?>
                <li><?php echo htmlspecialchars($appellation); ?></li>
            <?php endforeach; ?>
        </ul>

        <h3 class="section-title">Histoires :</h3>
        <p>
            <?php
            $histoires = explode('<br>', $recette['histoires']);
            foreach ($histoires as $histoire): ?>
                <span><?php echo htmlspecialchars($histoire); ?></span><br>
            <?php endforeach; ?>
        </p>

        <h3 class="section-title">Différences :</h3>
        <p>
            <?php
            $differences = explode('<br>', $recette['differences']);
            foreach ($differences as $difference): ?>
                <span><?php echo htmlspecialchars($difference); ?></span><br>
            <?php endforeach; ?>
        </p>

        <h3 class="section-title">Évaluation :</h3>
        <p>Note moyenne : <?php echo isset($recette['note_moyenne']) ? htmlspecialchars($recette['note_moyenne']) : 'Aucune évaluation'; ?></p>

        <h3 class="section-title">Commentaires :</h3>
        <ul class="ingredient-list">
            <?php if ($recette['nombre_commentaires'] > 0): ?>
                <?php
                // Récupérer les commentaires
                $commentairesQuery = "SELECT nom, text_commentaire, date_com FROM commentaires WHERE id_recette = :id_recette";
                $stmtCommentaires = $pdo->prepare($commentairesQuery);
                $stmtCommentaires->bindParam(':id_recette', $recette_id, PDO::PARAM_INT);
                $stmtCommentaires->execute();
                $commentaires = $stmtCommentaires->fetchAll(PDO::FETCH_ASSOC);

                foreach ($commentaires as $commentaire): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($commentaire['nom']); ?> :</strong>
                        <?php echo htmlspecialchars($commentaire['text_commentaire']); ?>
                        (<?php echo htmlspecialchars($commentaire['date_com']); ?>)
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Aucun commentaire trouvé.</li>
            <?php endif; ?>
        </ul>

        <!-- Formulaire de modification -->
        <h3 class="section-title">Modifier la recette :</h3>
        <form action="recette.php?id=<?php echo $recette_id; ?>" method="post" id="recipeForm" class="edit-form">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($recette['nom']); ?>" required>

            <label for="id_difficulte">Difficulté :</label>
            <select id="id_difficulte" name="id_difficulte">
                <?php
                $difficultes = [
                    (object) ['id' => 1, 'nom' => 'Facile'],
                    (object) ['id' => 2, 'nom' => 'Moyen'],
                    (object) ['id' => 3, 'nom' => 'Difficile']
                ];
                foreach ($difficultes as $difficulte):
                    $selected = ($recette['id_difficulte'] == $difficulte->id) ? 'selected' : ''; ?>
                    <option value="<?php echo $difficulte->id; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($difficulte->nom); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="id_catg">Catégorie :</label>
            <select id="id_catg" name="id_catg">
                <?php
                $categories = [
                    (object) ['id' => 1, 'nom' => 'Entrées'],
                    (object) ['id' => 2, 'nom' => 'Plats'],
                    (object) ['id' => 3, 'nom' => 'Desserts'],
                    (object) ['id' => 4, 'nom' => 'Soupes']
                ];
                foreach ($categories as $categorie):
                    $selected = ($recette['id_catg'] == $categorie->id) ? 'selected' : ''; ?>
                    <option value="<?php echo $categorie->id; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($categorie->nom); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Instructions :</label>
            <div id="instructionsContainer">
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

            <button type="submit">Mettre à jour la recette</button>
            <a href="profile.php" style="display: inline-block; background-color: #ffcc00; color: #333; padding: 10px 15px; border: none; border-radius: 5px; text-decoration: none; cursor: pointer;">
                Retour au Profil
            </a>

        </form>

        <script>
            document.getElementById('addInstruction').addEventListener('click', function() {
                const container = document.getElementById('instructionsContainer');
                const newInstructionDiv = document.createElement('div');
                newInstructionDiv.classList.add('instruction');
                newInstructionDiv.innerHTML = `
                <input type="text" name="instructions[]" required>
                <button type="button" class="remove-instruction">Supprimer</button>
            `;
                container.appendChild(newInstructionDiv);
            });

            document.getElementById('instructionsContainer').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-instruction')) {
                    e.target.parentElement.remove();
                }
            });
        </script>
    </div>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>
</body>

</html>