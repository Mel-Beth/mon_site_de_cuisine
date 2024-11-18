<?php
// Code pour traiter la modification d'un utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];

    // Code pour mettre à jour l'utilisateur dans la base de données
    // Exemple :
    // $query = "UPDATE utilisateurs SET pseudo = '$pseudo', email = '$email' WHERE id = $id";
    // Exécutez la requête avec votre connexion à la base de données

    // Rediriger vers la page de gestion des utilisateurs
    header("Location: manage_users.php");
    exit();
}
?>