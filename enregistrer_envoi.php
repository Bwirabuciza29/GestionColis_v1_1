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
    try {
        // Récupérer les données du formulaire
        $id_utilisateur = $_POST['id_utilisateur'];
        $id_colis = $_POST['id_colis'];
        $date_sortie = $_POST['date_sortie'];
        $created_by = $_SESSION['user_id'];

        // Vérifier les valeurs récupérées
        echo "id_utilisateur: $id_utilisateur<br>";
        echo "id_colis: $id_colis<br>";
        echo "date_sortie: $date_sortie<br>";
        echo "created_by: $created_by<br>";

        // Enregistrer l'envoi dans la table envoyer
        $query = $conn->prepare("INSERT INTO Envoyer (id_utilisateur, id_colis, date_sortie, created_by) VALUES (:id_utilisateur, :id_colis, :date_sortie, :created_by)");
        $query->bindParam(':id_utilisateur', $id_utilisateur);
        $query->bindParam(':id_colis', $id_colis);
        $query->bindParam(':date_sortie', $date_sortie);
        $query->bindParam(':created_by', $created_by);

        if ($query->execute()) {
            echo "Enregistrement réussi dans la table Envoyer.<br>";
        } else {
            echo "Échec de l'enregistrement dans la table Envoyer.<br>";
            print_r($query->errorInfo()); // Afficher les erreurs SQL
        }

        // Supprimer le colis de la table Colis
        $query = $conn->prepare("DELETE FROM Colis WHERE id_colis = :id_colis AND created_by = :created_by");
        $query->bindParam(':id_colis', $id_colis);
        $query->bindParam(':created_by', $created_by);

        if ($query->execute()) {
            echo "Suppression réussie dans la table Colis.<br>";
        } else {
            echo "Échec de la suppression dans la table Colis.<br>";
            print_r($query->errorInfo()); // Afficher les erreurs SQL
        }

        // Redirection vers la page stock.php
        header("Location: stock.php");
        exit;
    } catch (PDOException $e) {
        // Afficher les erreurs SQL
        echo "Erreur : " . $e->getMessage();
    }
}
