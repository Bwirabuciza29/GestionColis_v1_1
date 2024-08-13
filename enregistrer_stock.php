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
    // Récupérer les données du formulaire
    $id_colis = $_POST['id_colis'];
    $date_entree = $_POST['date_entree'];
    $quantite = $_POST['quantite'];
    $status = 'Sorti';
    $created_by = $_SESSION['user_id'];

    // Préparer et exécuter la requête d'insertion
    $query = $conn->prepare("INSERT INTO Stock (id_colis, date_entree, quantite, status, created_by) VALUES (:id_colis, :date_entree, :quantite, :status, :created_by)");
    $query->bindParam(':id_colis', $id_colis);
    $query->bindParam(':date_entree', $date_entree);
    $query->bindParam(':quantite', $quantite);
    $query->bindParam(':status', $status);
    $query->bindParam(':created_by', $created_by);

    // Exécution de la requête
    $query->execute();
}

// Redirection vers la page stock.php après l'enregistrement
header("Location: stock.php");
exit;
