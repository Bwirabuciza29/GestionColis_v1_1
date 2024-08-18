<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Inclure la configuration de la base de données
include('config.php');

// Vérifier si l'ID de l'envoi est fourni
if (isset($_GET['id'])) {
    $envoi_id = $_GET['id'];

    // Supprimer l'envoi de la base de données
    $query = $conn->prepare("DELETE FROM Envoyer WHERE id = :id");
    $query->bindParam(':id', $envoi_id);
    $query->execute();

    // Redirection vers la page colis.php
    header("Location: stock.php");
    exit;
} else {
    echo "ID d'envoi manquant.";
}
