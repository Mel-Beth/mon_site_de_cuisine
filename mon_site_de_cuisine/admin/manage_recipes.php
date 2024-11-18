<?php
session_start();

require '../data/db.php'; // Inclure le fichier de connexion à la base de données

// Initialiser la variable $error
$error = ''; // Ajoutez cette ligne pour éviter l'erreur d'undefined variable

// Récupérer les recettes
$stmt = $pdo->query("SELECT * FROM recettes");
$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Supprimer une recette
if (isset($_GET['delete'])) {
    $id_recette = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM recettes WHERE id_recette = ?");
    $stmt->execute([$id_recette]);
    header('Location: manage_recipes.php');
    exit;
}

// Ajouter une recette (traitement du formulaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_recipe'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $id_difficulte = intval($_POST['id_difficulte']);
    $id_catg = intval($_POST['id_catg']);

    // Vous pourriez ajouter une vérification ici pour gérer les erreurs
    if (empty($nom) || empty($id_difficulte) || empty($id_catg)) {
        $error = "Tous les champs sont requis.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO recettes (nom, id_difficulte, id_catg) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $id_difficulte, $id_catg]);
        header('Location: manage_recipes.php');
        exit;
    }
}
?>

<!-- Affichage des messages d'erreur ou de succès -->
<?php if ($error): ?>
    <div class="error-message"><?php echo $error; ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['message'])): ?>
    <div class="success-message"><?php echo $_SESSION['message'];
                                    unset($_SESSION['message']); ?></div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Recettes</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function confirmDelete() {
            return confirm('Êtes-vous sûr de vouloir supprimer cette recette ?');
        }
    </script>
    <style>

        /* Styles pour le tableau des recettes */
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

        /* Styles pour le formulaire d'ajout de recette */
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

        form label {
            display: block;
            /* Affichage en bloc pour les labels */
            margin-bottom: 5px;
            /* Ajout d'une marge inférieure pour les labels */
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form textarea {
            width: 100%;
            padding: 12px;
            /* Augmenté le padding pour plus d'espace à l'intérieur */
            margin-bottom: 15px;
            /* Ajout d'une marge inférieure */
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        form textarea {
            height: 100px;
            /* Hauteur spécifique pour le champ de texte */
        }

        /* Boutons pour les formulaires */
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

        /* Styles pour les instructions dynamiques */
        .instructions-container {
            margin-top: 15px;
            /* Ajout d'une marge supérieure */
        }

        /* Styles pour les instructions ajoutées */
        .instruction {
            margin-bottom: 10px;
            /* Ajout d'une marge inférieure pour les instructions */
        }

        /* Styles pour les messages d'erreur ou d'information dans le formulaire */
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
            <li><a href="dashboard.php">Accueil</a></li>
            <li><a href="manage_recipes.php">Gérer les Recettes</a></li>
            <li><a href="manage_users.php">Gérer les Utilisateurs</a></li>
            <li><a href="../logout.php">Déconnexion</a></li>
        </ul>
    </nav>

    <h1 class="main-title">Gérer les Recettes</h1>

    <h2>Liste des Recettes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Difficulté</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recettes as $recette): ?>
                <tr>
                    <td><?php echo htmlspecialchars($recette['id_recette']); ?></td>
                    <td><?php echo htmlspecialchars($recette['nom']); ?></td>
                    <td>
                        <?php
                        // Afficher le nom de la difficulté
                        switch ($recette['id_difficulte']) {
                            case 1:
                                echo "Facile";
                                break;
                            case 2:
                                echo "Moyen";
                                break;
                            case 3:
                                echo "Difficile";
                                break;
                            default:
                                echo "Inconnu";
                                break;
                        }
                        ?>
                    <td>
                        <?php
                        // Afficher le nom de la catégorie
                        switch ($recette['id_catg']) {
                            case 1:
                                echo "Entrées";
                                break;
                            case 2:
                                echo "Plats";
                                break;
                            case 3:
                                echo "Desserts";
                                break;
                            case 4:
                                echo "Soupes";
                                break;
                            default:
                                echo "Inconnu";
                                break;
                        }
                        ?>
                    </td>
                    <td>
                        <a href="modifier_recette.php?id=<?php echo $recette['id_recette']; ?>">Modifier</a>
                        <a href="?delete=<?php echo $recette['id_recette']; ?>" onclick="return confirmDelete();">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Ajouter une Nouvelle Recette</h2>

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
            <div class="instruction">
                <input type="text" name="instructions[]" required>
                <button type="button" class="remove-instruction">Supprimer</button>
            </div>
        </div>
        <button type="button" id="addInstruction">Ajouter une instruction</button>

        <label for="appellations">Appellations :</label>
        <input type="text" id="appellations" name="appellations" placeholder="Ex: Boulettes de Viande à la Sauce Tomate">

        <label for="histoires">Histoires :</label>
        <textarea id="histoires" name="histoires" rows="2" placeholder="Partagez l'histoire de ce plat..."></textarea>

        <button type="submit" name="add_recipe">Ajouter la recette</button>
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