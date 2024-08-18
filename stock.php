<?php
include("./blade/userHeader.php");
include("./blade/userAside.php");
// Inclure la configuration de la base de données
include('config.php');
// Récupérer la liste des autres utilisateurs
$query = $conn->prepare("SELECT id_utilisateur, nom_utilisateur FROM utilisateur WHERE id_utilisateur != :id_utilisateur");
$query->bindParam(':id_utilisateur', $_SESSION['user_id']);
$query->execute();
$autres_utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des colis de l'utilisateur connecté
$query = $conn->prepare("SELECT id_colis, reference_colis, description FROM colis WHERE created_by = :created_by");
$query->bindParam(':created_by', $_SESSION['user_id']);
$query->execute();
$colis_utilisateur = $query->fetchAll(PDO::FETCH_ASSOC);


// Récupérer les envois où l'utilisateur connecté est le destinataire
$query = $conn->prepare("
    SELECT Envoyer.id, Colis.reference_colis, Colis.description, Colis.date_embarquement, Colis.date_arrivee, 
           Colis.bureau_depart, Colis.bureau_arrivee, Colis.photo
    FROM Envoyer
    JOIN Colis ON Envoyer.id_colis = Colis.id_colis
    WHERE Envoyer.id_utilisateur = :user_id
");
$query->bindParam(':user_id', $_SESSION['user_id']);
$query->execute();
$envois = $query->fetchAll(PDO::FETCH_ASSOC);

// Nombre d'envois reçus
$total_envois = count($envois);

?>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Gestion Compte</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Bureau</p>
                                            <h4 class="mb-0">2</h4>
                                        </div>

                                        <div class="flex-shrink-0 align-self-center">
                                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                <span class="avatar-title">
                                                    <i class="bx bx-copy-alt font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Administrateur</p>
                                            <h4 class="mb-0">1</h4>
                                        </div>

                                        <div class="flex-shrink-0 align-self-center ">
                                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="bx bx-archive-in font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Formulaire d'envoi</p>
                                            <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm">Envoyer</button>
                                            <!--  Small modal example -->
                                            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="mySmallModalLabel">Envoyer un colis</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="envoyer.php" method="POST">
                                                                <div class="mb-3">
                                                                    <label for="id_utilisateur" class="form-label">Utilisateur Destinataire</label>
                                                                    <select class="form-select" name="id_utilisateur" id="id_utilisateur" required>
                                                                        <option value="">Sélectionner un utilisateur</option>
                                                                        <?php foreach ($autres_utilisateurs as $utilisateur): ?>
                                                                            <option value="<?= $utilisateur['id_utilisateur'] ?>"><?= $utilisateur['nom_utilisateur'] ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="id_colis" class="form-label">Colis</label>
                                                                    <select class="form-select" name="id_colis" id="id_colis" required>
                                                                        <option value="">Sélectionner un colis</option>
                                                                        <?php foreach ($colis_utilisateur as $colis): ?>
                                                                            <option value="<?= $colis['id_colis'] ?>">
                                                                                <?= $colis['reference_colis'] ?> - <?= $colis['description'] ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <input type="hidden" name="created_by" value="<?= $_SESSION['user_id'] ?>">
                                                                <button type="submit" class="btn btn-primary">Enregistrer l'Envoi</button>
                                                            </form>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div><!-- /.modal -->
                                        </div>

                                        <div class="flex-shrink-0 align-self-center">
                                            <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Envois Reçus</h4>
                                    <p>Total des envois reçus : <?= $total_envois; ?></p>
                                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Référence Colis</th>
                                                <th>Désignation</th>
                                                <th>Date Embarquement</th>
                                                <th>Date Arrivée</th>
                                                <th>Bureau Départ</th>
                                                <th>Bureau Arrivée</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($total_envois > 0) { ?>
                                                <?php foreach ($envois as $envoi) { ?>
                                                    <tr>
                                                        <td><?= $envoi['reference_colis']; ?></td>
                                                        <td><?= $envoi['description']; ?></td>
                                                        <td><?= $envoi['date_embarquement']; ?></td>
                                                        <td><?= $envoi['date_arrivee']; ?></td>
                                                        <td><?= $envoi['bureau_depart']; ?></td>
                                                        <td><?= $envoi['bureau_arrivee']; ?></td>
                                                        <td>
                                                            <!-- Bouton pour voir la photo (Modale) -->
                                                            <a type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target="#photoModal<?= $envoi['id']; ?>">Voir</a>

                                                            <!-- Bouton pour supprimer l'envoi -->
                                                            <form action="delete_envoi.php" method="POST" style="display:inline-block;">
                                                                <input type="hidden" name="id_envoi" value="<?= $envoi['id']; ?>">
                                                                <button type="submit" class="btn btn-outline-danger waves-effect waves-light">Supprimer</button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    <!-- Modale pour voir la photo du colis -->
                                                    <div class="modal fade" id="photoModal<?= $envoi['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel<?= $envoi['id']; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="photoModalLabel<?= $envoi['id']; ?>">Photo du Colis</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="<?= $envoi['photo']; ?>" class="img-fluid" alt="Photo du colis">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <tr>
                                                    <td colspan="7" class="text-danger text-center">Aucun envoi reçu pour cet utilisateur.</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <!-- end modal -->


    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © Gestion Stock Colis.
                </div>
            </div>
        </div>
    </footer>
</div>
<!-- end main content-->

</div>
<!-- END layout-wrapper -->


<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>
<?php
include("./blade/Footer.php");
?>