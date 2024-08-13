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
    $description = $_POST['description'];
    $poids = $_POST['poids'];
    $date_arrivee = $_POST['date_arrivee'];
    $user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté

    // Préparer la requête de mise à jour
    $query = $conn->prepare("UPDATE Colis SET description = :description, poids = :poids, date_arrivee = :date_arrivee WHERE id_colis = :id_colis AND created_by = :user_id");
    $query->bindParam(':description', $description);
    $query->bindParam(':poids', $poids);
    $query->bindParam(':date_arrivee', $date_arrivee);
    $query->bindParam(':id_colis', $id_colis);
    $query->bindParam(':user_id', $user_id); // S'assurer que l'utilisateur est le créateur du colis

    // Exécution de la requête
    $query->execute();

    // Gestion du téléchargement de la nouvelle photo si fournie
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // Mise à jour du chemin de la photo dans la base de données
            $query = $conn->prepare("UPDATE Colis SET photo = :photo WHERE id_colis = :id_colis AND created_by = :user_id");
            $query->bindParam(':photo', $target_file);
            $query->bindParam(':id_colis', $id_colis);
            $query->bindParam(':user_id', $user_id);
            $query->execute();
        }
    }
}

// Redirection vers la page colis.php
header("Location: colis.php");
exit;
