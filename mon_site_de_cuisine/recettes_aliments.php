<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'recette_melanie';
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = ''; // Remplacez par votre mot de passe

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion: " . $e->getMessage();
    exit;
}

// Récupérer le type depuis l'URL
$typeId = isset($_GET['type']) ? (int)$_GET['type'] : null;

// Récupérer le nom du type d'aliment
$typeName = '';
if ($typeId) {
    $stmt = $pdo->prepare("SELECT nom_aliment FROM types_aliments WHERE id_aliment = :id");
    $stmt->execute(['id' => $typeId]);
    $typeName = $stmt->fetchColumn(); // Récupérer le nom
}

// Construire la requête SQL
$query = "
    SELECT r.id_recette, r.nom AS nom_recette, r.id_difficulte, c.nom AS nom_categorie, AVG(e.note) AS moyenne_note, p.chemin AS chemin_image 
    FROM recettes r
    LEFT JOIN evaluations e ON r.id_recette = e.id_recette
    LEFT JOIN photos p ON r.id_recette = p.id_recette
    LEFT JOIN categories c ON r.id_catg = c.id_catg
    LEFT JOIN ingredients_recettes ir ON r.id_recette = ir.id_recette
    LEFT JOIN ingredients i ON ir.id_ingredient = i.id_ingredient
    LEFT JOIN ingredients_types_aliments ita ON i.id_ingredient = ita.id_ingredient";

// Filtrer par type si spécifié
if ($typeId) {
    $query .= " WHERE ita.id_aliment = :type";
}

$query .= " GROUP BY r.id_recette ORDER BY c.nom, moyenne_note DESC"; // Grouper par catégorie

// Préparer la requête
$stmt = $pdo->prepare($query);

// Lier le paramètre si nécessaire
if ($typeId) {
    $stmt->bindParam(':type', $typeId, PDO::PARAM_INT);
}

// Exécuter la requête
$stmt->execute();
$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grouper les recettes par catégorie
$recettesParCategorie = [];
foreach ($recettes as $recette) {
    $recettesParCategorie[$recette['nom_categorie']][] = $recette;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes par Type d'Aliment</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="recettes.php">Recettes</a></li>
            <li><a href="types_aliments.php">Types d'Aliments</a>
                <ul class="dropdown">
                    <li><a href="recettes_aliments.php?type=1">Légume</a></li>
                    <li><a href="recettes_aliments.php?type=2">Fruit</a></li>
                    <li><a href="recettes_aliments.php?type=3">Céréale</a></li>
                    <li><a href="recettes_aliments.php?type=4">Protéine</a></li>
                    <li><a href="recettes_aliments.php?type=5">Produit laitier</a></li>
                    <li><a href="recettes_aliments.php?type=6">Épice</a></li>
                    <li><a href="recettes_aliments.php?type=7">Graisse</a></li>
                    <li><a href="recettes_aliments.php?type=8">Sucre</a></li>
                    <li><a href="recettes_aliments.php?type=9">Viande et Poisson</a></li>
                    <li><a href="recettes_aliments.php?type=10">Condiment</a></li>
                    <li><a href="recettes_aliments.php?type=11">Produits de Boulangerie et Pâtisserie</a></li>
                    <li><a href="recettes_aliments.php?type=12">Herbes et Plantes Aromatiques</a></li>
                    <li><a href="recettes_aliments.php?type=13">Noix et Graines</a></li>
                    <li><a href="recettes_aliments.php?type=14">Produits de Snack</a></li>
                </ul>
            </li>
            <li><a href="regimes.php">Régimes Alimentaires</a>
                <ul class="dropdown">
                    <li><a href="recettes_regimes.php?regime=1">Végétarien</a></li>
                    <li><a href="recettes_regimes.php?regime=2">Végétalien</a></li>
                    <li><a href="recettes_regimes.php?regime=3">Sans gluten</a></li>
                    <li><a href="recettes_regimes.php?regime=4">Toute alimentation</a></li>
                </ul>
            </li>
            <li><a href="#">Connexion</a>
                <ul class="dropdown">
                    <li><a href="login.php">Connexion</a></li>
                    <li><a href="admin/index.php">Connexion admin</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <h1 class="main-title"><?php echo $typeName ? htmlspecialchars($typeName) : 'Recettes par Type d\'Aliment'; ?></h1>

    <div class="recettes-container"> <!-- Conteneur pour les recettes -->
        <div class="recettes-list">
            <?php
            $current_category = '';
            foreach ($recettes as $recette):
                // Vérifier si la catégorie a changé
                if ($current_category != $recette['nom_categorie']):
                    if ($current_category != ''): ?>
                        </div> <!-- Fin de la liste précédente -->
                    <?php endif; ?>
                    <h2><?php echo htmlspecialchars($recette['nom_categorie']); ?></h2>
                    <div class="recettes-category">
                    <?php
                    $current_category = $recette['nom_categorie'];
                endif; ?>
                <div class="recette-card"> <!-- Carte de recette -->
                    <img src="<?php echo isset($recette['chemin_image']) ? htmlspecialchars($recette['chemin_image']) : 'path/to/default/image.jpg'; ?>" alt="<?php echo htmlspecialchars($recette['nom_recette']); ?>" class="recette-image">
                    <h3 class="recette-title"><?php echo htmlspecialchars($recette['nom_recette']); ?></h3>
                    <p class="recette-description"><strong>Difficulté :</strong> <?php echo isset($recette['id_difficulte']) ? htmlspecialchars($recette['id_difficulte']) : 'Non spécifiée'; ?></p>
                    <p class="recette-description"><strong>Moyenne des évaluations :</strong> <?php echo isset($recette['moyenne_note']) ? number_format($recette['moyenne_note'], 1) : 'Pas d\'évaluation'; ?></p>
                    <a href="recipe_detail.php?id=<?php echo $recette['id_recette']; ?>" class="view-recipe-button">Voir la recette</a>
                </div> <!-- Fin de la carte de recette -->
            <?php endforeach; ?>
            </div> <!-- Fin de la dernière liste de recettes -->
        </div> <!-- Fin de la catégorie actuelle -->
    </div> <!-- Fin du conteneur des recettes -->

    <footer class="recettes-footer"> <!-- Pied de page spécifique aux recettes -->
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>
</body>

</html>