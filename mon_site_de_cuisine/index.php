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

// Récupérer les catégories triées par id_catg
$query_categories = "SELECT * FROM categories ORDER BY id_catg ASC";
$stmt_categories = $pdo->prepare($query_categories);
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les types d'aliments
$query_types_aliments = "SELECT * FROM types_aliments";
$stmt_types_aliments = $pdo->prepare($query_types_aliments);
$stmt_types_aliments->execute();
$types_aliments = $stmt_types_aliments->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les régimes alimentaires
$query_regimes = "SELECT * FROM regimes";
$stmt_regimes = $pdo->prepare($query_regimes);
$stmt_regimes->execute();
$regimes = $stmt_regimes->fetchAll(PDO::FETCH_ASSOC);

// Définir un tableau d'images pour chaque catégorie
$images = [
    'Entrées' => 'entrees.jpg',
    'Plats' => 'plats.jpeg',
    'Desserts' => 'desserts.jpg',
    'Soupes' => 'soupes.jpeg',
];

// Récupérer les recettes populaires triées par note moyenne
$recettesQuery = "
    SELECT r.*, AVG(e.note) AS moyenne_note, p.chemin AS chemin_image 
    FROM recettes r
    LEFT JOIN evaluations e ON r.id_recette = e.id_recette
    LEFT JOIN photos p ON r.id_recette = p.id_recette
    GROUP BY r.id_recette
    ORDER BY moyenne_note DESC
    LIMIT 5";
$recettesResult = $pdo->query($recettesQuery);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Site de Cuisine</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <nav class="main-nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="recettes.php">Recettes</a></li>
            <li><a href="#">Types d'Aliments</a>
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
            <li><a href="#">Régimes Alimentaires</a>
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

    <h1 class="main-title">Bienvenue sur Mon Site de Cuisine</h1>

    <section class="presentation">
        <p>Mon Site de Cuisine est votre destination incontournable pour découvrir des recettes délicieuses et faciles à réaliser. Que vous soyez un cuisinier débutant ou un chef expérimenté, vous trouverez ici des idées pour toutes les occasions.</p>
    </section>

    <div class="search-bar">
        <form action="recipe_detail.php" method="get" id="search-form">
            <input type="text" name="search" id="search-input" placeholder="Rechercher une recette..." required>
            <button type="submit">Rechercher</button>
        </form>
        <div id="suggestions" class="suggestions-box"></div>
    </div>

    <h2>Catégories</h2>
    <div class="categories">
        <?php foreach ($categories as $category): ?>
            <div class="card">
                <div class="card-content">
                    <img src="images/<?php echo isset($images[$category['nom']]) ? htmlspecialchars($images[$category['nom']]) : 'default.jpg'; ?>" alt="<?php echo htmlspecialchars($category['nom']); ?>" class="card-image">
                    <div class="card-text">
                        <h3><?php echo htmlspecialchars($category['nom']); ?></h3>
                        <p>Découvrez nos recettes dans cette catégorie.</p>
                        <a href="recettes.php?categorie=<?php echo urlencode($category['nom']); ?>" class="card-link">Voir les recettes</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Recettes Populaires</h2>
    <div class="popular-recipes">
        <?php while ($recette = $recettesResult->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="card">
                <a href="recipe_detail.php?id=<?= $recette['id_recette'] ?>" class="card-lien">
                    <img src="<?= htmlspecialchars($recette['chemin_image']) ?>" alt="<?= htmlspecialchars($recette['nom']) ?>" class="card-image">
                    <div class="card-text">
                        <h3><?= htmlspecialchars($recette['nom']) ?></h3>
                        <p>(Note : <?= number_format($recette['moyenne_note'], 1) ?>)</p>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>

    <script src="js/script.js"></script>
</body>

</html>