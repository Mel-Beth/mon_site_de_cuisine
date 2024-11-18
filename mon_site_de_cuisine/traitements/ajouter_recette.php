<?php
// Code pour traiter l'ajout de recette
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_recette = $_POST['nom_recette'];
    $image_recette = $_FILES['image_recette']['name'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];

    // Code pour sauvegarder la recette dans la base de données

    // Rediriger vers la page de gestion des recettes
    header("Location: manage_recipes.php");
    exit();
}
?>