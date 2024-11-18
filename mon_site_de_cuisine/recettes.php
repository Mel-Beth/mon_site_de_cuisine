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

// Récupérer la catégorie depuis l'URL
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : '';

// Récupérer toutes les recettes avec leurs détails
$query = "
    SELECT r.*, c.nom AS categorie, d.nom AS difficulte, 
           a.nom_local AS nom_recette,
           a.pays_origine AS origine,
           p.chemin AS image_url,
           GROUP_CONCAT(DISTINCT i.nom SEPARATOR ', ') AS ingredients,
           GROUP_CONCAT(DISTINCT ins.description ORDER BY ins.ordre SEPARATOR '<br>') AS instructions,
           AVG(e.note) AS moyenne_note
    FROM recettes r
    JOIN categories c ON r.id_catg = c.id_catg
    JOIN difficultes d ON r.id_difficulte = d.id_difficulte
    LEFT JOIN appellations a ON r.id_recette = a.id_recette
    LEFT JOIN photos p ON r.id_recette = p.id_recette
    LEFT JOIN ingredients_recettes ir ON r.id_recette = ir.id_recette
    LEFT JOIN ingredients i ON ir.id_ingredient = i.id_ingredient
    LEFT JOIN instructions ins ON r.id_recette = ins.id_recette
    LEFT JOIN evaluations e ON r.id_recette = e.id_recette
";

// Si une catégorie est spécifiée, filtrer les recettes par cette catégorie
if ($categorie) {
    $query .= " WHERE c.nom = :categorie";
}

$query .= " GROUP BY r.id_recette
            ORDER BY c.id_catg, nom_recette"; // Tri par id_catg et ensuite par nom de recette

$stmt = $pdo->prepare($query);

// Lier le paramètre de catégorie si nécessaire
if ($categorie) {
    $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
}

$stmt->execute();
$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Recettes</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="recettes.php">Recettes</a></li>
            <li><a href="types_aliments.php">Types d'Aliments</a>
                <ul class="dropdown">
                    <li><a href="recettes_aliments.php?type=1">Légume</a></li> <!-- ID 1 -->
                    <li><a href="recettes_aliments.php?type=2">Fruit</a></li> <!-- ID 2 -->
                    <li><a href="recettes_aliments.php?type=3">Céréale</a></li> <!-- ID 3 -->
                    <li><a href="recettes_aliments.php?type=4">Protéine</a></li> <!-- ID 4 -->
                    <li><a href="recettes_aliments.php?type=5">Produit laitier</a></li> <!-- ID 5 -->
                    <li><a href="recettes_aliments.php?type=6">Épice</a></li> <!-- ID 6 -->
                    <li><a href="recettes_aliments.php?type=7">Graisse</a></li> <!-- ID 7 -->
                    <li><a href="recettes_aliments.php?type=8">Sucre</a></li> <!-- ID 8 -->
                    <li><a href="recettes_aliments.php?type=9">Viande et Poisson</a></li> <!-- ID 9 -->
                    <li><a href="recettes_aliments.php?type=10">Condiment</a></li> <!-- ID 10 -->
                    <li><a href="recettes_aliments.php?type=11">Produits de Boulangerie et Pâtisserie</a></li> <!-- ID 11 -->
                    <li><a href="recettes_aliments.php?type=12">Herbes et Plantes Aromatiques</a></li> <!-- ID 12 -->
                    <li><a href="recettes_aliments.php?type=13">Noix et Graines</a></li> <!-- ID 13 -->
                    <li><a href="recettes_aliments.php?type=14">Produits de Snack</a></li> <!-- ID 14 -->
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

    <h1 class="main-title">Recettes <?php echo htmlspecialchars($categorie); ?></h1>

    <div class="recettes-container"> <!-- Conteneur pour les recettes -->
        <div class="recettes-list">
            <?php
            $current_category = '';
            foreach ($recettes as $recette):
                // Vérifier si la catégorie a changé
                if ($current_category != $recette['categorie']):
                    if ($current_category != ''): ?>
        </div> <!-- Fin de la liste précédente -->
        <?php endif; ?>
        <h2><?php echo htmlspecialchars($recette['categorie']); ?></h2>
        <div class="recettes-category">
        <?php
                        $current_category = $recette['categorie'];
                    endif; ?>
        <div class="recette-card"> <!-- Carte de recette -->
            <img src="<?php echo isset($recette['image_url']) ? htmlspecialchars($recette['image_url']) : 'path/to/default/image.jpg'; ?>" alt="<?php echo htmlspecialchars($recette['nom_recette']); ?>" class="recette-image">
            <h3 class="recette-title"><?php echo htmlspecialchars($recette['nom_recette']); ?></h3>
            <p class="recette-description"><strong>Difficulté :</strong> <?php echo htmlspecialchars($recette['difficulte']); ?></p>
            <p class="recette-description"><strong>Origine :</strong> <?php echo htmlspecialchars($recette['origine']); ?></p>
            <p class="recette-description"><strong>Moyenne des évaluations :</strong> <?php echo htmlspecialchars($recette['moyenne_note'] ? number_format($recette['moyenne_note'], 1) : 'Pas d\'évaluation'); ?></p>
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