    <?php
    include("./blade/ComptHeader.php");
    include("./blade/ComptAside.php");
    // Vérifier si l'ID du colis est passé dans l'URL
    if (!isset($_GET['id'])) {
        header("Location: colis.php");
        exit;
    }
    include('config.php');
    $id_colis = $_GET['id'];

    // Récupérer les informations du colis depuis la base de données
    $query = $conn->prepare("
    SELECT 
        c.*, 
        u.nom_utilisateur AS nomExpediteur, 
        u2.nom_utilisateur AS nomDestinateur, 
        c.nomExpediteur AS nomExpediteurColis,  
        c.nomDestinateur AS nomDestinateurColis
    FROM 
        Colis c
    JOIN 
        Utilisateur u ON c.id_utilisateur = u.id_utilisateur
    JOIN 
        Utilisateur u2 ON c.bureau_arrivee = u2.id_utilisateur
    WHERE 
        c.id_colis = :id_colis
");

    $query->bindParam(':id_colis', $id_colis);
    $query->execute();
    $colis = $query->fetch(PDO::FETCH_ASSOC);

    // Si le colis n'existe pas, rediriger
    if (!$colis) {
        header("Location: colis.php");
        exit;
    }
    ?>
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Reçu de paiement</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="invoice-title">
                                    <h4 class="float-end font-size-16">Num Ref #<?= htmlspecialchars($colis['reference_colis']); ?></h4>
                                    <div class="mb-4">
                                        <h4 class="mb-sm-0 font-size-18">AGENCE DE FRET REGLE D'OR</h4>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <address>
                                            <strong>Expéditeur:</strong><br>
                                            Bureau de : <strong><?= htmlspecialchars($colis['nomExpediteur']); ?></strong> <br>
                                            Expéditeur: <strong><?= htmlspecialchars($colis['nomExpediteurColis']); ?></strong> <br>
                                            Téléphone Expediteur: <strong><?= htmlspecialchars(chunk_split($colis['telExpediteur'], 3, ' ')); ?>
                                            </strong> <br>
                                        </address>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <address class="mt-2 mt-sm-0">
                                            <strong>Destinateur:</strong><br>
                                            Vers le Bureau de : <strong><?= htmlspecialchars($colis['nomDestinateur']); ?></strong> <br>
                                            Destinataire: <strong> <?= htmlspecialchars($colis['nomDestinateurColis']); ?></strong> <br>
                                            Téléphone Destinataire: <strong><?= htmlspecialchars(chunk_split($colis['telDestinataire'], 3, ' ')); ?></strong> <br>
                                        </address>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mt-3">
                                        <address>
                                            <strong>Methode de payement:</strong><br>
                                            Cash<br>
                                            USD
                                        </address>
                                    </div>
                                    <div class="col-sm-6 mt-3 text-sm-end">
                                        <address>
                                            <strong>Date d'Envoi:</strong><br>
                                            <?= htmlspecialchars($colis['date_embarquement']); ?><br><br>
                                        </address>
                                    </div>
                                </div>
                                <div class="py-2 mt-3">
                                    <h3 class="font-size-15 fw-bold">Détail sur le colis</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-nowrap">
                                        <thead>
                                            <tr>
                                                <th style="width: 70px;">No.</th>
                                                <th>Designation</th>
                                                <th>Date Arrivee</th>
                                                <th class="text-end">Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>01</td>
                                                <td><?= htmlspecialchars($colis['description']); ?></td>
                                                <td><?= htmlspecialchars($colis['date_arrivee']); ?></td>
                                                <td class="text-end">$<?= htmlspecialchars($colis['montantPaye']); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end">Sous-total</td>
                                                <td class="text-end">$<?= htmlspecialchars($colis['montantPaye']); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="border-0 text-end">
                                                    <strong>Expédition</strong>
                                                </td>
                                                <td class="border-0 text-end">$00.00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="border-0 text-end">
                                                    <strong>Total</strong>
                                                </td>
                                                <td class="border-0 text-end">
                                                    <h4 class="m-0">$<?= htmlspecialchars($colis['montantPaye'] + 00); ?></h4>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-print-none">
                                    <div class="float-end">
                                        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light me-1"><i class="fa fa-print"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                    </div>

                </div>
            </div>
        </footer>
    </div>
    <?php
    include("./blade/Footer.php");
    ?>