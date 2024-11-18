<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: index.php"); // Rediriger vers la page de connexion si non connecté
    exit;
}

require '../data/db.php'; // Inclure le fichier de connexion à la base de données

// Récupérer les statistiques
$stmt = $pdo->query("SELECT COUNT(*) as total_recipes FROM recettes");
$total_recipes = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM utilisateurs");
$total_users = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) as total_comments FROM commentaires");
$total_comments = $stmt->fetchColumn();

// Récupérer les derniers utilisateurs
$stmt = $pdo->query("SELECT pseudo FROM utilisateurs ORDER BY date_inscription DESC LIMIT 5");
$last_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les dernières recettes
$stmt = $pdo->query("SELECT nom FROM recettes ORDER BY id_recette DESC LIMIT 5");
$last_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre total d'évaluations
$stmt = $pdo->query("SELECT COUNT(*) FROM evaluations");
$total_evaluations = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Styles pour le bloc de statistiques administratives */
        .admin-stats {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            width: 80%;
        }

        .admin-stats h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .admin-stats p {
            font-size: 1.2em;
            margin: 10px 0;
            color: #555;
        }

        .recent-activities {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            width: 80%;
        }

        .recent-activities h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .recent-activities ul {
            list-style-type: none;
            padding: 0;
        }

        .recent-activities li {
            margin: 5px 0;
            color: #555;
        }

        #myChart {
            background-color: #ffffff;
            /* Couleur de fond du canvas */
            border-radius: 8px;
            /* Coins arrondis */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Ombre légère */
            margin: 20px auto;
            /* Centrer le canvas */
            display: block;
            /* S'assurer que le canvas est un bloc */
            max-width: 90%;
            /* Limiter la largeur maximale */
            height: auto;
            /* Hauteur automatique pour garder le ratio */
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
    <h1 class="main-title">Tableau de Bord Administrateur</h1>

    <h2>Statistiques du Site</h2>

    <div class="admin-stats">

        <p>Nombre total de recettes : <?php echo $total_recipes; ?></p>
        <p>Nombre total d'utilisateurs : <?php echo $total_users; ?></p>
        <p>Nombre total de commentaires : <?php echo $total_comments; ?></p>
        <p>Nombre total d'évaluations : <?php echo $total_evaluations; ?></p> <!-- Ajouté -->
    </div>

    <canvas id="myChart" width="400" height="200"></canvas>

    <h2>Activités Récentes</h2>

    <div class="recent-activities">

        <h3>Derniers Utilisateurs :</h3>
        <ul>
            <?php foreach ($last_users as $user): ?>
                <li><?php echo htmlspecialchars($user['pseudo']); ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Dernières Recettes :</h3>
        <ul>
            <?php foreach ($last_recipes as $recipe): ?>
                <li><?php echo htmlspecialchars($recipe['nom']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');

        // Récupération des données PHP pour le graphique
        const data = [
            <?php echo $total_recipes; ?>,
            <?php echo $total_users; ?>,
            <?php echo $total_comments; ?>,
            <?php echo $total_evaluations; ?>
        ];

        const myChart = new Chart(ctx, {
            type: 'bar', // Type de graphique
            data: {
                labels: ['Recettes', 'Utilisateurs', 'Commentaires', 'Évaluations'], // Étiquettes pour l'axe des X
                datasets: [{
                    label: 'Statistiques du Site',
                    data: [<?php echo $total_recipes; ?>, <?php echo $total_users; ?>, <?php echo $total_comments; ?>, <?php echo $total_evaluations; ?>], // Données pour chaque étiquette
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Commencer l'axe Y à zéro
                    }
                }
            }
        });
    </script>

</body>

</html>