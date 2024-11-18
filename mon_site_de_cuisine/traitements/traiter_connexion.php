<?php
session_start();

// Code pour traiter la connexion de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Code pour récupérer l'utilisateur de la base de données
    // Exemple :
    // $query = "SELECT * FROM utilisateurs WHERE email = '$email'";
    // $result = exécutez la requête avec votre connexion à la base de données
    // $utilisateur = fetch_assoc($result);

    // Vérifiez si l'utilisateur existe et si le mot de passe est correct
    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        $_SESSION['utilisateur_id'] = $utilisateur['id']; // Enregistrez l'ID de l'utilisateur dans la session
        header("Location: index.php"); // Redirigez vers la page d'accueil
        exit();
    } else {
        // Gérer l'erreur de connexion
        echo "Identifiants incorrects. Veuillez réessayer.";
    }
}
?>