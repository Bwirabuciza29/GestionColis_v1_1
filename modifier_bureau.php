<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id_bureau = $_GET['id'];

    $query = "SELECT Bureau.id_bureau, Bureau.nom_bureau, Bureau.adresse, Utilisateur.email 
              FROM Bureau 
              LEFT JOIN Utilisateur ON Bureau.id_bureau = Utilisateur.bureau_affecte 
              WHERE Bureau.id_bureau = :id_bureau";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_bureau', $id_bureau);
    $stmt->execute();
    $bureau = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bureau) {
        die("Bureau non trouvé.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom_bureau = $_POST['nom_bureau'];
        $adresse_bureau = $_POST['adresse_bureau'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        if (!empty($mot_de_passe)) {
            $hashed_password = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        } else {
            $hashed_password = $bureau['mot_de_passe'];
        }

        try {
            $query_update_bureau = "UPDATE Bureau SET nom_bureau = :nom_bureau, adresse = :adresse_bureau WHERE id_bureau = :id_bureau";
            $stmt_update_bureau = $conn->prepare($query_update_bureau);
            $stmt_update_bureau->bindParam(':nom_bureau', $nom_bureau);
            $stmt_update_bureau->bindParam(':adresse_bureau', $adresse_bureau);
            $stmt_update_bureau->bindParam(':id_bureau', $id_bureau);
            $stmt_update_bureau->execute();

            $query_update_utilisateur = "UPDATE Utilisateur SET email = :email, mot_de_passe = :mot_de_passe WHERE bureau_affecte = :id_bureau";
            $stmt_update_utilisateur = $conn->prepare($query_update_utilisateur);
            $stmt_update_utilisateur->bindParam(':email', $email);
            $stmt_update_utilisateur->bindParam(':mot_de_passe', $hashed_password);
            $stmt_update_utilisateur->bindParam(':id_bureau', $id_bureau);
            $stmt_update_utilisateur->execute();

            header("Location: user.php");
            exit;
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }
} else {
    die("ID de bureau non fourni.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Bureau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h1 class="mb-4">Modifier Bureau</h1>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="nom_bureau" class="form-label">Nom du Bureau</label>
                <input type="text" class="form-control" id="nom_bureau" name="nom_bureau" value="<?php echo htmlspecialchars($bureau['nom_bureau']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="adresse_bureau" class="form-label">Adresse du Bureau</label>
                <input type="text" class="form-control" id="adresse_bureau" name="adresse_bureau" value="<?php echo htmlspecialchars($bureau['adresse']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($bureau['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Nouveau Mot de Passe (laisser vide pour conserver l'actuel)</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe">
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="user.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>