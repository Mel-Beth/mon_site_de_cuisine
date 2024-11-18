<?php
session_start();
include 'data/db.php'; // Inclure votre connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $utilisateur_id = $_SESSION['utilisateur_id'];

       // Traitement de l'image
       $image_path = '';
       if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
           $image_path = 'uploads/' . uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
           move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
       }
   
       // Insertion dans la base de données
       $query = "INSERT INTO recettes (titre, description, image_url, utilisateur_id) VALUES ('$titre', '$description', '$image_path', '$utilisateur_id')";
       mysqli_query($conn, $query);
   
       header("Location: index.php"); // Rediriger vers la page d'accueil
       exit();
   }
   ?>