<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Inclure la configuration de la base de données
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'ID du colis à supprimer
    $id_colis = $_POST['id_colis'];

    // Préparer et exécuter la requête de suppression
    $query = $conn->prepare("DELETE FROM Colis WHERE id_colis = :id_colis AND created_by = :user_id");
    $query->bindParam(':id_colis', $id_colis);
    $query->bindParam(':user_id', $_SESSION['user_id']); // S'assurer que l'utilisateur est le créateur du colis
    $query->execute();
}

// Redirection vers la page colis.php
header("Location: colis.php");
exit;
