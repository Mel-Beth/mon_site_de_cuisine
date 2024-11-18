<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuration de la base de données
$dsn = 'mysql:host=localhost;dbname=recette_melanie;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    // Vérifier les informations d'identification
    $sql = "SELECT id_utilisateur, pseudo, mdp, id_role FROM utilisateurs WHERE pseudo = :pseudo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pseudo', $pseudo);

    try {
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier le mot de passe
            if ($mdp === $user['mdp']) { // À remplacer par password_verify si vous utilisez un hachage
                // Authentification réussie
                $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
                $_SESSION['pseudo'] = $user['pseudo'];
                $_SESSION['id_role'] = $user['id_role'];
                header("Location: profile.php"); // Redirige vers le profil
                exit;
            } else {
                $error_message = "Identifiants incorrects.";
            }
        } else {
            $error_message = "Aucun utilisateur trouvé avec ce pseudo.";
        }
    } catch (PDOException $e) {
        $error_message = "Erreur lors de la préparation ou de l'exécution de la requête : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="title">Connexion</h1>
        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <label for="pseudo">Pseudo :</label>
                <input type="text" name="pseudo" required>
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe :</label>
                <input type="password" name="mdp" required>
            </div>
            <button type="submit" class="submit-button">Se connecter</button>
            <button type="button" class="submit-button" onclick="redirectToRegister();">S'inscrire</button>
        </form>

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