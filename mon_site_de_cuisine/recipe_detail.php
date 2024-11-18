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

// Vérifiez si le terme de recherche est passé dans l'URL
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // Récupérer les détails de la recette par nom
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
        WHERE r.nom LIKE :searchTerm
        GROUP BY r.id_recette
        LIMIT 1"; // Limiter à une seule recette pour la redirection
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    $stmt->execute();
    $recette = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recette) {
        echo "Recette introuvable.";
        exit;
    }
} elseif (isset($_GET['id'])) {
    $recette_id = $_GET['id'];

    // Récupérer les détails de la recette par ID
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
} else {
    echo "Aucune recette spécifiée.";
    exit;
}

// Traitement du formulaire de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text_commentaire'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $text_commentaire = htmlspecialchars($_POST['text_commentaire']);
    $date_com = date('Y-m-d H:i:s'); // Date actuelle
    $id_recette = $recette['id_recette']; // ID de la recette
    $id_utilisateur = 1; // Remplacez par l'ID de l'utilisateur connecté

    // Insérer le commentaire dans la base de données
    $insertCommentQuery = "INSERT INTO commentaires (nom, text_commentaire, date_com, id_recette, id_utilisateur) VALUES (:nom, :text_commentaire, :date_com, :id_recette, :id_utilisateur)";
    $stmtInsertComment = $pdo->prepare($insertCommentQuery);
    $stmtInsertComment->bindParam(':nom', $nom);
    $stmtInsertComment->bindParam(':text_commentaire', $text_commentaire);
    $stmtInsertComment->bindParam(':date_com', $date_com);
    $stmtInsertComment->bindParam(':id_recette', $id_recette);
    $stmtInsertComment->bindParam(':id_utilisateur', $id_utilisateur); // Remplacez par l'ID de l'utilisateur connecté
    $stmtInsertComment->execute();
}

// Traitement du formulaire d'évaluation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note'])) {
    $note = intval($_POST['note']);
    $id_utilisateur = 1; // Remplacez par l'ID de l'utilisateur connecté

    // Insérer l'évaluation dans la base de données
    $insertEvaluationQuery = "INSERT INTO evaluations (id_recette, id_utilisateur, note) VALUES (:id_recette, :id_utilisateur, :note)";
    $stmtInsertEvaluation = $pdo->prepare($insertEvaluationQuery);
    $stmtInsertEvaluation->bindParam(':id_recette', $id_recette);
    $stmtInsertEvaluation->bindParam(':id_utilisateur', $id_utilisateur);
    $stmtInsertEvaluation->bindParam(':note', $note);
    $stmtInsertEvaluation->execute();
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
            margin-bottom: 20px;
            margin-top: 20px;
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
            font-size: 1.5em;
            color: #555;
            margin-top: 20px;
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
            padding: 10px;
            margin-bottom: 10px;
        }

        .back-button {
            display: inline-block;
            background-color: #ffcc00;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #e6b800;
        }

        /* Styles pour les formulaires */
form {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
    margin-top: 20px;
}

form input[type="text"],
form textarea,
form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
}

form textarea {
    height: 100px; /* Hauteur spécifique pour le champ de texte */
}

/* Boutons pour les formulaires */
form button {
    background-color: #ffcc00;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s;
}

form button:hover {
    background-color: #e6b800;
}

/* Styles pour les sections de commentaires et d'évaluations */
h3.section-title {
    margin-top: 30px;
    color: #333;
}

.ingredient-list {
    margin-top: 10px;
}

.ingredient-list li {
    background-color: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
}

/* Styles pour les messages d'erreur ou d'information */
.error-message {
    color: red;
    font-weight: bold;
}

.success-message {
    color: green;
    font-weight: bold;
}
    </style>
</head>

