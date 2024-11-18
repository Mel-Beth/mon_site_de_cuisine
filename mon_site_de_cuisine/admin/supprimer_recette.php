<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: login.php');
    exit;
}

require '../data/db.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'ID de la recette est passé dans l'URL
if (!isset($_GET['id'])) {
    header('Location: manage_recipes.php');
    exit;
}

$id_recette = intval($_GET['id']);

// Supprimer la recette
$stmt = $pdo->prepare("DELETE FROM recettes WHERE id_recette = ?");
$stmt->execute([$id_recette]);

header('Location: manage_recipes.php');
exit;