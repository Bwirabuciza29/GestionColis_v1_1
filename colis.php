<?php
include("./blade/userHeader.php");
include("./blade/userAside.php")
?>

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
                                            <p class="text-muted fw-medium">Sorties</p>
                                            <h4 class="mb-0"><?= $nombre_colis; ?></h4>
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
                                            <p class="text-muted fw-medium">Entrées</p>
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
                                            <p class="text-muted fw-medium">Mes Transferts</p>
                                            <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm">Enregistrer un colis</button>
                                            <!--  Small modal example -->
                                            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="mySmallModalLabel">Enregistrer Utilisateur</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="colis.php" method="post" enctype="multipart/form-data">
                                                                <div class="mb-3">
                                                                    <label for="reference_colis" class="form-label">Référence Colis</label>
                                                                    <input type="text" class="form-control" id="reference_colis" name="reference_colis" value="<?= htmlspecialchars($reference_colis); ?>" readonly>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="description" class="form-label">Description</label>
                                                                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="poids" class="form-label">Poids (kg)</label>
                                                                    <input type="number" class="form-control" id="poids" name="poids" step="0.01" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="date_arrivee" class="form-label">Date d'Arrivée</label>
                                                                    <input type="date" class="form-control" id="date_arrivee" name="date_arrivee" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="bureau_depart" class="form-label">Bureau de Départ</label>
                                                                    <input type="text" class="form-control" id="bureau_depart" value="<?= htmlspecialchars($user_name); ?>" readonly>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="bureau_arrivee" class="form-label">Bureau d'Arrivée</label>
                                                                    <select class="form-select" id="bureau_arrivee" name="bureau_arrivee" required>
                                                                        <option value="" selected disabled>-- Sélectionner un bureau --</option>
                                                                        <?php foreach ($autres_bureaux as $bureau): ?>
                                                                            <option value="<?= htmlspecialchars($bureau['id_utilisateur']); ?>"><?= htmlspecialchars($bureau['nom_utilisateur']); ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="photo" class="form-label">Photo du Colis</label>
                                                                    <input class="form-control" type="file" id="photo" name="photo" accept="image/*" required>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="id_utilisateur" class="form-label">Utilisateur</label>
                                                                    <input type="text" class="form-control" id="id_utilisateur" value="<?= htmlspecialchars($user_name); ?>" readonly>
                                                                </div>

                                                                <button type="submit" class="btn btn-primary">Enregistrer le Colis</button>
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
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success" role="alert">
                            Colis enregistré avec succès!
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title">Nos Utilisateurs</h4>

                                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Référence</th>
                                                <th>Description</th>
                                                <th>Poids (kg)</th>
                                                <th>Date Embarquement</th>
                                                <th>Date Arrivée</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($colis as $c): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($c['reference_colis']); ?></td>
                                                    <td><?= htmlspecialchars($c['description']); ?></td>
                                                    <td><?= htmlspecialchars($c['poids']); ?></td>
                                                    <td><?= htmlspecialchars($c['date_embarquement']); ?></td>
                                                    <td><?= htmlspecialchars($c['date_arrivee']); ?></td>
                                                    <td>
                                                        <!-- Bouton pour afficher la photo -->
                                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#photoModal<?= $c['id_colis']; ?>">Voir Photo</button>

                                                        <!-- Bouton pour modifier le colis -->
                                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id_colis']; ?>">Modifier</button>

                                                        <!-- Bouton pour supprimer le colis -->
                                                        <form action="delete_colis.php" method="post" style="display:inline;">
                                                            <input type="hidden" name="id_colis" value="<?= $c['id_colis']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce colis ?');">Supprimer</button>
                                                        </form>
                                                    </td>
                                                </tr>

                                                <!-- Modal pour afficher la photo -->
                                                <div class="modal fade" id="photoModal<?= $c['id_colis']; ?>" tabindex="-1" aria-labelledby="photoModalLabel<?= $c['id_colis']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="photoModalLabel<?= $c['id_colis']; ?>">Photo du Colis</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="<?= htmlspecialchars($c['photo']); ?>" class="img-fluid" alt="Photo du Colis">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal pour modifier le colis -->
                                                <div class="modal fade" id="editModal<?= $c['id_colis']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $c['id_colis']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel<?= $c['id_colis']; ?>">Modifier Colis</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="edit_colis.php" method="post" enctype="multipart/form-data">
                                                                    <input type="hidden" name="id_colis" value="<?= $c['id_colis']; ?>">
                                                                    <div class="mb-3">
                                                                        <label for="description" class="form-label">Description</label>
                                                                        <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($c['description']); ?></textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="poids" class="form-label">Poids (kg)</label>
                                                                        <input type="number" class="form-control" id="poids" name="poids" step="0.01" value="<?= htmlspecialchars($c['poids']); ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="date_arrivee" class="form-label">Date d'Arrivée</label>
                                                                        <input type="date" class="form-control" id="date_arrivee" name="date_arrivee" value="<?= htmlspecialchars($c['date_arrivee']); ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="photo" class="form-label">Photo du Colis</label>
                                                                        <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                                                                        <small class="text-muted">Laissez vide pour ne pas changer la photo.</small>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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