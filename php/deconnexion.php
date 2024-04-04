<?php
/**
 * Script de déconnexion de l'utilisateur.
 *
 * Ce script sert à déconnecter l'utilisateur en détruisant la session en cours
 * et en redirigeant l'utilisateur vers la page d'accueil.
 */

// Initialiser la session
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil
header("location: ../pages/index.php");
exit;
?>