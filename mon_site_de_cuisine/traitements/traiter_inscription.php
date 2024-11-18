<?php
// Code pour traiter l'inscription d'un nouvel utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hash le mot de passe

    // Code pour insérer l'utilisateur dans la base de données
    // Exemple :
    // $query = "INSERT INTO utilisateurs (pseudo, email, mot_de_passe) VALUES ('$pseudo', '$email', '$mot_de_passe')";
    // Exécutez la requête avec votre connexion à la base de données

    // Rediriger vers la page de connexion après l'inscription
    header("Location: login.php");
    exit();
}
?>