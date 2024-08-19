<?php
include("./blade/userHeader.php");
include("./blade/userAside.php")
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
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-3">
                                        <h5 class="text-primary">Bienvenue !</h5>
                                        <p><?php echo htmlspecialchars($user_name); ?></p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="assets/images/profile-img.png" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Colis enregistrés</p>
                                            <h4 class="mb-0"><?= htmlspecialchars($total_colis) ?></h4>
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
                        <div class="col-md-8">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Colis Reçus</p>
                                            <h4 class="mb-0"><?= $total_envois; ?></h4>
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

                    </div>
                    <!-- end row -->

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
                                        <th>Référence</th>
                                        <th>Description</th>
                                        <th>Nom Expéditeur</th>
                                        <th>Nom Destinataire</th>
                                        <th>Montant Payé ($)</th>
                                        <th>Poids (kg)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($colis as $c): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($c['reference_colis']); ?></td>
                                            <td><?= htmlspecialchars($c['description']); ?></td>
                                            <td><?= htmlspecialchars($c['nomExpediteur']); ?></td>
                                            <td><?= htmlspecialchars($c['nomDestinateur']); ?></td>
                                            <td><?= htmlspecialchars($c['montantPaye']); ?></td>
                                            <td><?= htmlspecialchars($c['poids']); ?></td>
                                            <td>
                                                <a href="invoice.php?id=<?= htmlspecialchars($c['id_colis']); ?>" class="btn btn-info btn-sm">voir reçu</a>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
            <!-- end row -->
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


<?php
include("./blade/Footer.php");
?>