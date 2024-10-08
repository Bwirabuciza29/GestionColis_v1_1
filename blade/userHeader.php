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
    $nomExpediteur = $_POST['nomExpediteur'];
    $telExpediteur = $_POST['telExpediteur'];
    $nomDestinateur = $_POST['nomDestinateur'];
    $telDestinataire = $_POST['telDestinataire'];
    $montantPaye = $_POST['montantPaye'];
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
    $query = $conn->prepare("INSERT INTO Colis (reference_colis, description, poids, date_embarquement, date_arrivee, bureau_depart, bureau_arrivee, photo, id_utilisateur, created_by, nomExpediteur, telExpediteur, nomDestinateur, telDestinataire, montantPaye) 
                             VALUES (:reference_colis, :description, :poids, NOW(), :date_arrivee, :bureau_depart, :bureau_arrivee, :photo, :id_utilisateur, :created_by, :nomExpediteur, :telExpediteur, :nomDestinateur, :telDestinataire, :montantPaye)");
    $query->bindParam(':reference_colis', $reference_colis);
    $query->bindParam(':description', $description);
    $query->bindParam(':poids', $poids);
    $query->bindParam(':date_arrivee', $date_arrivee);
    $query->bindParam(':bureau_depart', $user_id); // Bureau de départ est l'utilisateur connecté
    $query->bindParam(':bureau_arrivee', $bureau_arrivee);
    $query->bindParam(':photo', $photo);
    $query->bindParam(':id_utilisateur', $user_id);
    $query->bindParam(':created_by', $user_id); // `created_by` prend l'ID de l'utilisateur connecté
    $query->bindParam(':nomExpediteur', $nomExpediteur);
    $query->bindParam(':telExpediteur', $telExpediteur);
    $query->bindParam(':nomDestinateur', $nomDestinateur);
    $query->bindParam(':telDestinataire', $telDestinataire);
    $query->bindParam(':montantPaye', $montantPaye);
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

// Récupérer les notifications pour l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT n.message, n.date_notification, u.nom_utilisateur 
                          FROM notification n 
                          JOIN utilisateur u ON n.id_utilisateur = u.id_utilisateur
                          WHERE n.id_utilisateur = :user_id 
                          ORDER BY n.date_notification DESC");
$query->bindParam(':user_id', $user_id);
$query->execute();
$notifications = $query->fetchAll(PDO::FETCH_ASSOC);

// Nombre total de notifications
$total_notifications = count($notifications);
// Récupérer le nombre total de colis enregistrés par l'utilisateur connecté
$query = $conn->prepare("SELECT COUNT(*) as total_colis FROM colis WHERE created_by = :user_id");
$query->bindParam(':user_id', $user_id);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

$total_colis = $result['total_colis'];
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

</head>

<body data-sidebar="dark">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <img src="assets/images/noir.png" alt="" height="108">
                </div>

                <div class="d-flex">
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-bell bx-tada"></i>
                            <span class="badge bg-danger rounded-pill"><?= htmlspecialchars($total_notifications) ?></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0">Notifications</h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#" class="small">Voir tout</a>
                                    </div>
                                </div>
                            </div>
                            <div data-simplebar style="max-height: 230px;">
                                <?php foreach ($notifications as $notification): ?>
                                    <a href="javascript: void(0);" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?= htmlspecialchars($notification['message']) ?></h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span><?= htmlspecialchars($notification['date_notification']) ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div class="p-2 border-top d-grid">
                                <a class="btn btn-sm btn-link font-size-14 text-center" href="#">
                                    <i class="mdi mdi-arrow-right-circle me-1"></i> Voir plus..
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
                            <a class="dropdown-item text-danger" href="logout.php"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Deconnexion</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>