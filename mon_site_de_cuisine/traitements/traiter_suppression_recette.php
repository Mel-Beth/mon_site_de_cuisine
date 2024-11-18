<?php
// Code pour traiter la suppression d'une recette
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Code pour supprimer la recette de la base de données
    // Exemple :
    // $query = "DELETE FROM recettes WHERE id = $id";
    // Exécutez la requête avec votre connexion à la base de données

    // Rediriger vers la page de gestion des recettes
    header("Location: manage_recipes.php");
    exit();
}
?>