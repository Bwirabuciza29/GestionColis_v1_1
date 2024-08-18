<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Vérifier le rôle de l'utilisateur (seulement pour les Gestionnaires)
if ($_SESSION['role'] != 'Gestionnaire') {
    header("Location: index.php");
    exit;
}

// Afficher le nom et le rôle de l'utilisateur
$user_name = $_SESSION['name'];
$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id']; // ID de l'utilisateur connecté


// Inclure la configuration de la base de données
include('config.php');

// Générer une référence de colis unique
function genererReferenceColis($conn)
{
    // Trouver le dernier numéro de référence et l'incrémenter
    $query = $conn->prepare("SELECT reference_colis FROM Colis ORDER BY id_colis DESC LIMIT 1");
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $last_ref = $result['reference_colis'];
        $number = (int)substr($last_ref, 3) + 1; // Incrémenter le numéro
    } else {
        $number = 1; // Premier enregistrement
    }

    return 'REF' . str_pad($number, 4, '0', STR_PAD_LEFT); // Format REF0001
}

$reference_colis = genererReferenceColis($conn);

// Gérer l'enregistrement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $description = $_POST['description'];
    $poids = $_POST['poids'];
    $date_arrivee = $_POST['date_arrivee'];
    $bureau_arrivee = $_POST['bureau_arrivee'];

    // Gestion du téléchargement de la photo
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo = $target_file; // Chemin de la photo
        }
    }

    // Insérer le colis dans la base de données, incluant le champ `created_by`
    $query = $conn->prepare("INSERT INTO Colis (reference_colis, description, poids, date_embarquement, date_arrivee, bureau_depart, bureau_arrivee, photo, id_utilisateur, created_by) 
                             VALUES (:reference_colis, :description, :poids, NOW(), :date_arrivee, :bureau_depart, :bureau_arrivee, :photo, :id_utilisateur, :created_by)");
    $query->bindParam(':reference_colis', $reference_colis);
    $query->bindParam(':description', $description);
    $query->bindParam(':poids', $poids);
    $query->bindParam(':date_arrivee', $date_arrivee);
    $query->bindParam(':bureau_depart', $user_id); // Bureau de départ est l'utilisateur connecté
    $query->bindParam(':bureau_arrivee', $bureau_arrivee);
    $query->bindParam(':photo', $photo);
    $query->bindParam(':id_utilisateur', $user_id);
    $query->bindParam(':created_by', $user_id); // `created_by` prend l'ID de l'utilisateur connecté
    $query->execute();

    // Redirection après l'enregistrement
    header("Location: colis.php");
    exit;
}

// Récupérer les autres utilisateurs pour sélectionner le bureau d'arrivée
$query = $conn->prepare("SELECT id_utilisateur, nom_utilisateur FROM Utilisateur WHERE id_utilisateur != :user_id");
$query->bindParam(':user_id', $user_id);
$query->execute();
$autres_bureaux = $query->fetchAll(PDO::FETCH_ASSOC);
// Récupérer les colis enregistrés par l'utilisateur connecté
$query = $conn->prepare("SELECT * FROM Colis WHERE created_by = :user_id");
$query->bindParam(':user_id', $user_id);
$query->execute();
$colis = $query->fetchAll(PDO::FETCH_ASSOC);

// Compter le nombre de colis enregistrés
$nombre_colis = count($colis);
?>


<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>GestionStock_Colis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="assets/images/logo.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="assets/images/logo-dark.png" alt="" height="17">
                            </span>
                        </a>

                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="assets/images/logo-light.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="assets/images/logo-light.png" alt="" height="19">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>

                </div>

                <div class="d-flex">
                    <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                            <i class="bx bx-fullscreen"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-bell bx-tada"></i>
                            <span class="badge bg-danger rounded-pill">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0" key="t-notifications"> Notifications </h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#!" class="small" key="t-view-all"> View All</a>
                                    </div>
                                </div>
                            </div>
                            <div data-simplebar style="max-height: 230px;">
                                <a href="javascript: void(0);" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                                <i class="bx bx-badge-check"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1" key="t-shipped">Your item is shipped</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1" key="t-grammer">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">3 min ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                            </div>
                            <div class="p-2 border-top d-grid">
                                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                    <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View More..</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <!-- <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg"
                                alt="Header Avatar"> -->
                            <span class="d-none d-xl-inline-block ms-1" key="t-henry"><?php echo htmlspecialchars($user_name); ?></span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="logout.php"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Déconnexion</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>