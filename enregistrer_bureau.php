<?php
// Inclure le fichier config.php pour la connexion à la base de données
include 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les valeurs du formulaire
    $nom_bureau = $_POST['nom_bureau'];
    $adresse_bureau = $_POST['adresse_bureau'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Hacher le mot de passe
    $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    try {
        // Démarrer une transaction
        $conn->beginTransaction();

        // Insérer dans la table Bureau
        $stmt_bureau = $conn->prepare("INSERT INTO Bureau (nom_bureau, adresse, type_bureau) VALUES (:nom_bureau, :adresse_bureau, 'Origine')");
        $stmt_bureau->bindParam(':nom_bureau', $nom_bureau);
        $stmt_bureau->bindParam(':adresse_bureau', $adresse_bureau);
        $stmt_bureau->execute();

        // Récupérer l'ID du bureau nouvellement inséré
        $bureau_affecte = $conn->lastInsertId();

        // Insérer dans la table Utilisateur
        $stmt_utilisateur = $conn->prepare("INSERT INTO Utilisateur (nom_utilisateur, role, bureau_affecte, email, mot_de_passe) VALUES (:nom_bureau, 'Gestionnaire', :bureau_affecte, :email, :hashed_password)");
        $stmt_utilisateur->bindParam(':nom_bureau', $nom_bureau);
        $stmt_utilisateur->bindParam(':bureau_affecte', $bureau_affecte);
        $stmt_utilisateur->bindParam(':email', $email);
        $stmt_utilisateur->bindParam(':hashed_password', $hashed_password);
        $stmt_utilisateur->execute();

        // Valider la transaction
        $conn->commit();

        // Rediriger vers la page user.php
        header("Location: user.php");
        exit;
    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $conn->rollBack();
        die("Erreur lors de l'enregistrement : " . $e->getMessage());
    }
}
