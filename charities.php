<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$page_title = $lang['charities'];
$current_page = 'charities';

// Get all approved charities
$db = new Database();
$conn = $db->getConnection();

$charities = [];
$search_term = '';

if ($conn) {
    $sql = "SELECT c.*, u.charity_website 
            FROM charities c 
            LEFT JOIN users u ON c.user_id = u.id 
            WHERE c.is_approved = TRUE";
    
    $params = [];
    
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_term = trim($_GET['search']);
        $sql .= " AND (c.display_name LIKE ? OR c.legal_name LIKE ? OR c.description LIKE ? OR c.category LIKE ?)";
        $search_param = "%{$search_term}%";
        $params = array_fill(0, 4, $search_param);
    }
    
    $sql .= " ORDER BY c.display_name ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $charities = $stmt->fetchAll();
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

    <!-- Page Header -->
    <section class="page-header-banner">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="display-4 fw-bold"><?php echo $lang['charities']; ?></h1>
                    <p class="lead">Découvrez les organismes de bienfaisance vérifiés que vous pouvez soutenir</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filter -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <form method="GET" action="charities.php">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" 
                                   name="search" 
                                   placeholder="<?php echo $lang['search_charities_placeholder']; ?>"
                                   value="<?php echo htmlspecialchars($search_term); ?>">
                            <button class="btn btn-primary btn-lg" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <?php if (!empty($search_term)): ?>
                            <a href="charities.php" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Charities Grid -->
    <section class="py-5">
        <div class="container">
            <?php if (!empty($search_term)): ?>
            <div class="row mb-4">
                <div class="col">
                    <p class="text-muted">
                        <?php echo count($charities); ?> organisme(s) trouvé(s) pour "<?php echo htmlspecialchars($search_term); ?>"
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php if (empty($charities)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucun organisme trouvé</h4>
                    <p class="text-muted">Essayez de modifier vos critères de recherche</p>
                    <a href="charities.php" class="btn btn-primary">Voir tous les organismes</a>
                </div>
                <?php else: ?>
                    <?php foreach ($charities as $charity): ?>
                    <div class="col-lg-6">
                        <div class="card charity-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <?php if ($charity['logo_url']): ?>
                                    <img src="<?php echo htmlspecialchars($charity['logo_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($charity['display_name']); ?>" 
                                         class="charity-logo me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    <?php else: ?>
                                    <div class="charity-logo-placeholder bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-heart text-primary fa-2x"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1">
                                        <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($charity['display_name']); ?></h5>
                                        <p class="text-muted small mb-2">BN: <?php echo htmlspecialchars($charity['business_number']); ?></p>
                                        <div class="charity-category mb-2">
                                            <span class="badge bg-primary"><?php echo htmlspecialchars($charity['category']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <p class="card-text text-muted mb-3"><?php echo htmlspecialchars($charity['description']); ?></p>
                                
                                <?php if ($charity['charity_website']): ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="<?php echo htmlspecialchars($charity['charity_website']); ?>" 
                                       class="btn btn-outline-primary" target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i>
                                        <?php echo $lang['visit_website']; ?>
                                    </a>
                                    <small class="text-muted">
                                        <i class="fas fa-check-circle text-success me-1"></i>
                                        <?php echo $lang['verified']; ?>
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>