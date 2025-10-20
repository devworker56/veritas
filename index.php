<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$page_title = $lang['home_title'];
$current_page = 'home';

// Get featured charities for homepage
$db = new Database();
$conn = $db->getConnection();

$featured_charities = [];
if ($conn) {
    $stmt = $conn->prepare("
        SELECT c.*, u.charity_website 
        FROM charities c 
        LEFT JOIN users u ON c.user_id = u.id 
        WHERE c.is_approved = TRUE 
        ORDER BY c.created_at DESC 
        LIMIT 6
    ");
    $stmt->execute();
    $featured_charities = $stmt->fetchAll();
}
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

    <!-- Main Banner -->
    <section class="main-banner">
        <div class="container">
            <div class="row align-items-center min-vh-50">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-primary mb-4">AidVeritas</h1>
                    <?php if ($current_language === 'fr'): ?>
                        <p class="lead mb-4">AidVeritas transforme votre monnaie en dons vérifiables. Nous consolidons vos dons annuels en un seul reçu fiscal, vous permettant de réclamer votre crédit, tout en garantissant que les organismes de bienfaisance reçoivent un soutien entièrement attribué et de confiance.</p>
                    <?php else: ?>
                        <p class="lead mb-4">AidVeritas turns your spare change into verifiable donations. We consolidate your annual giving into a single tax receipt, so you can claim your credit, while ensuring charities receive fully attributed, trusted support.</p>
                    <?php endif; ?>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#how-it-works" class="btn btn-primary btn-lg">
                            <i class="fas fa-play-circle me-2"></i>
                            <?php echo $lang['how_it_works']; ?>
                        </a>
                        <a href="#charities" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-heart me-2"></i>
                            <?php echo $lang['view_charities']; ?>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="assets/images/donation-terminal.svg" alt="AidVeritas Terminal" class="img-fluid" style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2 class="display-5 fw-bold text-primary mb-3"><?php echo $lang['how_it_works']; ?></h2>
                    <p class="lead"><?php echo $lang['simple_steps']; ?></p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-mobile-alt fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">1. <?php echo $lang['download_app']; ?></h4>
                            <p class="text-muted"><?php echo $lang['step1_desc']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-qrcode fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">2. <?php echo $lang['scan_qr']; ?></h4>
                            <p class="text-muted"><?php echo $lang['step2_desc']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-hand-holding-usd fa-2x"></i>
                            </div>
                            <h4 class="fw-bold">3. <?php echo $lang['donate_receive']; ?></h4>
                            <p class="text-muted"><?php echo $lang['step3_desc']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Charities Section -->
    <section id="charities" class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col">
                    <h2 class="display-5 fw-bold text-primary text-center mb-3"><?php echo $lang['featured_charities']; ?></h2>
                    <p class="lead text-center"><?php echo $lang['charities_desc']; ?></p>
                </div>
            </div>
            
            <!-- Charity Search -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form id="charitySearchForm">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-lg" 
                                           placeholder="<?php echo $lang['search_charities_placeholder']; ?>" 
                                           id="charitySearch">
                                    <button class="btn btn-primary btn-lg" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charity Grid -->
            <div class="row g-4" id="charityGrid">
                <?php foreach ($featured_charities as $charity): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card charity-card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-start mb-3">
                                <?php if ($charity['logo_url']): ?>
                                <img src="<?php echo htmlspecialchars($charity['logo_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($charity['display_name']); ?>" 
                                     class="charity-logo me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                <div class="charity-logo-placeholder bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-heart text-primary"></i>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($charity['display_name']); ?></h5>
                                    <p class="text-muted small mb-0">BN: <?php echo htmlspecialchars($charity['business_number']); ?></p>
                                </div>
                            </div>
                            <p class="card-text text-muted"><?php echo htmlspecialchars(substr($charity['description'], 0, 120)) . '...'; ?></p>
                            <div class="charity-category mb-3">
                                <span class="badge bg-light text-dark"><?php echo htmlspecialchars($charity['category']); ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="<?php echo htmlspecialchars($charity['charity_website'] ?? '#'); ?>" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    <?php echo $lang['visit_website']; ?>
                                </a>
                                <small class="text-muted">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    <?php echo $lang['verified']; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <a href="charities.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-list me-2"></i>
                        <?php echo $lang['view_all_charities']; ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>