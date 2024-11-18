<?php
session_start();

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

$error_message = ""; // Variable pour stocker les messages d'erreur
$suggestion = ""; // Variable pour stocker la suggestion de pseudo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    // Vérifier si le mot de passe est vide
    if (empty($mdp)) {
        $error_message = "Le mot de passe est vide !";
    } else {
        // Vérifier si le pseudo existe déjà
        $sql_check = "SELECT * FROM utilisateurs WHERE pseudo = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(1, $pseudo);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($result_check) {
            // Générer une suggestion de pseudo
            $base_pseudo = $pseudo;
            $i = 1;
            while ($result_check) {
                $suggestion = $base_pseudo . $i; // Ajoute un suffixe numérique
                $stmt_check->bindParam(1, $suggestion);
                $stmt_check->execute();
                $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
                $i++;
            }
            $error_message = "Le pseudo est déjà pris. Peut-être essayer : " . htmlspecialchars($suggestion);
        } else {
            $id_role = 3; // Rôle par défaut : Utilisateur

            // Insérer l'utilisateur dans la base de données
            $sql = "INSERT INTO utilisateurs (pseudo, mdp, id_role) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(1, $pseudo);
            $stmt->bindParam(2, $mdp);
            $stmt->bindParam(3, $id_role);

            if ($stmt->execute()) {
                // Récupérer l'ID de l'utilisateur nouvellement créé
                $userId = $pdo->lastInsertId();
                $_SESSION['id_utilisateur'] = $userId; // Stocker l'ID de l'utilisateur dans la session

                // Redirection vers la page de profil
                header("Location: profile.php");
                exit;
            } else {
                $error_message = "Erreur lors de l'inscription : " . $stmt->errorInfo()[2]; // Afficher l'erreur
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <h1 class="title">Inscription</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="pseudo">Pseudo :</label>
                <input type="text" name="pseudo" required>
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe :</label>
                <input type="password" name="mdp" required>
            </div>
            <button type="submit" class="submit-button">S'inscrire</button>
        </form>
        <button type="button" class="submit-button" onclick="window.location.href='login.php';">Se connecter</button>

        <?php if (!empty($error_message)): ?>
            <div class="error-message" style="color: red; text-align: center; margin-top: 15px;">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Mon Site de Cuisine. Tous droits réservés.</p>
    </footer>

</body>

</html>