<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$page_title = $lang['about_title'];
$current_page = 'about';
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
                <h1 class="display-4 fw-bold"><?php echo $lang['about_title']; ?></h1>
                <p class="lead"><?php echo $lang['mission']; ?>: Revolutionizing transparency in physical donations</p>
            </div>
        </div>
    </div>
</section>

    <!-- Mission Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold text-primary mb-4"><?php echo $lang['mission']; ?></h2>
                    <p class="lead mb-4">AidVeritas a été créé pour résoudre un problème fondamental dans l'écosystème caritatif : l'opacité des dons physiques traditionnels.</p>
                    <p class="mb-4">Notre plateforme transforme les dons anonymes en pièces de monnaie en transactions numériques vérifiables et attribuées, garantissant que chaque donateur reçoive la reconnaissance et les avantages fiscaux qui lui reviennent.</p>
                    <div class="trust-badge">
                        <i class="fas fa-shield-alt fa-3x encryption-icon mb-3"></i>
                        <h5 class="fw-bold">Sécurité et Transparence</h5>
                        <p class="mb-0">Chaque transaction est cryptographiquement sécurisée et immuable</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/transparency.svg" alt="Transparency" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="display-5 fw-bold text-primary">Nos Valeurs</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 text-center">
                        <div class="card-body p-4">
                            <div class="text-primary mb-3">
                                <i class="fas fa-eye fa-3x"></i>
                            </div>
                            <h4 class="fw-bold">Transparence</h4>
                            <p class="text-muted">Chaque don est traçable de la pièce de monnaie jusqu'à l'organisme de bienfaisance, avec un registre immuable accessible à tous les partenaires.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 text-center">
                        <div class="card-body p-4">
                            <div class="text-primary mb-3">
                                <i class="fas fa-user-shield fa-3x"></i>
                            </div>
                            <h4 class="fw-bold">Équité</h4>
                            <p class="text-muted">Nous garantissons que le crédit et les avantages fiscaux reviennent au véritable donateur, et non aux intermédiaires.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 text-center">
                        <div class="card-body p-4">
                            <div class="text-primary mb-3">
                                <i class="fas fa-hand-holding-heart fa-3x"></i>
                            </div>
                            <h4 class="fw-bold">Impact</h4>
                            <p class="text-muted">En restaurant la confiance dans le système, nous encourageons une plus grande générosité et un impact caritatif accru.</p>
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