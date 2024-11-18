<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'recette_melanie';
$username = 'root'; // Remplacez par votre nom d'utilisateur
$password = ''; // Remplacez par votre mot de passe

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion: " . $e->getMessage();
    exit;
}

// Vérifiez si le paramètre 'regime' est passé dans l'URL
if (isset($_GET['regime'])) {
    $regimeId = intval($_GET['regime']);

    // Récupérer le nom du régime
    $stmt = $pdo->prepare("SELECT nom FROM regimes WHERE id_regime = ?");
    $stmt->execute([$regimeId]);
    $regime = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifiez si le régime existe
    if ($regime) {
        $regimeName = $regime['nom'];
    } else {
        $regimeName = null; // Pas de régime trouvé
    }
} else {
    $regimeName = null; // Pas de régime spécifié
}

// Récupérer les recettes associées au régime
$recettesParCategorie = [];
if (isset($regimeId)) {
    $stmt = $pdo->prepare("SELECT r.*, 
        p.chemin AS image_url, 
        c.nom AS categorie_nom, 
        d.nom AS difficulte, 
        a.pays_origine AS origine,
        AVG(e.note) AS moyenne_note 
    FROM recettes r
    JOIN categories c ON r.id_catg = c.id_catg
    JOIN difficultes d ON r.id_difficulte = d.id_difficulte
    LEFT JOIN appellations a ON r.id_recette = a.id_recette
    LEFT JOIN photos p ON r.id_recette = p.id_recette
    JOIN recettes_regimes rr ON r.id_recette = rr.id_recette
    LEFT JOIN evaluations e ON r.id_recette = e.id_recette
    WHERE rr.id_regime = ?
    GROUP BY r.id_recette
    ORDER BY c.id_catg, r.nom");
    $stmt->execute([$regimeId]);
    $recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Grouper les recettes par catégorie
    foreach ($recettes as $recette) {
        $recettesParCategorie[$recette['categorie_nom']][] = $recette;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes par Régime Alimentaire</title>
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

    <h1 class="main-title"><?php echo isset($regimeName) ? htmlspecialchars($regimeName) : 'Régime Alimentaire'; ?></h1>

    <div class="recettes-container"> <!-- Conteneur pour les recettes -->
        <div class="recettes-list">
            <?php
            $current_category = '';
            foreach ($recettes as $recette):
                // Vérifier si la catégorie a changé
                if ($current_category != (isset($recette['categorie_nom']) ? $recette['categorie_nom'] : '')):
                    if ($current_category != ''): ?>
                        </div> <!-- Fin de la liste précédente -->
                    <?php endif; ?>
                    <h2><?php echo htmlspecialchars($current_category = isset($recette['categorie_nom']) ? $recette['categorie_nom'] : 'Non spécifiée'); ?></h2>
                    <div class="recettes-category">
                <?php endif; ?>
                <div class="recette-card"> <!-- Carte de recette -->
                    <img src="<?php echo isset($recette['image_url']) ? htmlspecialchars($recette['image_url']) : 'path/to/default/image.jpg'; ?>" alt="<?php echo htmlspecialchars(isset($recette['nom']) ? $recette['nom'] : 'Recette sans nom'); ?>" class="recette-image">
                    <h3 class="recette-title"><?php echo htmlspecialchars(isset($recette['nom']) ? $recette['nom'] : 'Nom non disponible'); ?></h3>
                    <p class="recette-description"><strong>Difficulté :</strong> <?php echo htmlspecialchars(isset($recette['difficulte']) ? $recette['difficulte'] : 'Non spécifiée'); ?></p>
                    <p class="recette-description"><strong>Origine :</strong> <?php echo htmlspecialchars(isset($recette['origine']) ? $recette['origine'] : 'Non spécifiée'); ?></p>
                    <p class="recette-description"><strong>Moyenne des évaluations :</strong> <?php echo htmlspecialchars(isset($recette['moyenne_note']) ? number_format($recette['moyenne_note'], 1) : 'Pas d\'évaluation'); ?></p>
                    <a href="recipe_detail.php?id=<?php echo isset($recette['id_recette']) ? $recette['id_recette'] : ''; ?>" class="view-recipe-button">Voir la recette</a>
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