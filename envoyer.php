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
    $id_utilisateur = $_POST['id_utilisateur'];
    $id_colis = $_POST['id_colis'];
    $created_by = $_SESSION['user_id'];
    $date_sortie = date('Y-m-d H:i:s'); // Générer la date de sortie actuelle

    // Insérer les données dans la table envoyer
    $query = $conn->prepare("INSERT INTO envoyer (id_utilisateur, id_colis, date_sortie, created_by) VALUES (:id_utilisateur, :id_colis, :date_sortie, :created_by)");
    $query->bindParam(':id_utilisateur', $id_utilisateur);
    $query->bindParam(':id_colis', $id_colis);
    $query->bindParam(':date_sortie', $date_sortie);
    $query->bindParam(':created_by', $created_by);

    // Rediriger vers la page des envois
    header("Location: stock.php");
    exit;
}
