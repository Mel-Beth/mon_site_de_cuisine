<?php
session_start(); // Démarrer la session

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

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ../login.php"); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

// Récupérer les données du formulaire
$id_commentaire = $_POST['id_commentaire']; // Renommez cette variable si nécessaire
$text_commentaire = trim($_POST['text_commentaire']);

try {
    // Préparer la requête de mise à jour
    $query = "UPDATE commentaires SET text_commentaire = :text_commentaire WHERE id_com = :id_commentaire";
    $stmt = $pdo->prepare($query);

    // Lier les paramètres
    $stmt->bindParam(':text_commentaire', $text_commentaire);
    $stmt->bindParam(':id_commentaire', $id_commentaire, PDO::PARAM_INT);
    
    // Exécuter la requête
    $stmt->execute();
    
    // Redirection vers le profil avec un message de succès
    $_SESSION['message'] = "Commentaire mis à jour avec succès.";
    header("Location: ../profile.php");
    exit;
} catch (PDOException $e) {
    // Gérer les erreurs
    echo "Erreur lors de la mise à jour : " . htmlspecialchars($e->getMessage());
    exit;
}
?>