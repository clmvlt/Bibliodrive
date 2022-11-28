<?php
// Connexion au serveur
try {
    $dns = 'mysql:host=localhost;dbname=bibliodrive'; // dbname : nom de la base
    $utilisateur = 'root'; // root sur vos postes
    $motDePasse = ''; // pas de mot de passe sur vos postes
    $connexion = new PDO( $dns, $utilisateur, $motDePasse );
    // echo "Connexion réussie!!!!!!!!";
  } catch (Exception $e) {
    echo "Erreur de la connexion à la base de donnée : ", $e->getMessage();
    die();
  }
?>