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
$pseudo = trim($_POST['pseudo']);
$mdp = trim($_POST['mdp']);
$utilisateur_id = $_SESSION['id_utilisateur'];

try {
    // Préparer la requête de mise à jour
    $query = "UPDATE utilisateurs SET pseudo = :pseudo, mdp = :mdp WHERE id_utilisateur = :id_utilisateur";
    $stmt = $pdo->prepare($query);

    // Lier les paramètres
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':mdp', $mdp); // Utilisez le mot de passe en clair
    $stmt->bindParam(':id_utilisateur', $utilisateur_id, PDO::PARAM_INT);
    
    // Exécuter la requête
    $stmt->execute();

    // Après la mise à jour réussie
    $_SESSION['message'] = "Profil mis à jour avec succès.";
    header("Location: ../profile.php");
    exit;

} catch (PDOException $e) {
    echo "Erreur lors de la mise à jour: " . htmlspecialchars($e->getMessage());
    exit;
}
?>