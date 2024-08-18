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
    $query->execute();

    // Calculer le nombre de jours restants pour la livraison
    $query_colis = $conn->prepare("SELECT date_embarquement, date_arrivee FROM colis WHERE id_colis = :id_colis");
    $query_colis->bindParam(':id_colis', $id_colis);
    $query_colis->execute();
    $colis = $query_colis->fetch(PDO::FETCH_ASSOC);

    $date_embarquement = new DateTime($colis['date_embarquement']);
    $date_arrivee = new DateTime($colis['date_arrivee']);
    $date_sortie = new DateTime($date_sortie);

    $interval = $date_arrivee->diff($date_sortie);
    $jours_restants = $interval->days;

    // Créer une notification pour l'utilisateur destinataire
    $message = "Un colis avec la référence " . $id_colis . " est en route depuis " . $_SESSION['user_id'] . ". Nombre de jours restants : " . $jours_restants;

    $query_notification = $conn->prepare("INSERT INTO notification (id_utilisateur, message) VALUES (:id_utilisateur, :message)");
    $query_notification->bindParam(':id_utilisateur', $id_utilisateur);
    $query_notification->bindParam(':message', $message);
    $query_notification->execute();


    // Rediriger vers la page des envois
    header("Location: stock.php");
    exit;
}
