<?php
// Code pour traiter la suppression d'un utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Code pour supprimer l'utilisateur de la base de données
    // Exemple :
    // $query = "DELETE FROM utilisateurs WHERE id = $id";
    // Exécutez la requête avec votre connexion à la base de données

    // Rediriger vers la page de gestion des utilisateurs
    header("Location: manage_users.php");
    exit();
}
?>