<body>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="recettes.php">Recettes</a></li>
            <li><a href="types_aliments.php">Types d'Aliments</a>
                <ul class="dropdown">
                    <li><a href="recettes.php?type=cereal">Céréale</a></li>
                    <li><a href="recettes.php?type=condiment">Condiment</a></li>
                    <li><a href="recettes.php?type=epice">Épice</a></li>
                    <li><a href="recettes.php?type=fruit">Fruit</a></li>
                    <li><a href="recettes.php?type=graisse">Graisse</a></li>
                    <li><a href="recettes.php?type=herbes">Herbes et Plantes Aromatiques</a></li>
                    <li><a href="recettes.php?type=legume">Légume</a></li>
                    <li><a href="recettes.php?type=noix">Noix et Graines</a></li>
                    <li><a href="recettes.php?type=produit_laitier">Produit laitier</a></li>
                    <li><a href="recettes.php?type=produit_boulangerie">Produits de Boulangerie et Pâtisserie</a></li>
                    <li><a href="recettes.php?type=produit_snack">Produits de Snack</a></li>
                    <li><a href="recettes.php?type=proteine">Protéine</a></li>
                    <li><a href="recettes.php?type=sucre">Sucre</a></li>
                    <li><a href="recettes.php?type=viande">Viande et Poisson</a></li>
                </ul>
            </li>
            <li><a href="regimes.php">Régimes Alimentaires</a>
                <ul class="dropdown">
                    <li><a href="recettes.php?regime=sans_gluten">Sans gluten</a></li>
                    <li><a href="recettes.php?regime=toute_alimentation">Toute alimentation</a></li>
                    <li><a href="recettes.php?regime=vegetalien">Végétalien</a></li>
                    <li><a href="recettes.php?regime=vegetarien">Végétarien</a></li>
                </ul>
            </li>
            <li><a href="#">Connexion</a>
                <ul class="dropdown">
                    <li><a href="login.php">Connexion</a></li>
                    <li><a href="register.php">Inscription</a></li>
                    <li><a href="admin/index.php">Connexion admin</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div class="recipe-details">
        <h2 class="recipe-title"><?php echo htmlspecialchars($recette['nom']); ?></h2>
        <img class="recipe-image" src="<?php echo htmlspecialchars($recette['photo_chemin']); ?>" alt="<?php echo htmlspecialchars($recette['nom']); ?>" width="200">
        <p><strong>Difficulté :</strong> <?php echo htmlspecialchars($recette['difficulte']); ?></p>
        <p><strong>Catégorie :</strong> <?php echo htmlspecialchars($recette['categorie']); ?></p>
        <p><strong>Temps de préparation :</strong> <?php echo isset($recette['temps_preparation']) && $recette['temps_preparation'] !== '' ? htmlspecialchars($recette['temps_preparation']) . ' minutes' : 'Non spécifié'; ?></p>
        <p><strong>Temps de cuisson :</strong> <?php echo isset($recette['temps_cuisson']) && $recette['temps_cuisson'] > 0 ? htmlspecialchars($recette['temps_cuisson']) . ' minutes' : 'Pas de cuisson'; ?></p>

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
            $instructions = $recette['instructions'];
            if (strpos($instructions, '<br>') !== false) {
                $instructionsArray = explode('<br>', $instructions);
            } else {
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
        <p class="para">
            <?php
            $histoires = explode('<br>', $recette['histoires']);
            foreach ($histoires as $histoire): ?>
                <span><?php echo htmlspecialchars($histoire); ?></span><br>
            <?php endforeach; ?>
        </p>

        <h3 class="section-title">Différences :</h3>
        <p class="para">
            <?php
            $differences = explode('<br>', $recette['differences']);
            foreach ($differences as $difference): ?>
                <span><?php echo htmlspecialchars($difference); ?></span><br>
            <?php endforeach; ?>
        </p>

        <h3 class="section-title">Évaluation :</h3>
        <p class="para">Note moyenne : <?php echo isset($recette['note_moyenne']) ? htmlspecialchars($recette['note_moyenne']) : 'Aucune évaluation'; ?></p>

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

        <h3 class="section-title">Ajouter un commentaire :</h3>
        <form action="" method="post">
            <input type="text" name="nom" placeholder="Votre nom" required>
            <textarea name="text_commentaire" placeholder="Votre commentaire" required></textarea>
            <button type="submit">Envoyer</button>
        </form>

        <h3 class="section-title">Évaluer la recette :</h3>
        <form action="" method="post">
            <label for="note">Note (1 à 5) :</label>
            <select name="note" id="note" required>
                <option value="">Sélectionnez une note</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="submit">Évaluer</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="recettes.php" class="back-button">Retour aux Recettes</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>
</body>

</html>