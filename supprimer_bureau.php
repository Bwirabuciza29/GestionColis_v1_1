<?php
// Inclure le fichier config.php pour la connexion à la base de données
include 'config.php';

// Vérifier si un ID de bureau a été passé dans l'URL
if (isset($_GET['id'])) {
    $id_bureau = $_GET['id'];

    try {
        // Supprimer l'utilisateur associé
        $query_delete_user = "DELETE FROM Utilisateur WHERE bureau_affecte = :id_bureau";
        $stmt_delete_user = $conn->prepare($query_delete_user);
        $stmt_delete_user->bindParam(':id_bureau', $id_bureau);
        $stmt_delete_user->execute();

        // Supprimer le bureau
        $query_delete_bureau = "DELETE FROM Bureau WHERE id_bureau = :id_bureau";
        $stmt_delete_bureau = $conn->prepare($query_delete_bureau);
        $stmt_delete_bureau->bindParam(':id_bureau', $id_bureau);
        $stmt_delete_bureau->execute();

        // Rediriger vers la page user.php après la suppression
        header("Location: user.php");
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    die("ID de bureau non fourni.");
}
