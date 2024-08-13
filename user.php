<?php
include("./blade/homeHeader.php");
include("./blade/asideHome.php");
// Inclure le fichier config.php pour la connexion à la base de données
include 'config.php';

// Récupérer les données des tables Bureau et Utilisateur
$query = "SELECT Bureau.id_bureau, Bureau.nom_bureau, Bureau.adresse, Utilisateur.email 
          FROM Bureau 
          LEFT JOIN Utilisateur ON Bureau.id_bureau = Utilisateur.bureau_affecte";
$stmt = $conn->prepare($query);
$stmt->execute();
$bureaux = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compter le nombre de bureaux
$query_count = "SELECT COUNT(*) as total_bureaux FROM Bureau";
$stmt_count = $conn->prepare($query_count);
$stmt_count->execute();
$total_bureaux = $stmt_count->fetch(PDO::FETCH_ASSOC)['total_bureaux'];
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
                                            <h4 class="mb-0"><?php echo $total_bureaux; ?></h4>
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
                                            <p class="text-muted fw-medium">Enregistrer Utilisateur</p>
                                            <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm">Enregistrer</button>
                                            <!--  Small modal example -->
                                            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="mySmallModalLabel">Enregistrer Utilisateur</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="enregistrer_bureau.php" method="POST">
                                                                <div class="mb-3">
                                                                    <label for="nom_bureau" class="form-label">Nom du Bureau</label>
                                                                    <input type="text" class="form-control" id="nom_bureau" name="nom_bureau" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="adresse_bureau" class="form-label">Adresse du Bureau</label>
                                                                    <input type="text" class="form-control" id="adresse_bureau" name="adresse_bureau" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="email" class="form-label">Email</label>
                                                                    <input type="email" class="form-control" id="email" name="email" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="mot_de_passe" class="form-label">Mot de Passe</label>
                                                                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                                                                </div>

                                                                <button type="submit" class="btn btn-outline-primary waves-effect waves-light">Enregistrer</button>
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

                                    <h4 class="card-title">Nos Utilisateurs</h4>
                                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Nom du Bureau</th>
                                                <th>Adresse</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($bureaux as $bureau) : ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($bureau['nom_bureau']); ?></td>
                                                    <td><?php echo htmlspecialchars($bureau['adresse']); ?></td>
                                                    <td><?php echo htmlspecialchars($bureau['email']); ?></td>
                                                    <td>
                                                        <a href="modifier_bureau.php?id=<?php echo $bureau['id_bureau']; ?>" class="btn btn-outline-primary waves-effect waves-light">Modifier</a>
                                                        <a href="supprimer_bureau.php?id=<?php echo $bureau['id_bureau']; ?>" class="btn btn-outline-danger waves-effect waves-light" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bureau ?');">Supprimer</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
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