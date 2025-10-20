<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

requireAuth('admin');

$page_title = 'Manage Modules';
$current_page = 'modules';

$db = new Database();
$conn = $db->getConnection();

$modules = [];
if ($conn) {
    $stmt = $conn->query("SELECT * FROM modules ORDER BY created_at DESC");
    $modules = $stmt->fetchAll();
}

// Handle module actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_module'])) {
        $station_id = trim($_POST['station_id']);
        $location_name = trim($_POST['location_name']);
        $location_address = trim($_POST['location_address']);
        $business_partner = trim($_POST['business_partner']);
        
        $stmt = $conn->prepare("INSERT INTO modules (station_id, location_name, location_address, business_partner) VALUES (?, ?, ?, ?)");
        $stmt->execute([$station_id, $location_name, $location_address, $business_partner]);
        
        header('Location: modules.php');
        exit;
    }
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
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>

            <div class="col-md-9 col-lg-10 dashboard-main">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold text-primary">Manage Modules</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                        <i class="fas fa-plus me-2"></i>Add New Module
                    </button>
                </div>

                <!-- Modules Grid -->
                <div class="row g-4">
                    <?php foreach ($modules as $module): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card module-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?php echo sanitizeOutput($module['station_id']); ?></h6>
                                <span class="badge bg-<?php echo $module['status'] === 'active' ? 'success' : ($module['status'] === 'maintenance' ? 'warning' : 'danger'); ?>">
                                    <?php echo ucfirst($module['status']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo sanitizeOutput($module['location_name']); ?></h6>
                                <p class="card-text small text-muted mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?php echo sanitizeOutput($module['location_address']); ?>
                                </p>
                                <?php if ($module['business_partner']): ?>
                                <p class="card-text small text-muted mb-2">
                                    <i class="fas fa-building me-1"></i>
                                    <?php echo sanitizeOutput($module['business_partner']); ?>
                                </p>
                                <?php endif; ?>
                                <p class="card-text small text-muted mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    Last seen: <?php echo $module['last_heartbeat'] ? date('M j, g:i A', strtotime($module['last_heartbeat'])) : 'Never'; ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group w-100">
                                    <button class="btn btn-sm btn-outline-primary">Edit</button>
                                    <button class="btn btn-sm btn-outline-warning">Maintenance</button>
                                    <button class="btn btn-sm btn-outline-danger">Deactivate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (empty($modules)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-cube fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Modules Found</h4>
                    <p class="text-muted">Get started by adding your first AidVeritas module.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                        <i class="fas fa-plus me-2"></i>Add First Module
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Module Modal -->
    <div class="modal fade" id="addModuleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Station ID *</label>
                            <input type="text" class="form-control" name="station_id" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location Name *</label>
                            <input type="text" class="form-control" name="location_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location Address</label>
                            <textarea class="form-control" name="location_address" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Business Partner</label>
                            <input type="text" class="form-control" name="business_partner">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_module" class="btn btn-primary">Add Module</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>