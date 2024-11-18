<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrateur') {
    header('Location: login.php');
    exit;
}

require '../data/db.php'; // Inclure le fichier de connexion à la base de données

// Vérifier si l'ID de l'utilisateur est passé dans l'URL
if (!isset($_GET['id'])) {
    header('Location: manage_users.php');
    exit;
}

$id_utilisateur = intval($_GET['id']);

// Supprimer l'utilisateur
$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);

header('Location: manage_users.php');
exit;