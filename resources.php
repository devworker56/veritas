<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$page_title = $lang['resources'];
$current_page = 'resources';
?>

<!DOCTYPE html>
<html lang="<?php echo $current_language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title . ' - ' . SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header-banner">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="display-4 fw-bold"><?php echo $lang['resources']; ?></h1>
                    <p class="lead">Guides, documentation et ressources pour utiliser AidVeritas</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Resources Grid -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- For Donors -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="text-primary mb-3">
                                <i class="fas fa-mobile-alt fa-3x"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Pour les Donateurs</h4>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-download text-primary me-2"></i>Guide d'installation de l'application</li>
                                <li class="mb-2"><i class="fas fa-qrcode text-primary me-2"></i>Comment scanner les codes QR</li>
                                <li class="mb-2"><i class="fas fa-receipt text-primary me-2"></i>Comprendre vos reçus fiscaux</li>
                                <li class="mb-2"><i class="fas fa-shield-alt text-primary me-2"></i>Sécurité et confidentialité</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary mt-3">Voir les guides</a>
                        </div>
                    </div>
                </div>

                <!-- For Charities -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="text-primary mb-3">
                                <i class="fas fa-heart fa-3x"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Pour les Organismes</h4>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-clipboard-list text-primary me-2"></i>Processus d'inscription</li>
                                <li class="mb-2"><i class="fas fa-tachometer-alt text-primary me-2"></i>Guide du tableau de bord</li>
                                <li class="mb-2"><i class="fas fa-chart-bar text-primary me-2"></i>Analytics et rapports</li>
                                <li class="mb-2"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>Gestion des reçus</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary mt-3">Documentation</a>
                        </div>
                    </div>
                </div>

                <!-- For Businesses -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="text-primary mb-3">
                                <i class="fas fa-store fa-3x"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Pour les Entreprises</h4>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-cube text-primary me-2"></i>Installation du terminal</li>
                                <li class="mb-2"><i class="fas fa-wrench text-primary me-2"></i>Maintenance et support</li>
                                <li class="mb-2"><i class="fas fa-chart-line text-primary me-2"></i>Rapports RSE</li>
                                <li class="mb-2"><i class="fas fa-handshake text-primary me-2"></i>Partenariats</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary mt-3">Ressources partenaires</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold text-primary text-center mb-5">Foire Aux Questions</h2>
                    
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ Item 1 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Comment fonctionne le processus de don?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Le processus est simple : 1) Téléchargez l'application AidVeritas, 2) Scannez le code QR sur le terminal dans un magasin partenaire, 3) Sélectionnez votre organisme de bienfaisance, 4) Insérez votre monnaie. Chaque don est enregistré et vous recevrez un reçu fiscal annuel consolidé.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Comment sont sécurisées mes informations?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Toutes les transactions sont cryptographiquement sécurisées avec l'algorithme SHA-256. Vos informations personnelles sont chiffrées et nous respectons les normes les plus strictes de protection des données. Chaque transaction crée un hachage immuable pour garantir l'intégrité des données.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="accordion-item">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Quand vais-je recevoir mon reçu fiscal?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Les reçus fiscaux annuels consolidés sont générés automatiquement en février de chaque année pour tous les dons de l'année précédente. Vous recevrez une notification dans l'application et par courriel lorsque votre reçu sera disponible.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>