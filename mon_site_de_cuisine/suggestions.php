<?php
session_start();
$host = 'localhost';
$dbname = 'recette_melanie';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion: " . htmlspecialchars($e->getMessage());
    exit;
}

// Vérifiez si le terme de recherche est passé
if (isset($_GET['term'])) {
    $term = $_GET['term'];

    // Récupérer les recettes correspondantes
    $query = "SELECT nom FROM recettes WHERE nom LIKE :term LIMIT 5";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':term', '%' . $term . '%', PDO::PARAM_STR);
    $stmt->execute();
    
    $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retourner les suggestions au format JSON
    echo json_encode($suggestions);
}
?>