<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Require admin authentication
requireAuth('admin');

$page_title = 'Manage Charities';
$current_page = 'charities';

$db = new Database();
$conn = $db->getConnection();

$charities = [];
if ($conn) {
    $stmt = $conn->query("
        SELECT c.*, u.email, u.is_active 
        FROM charities c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.is_approved = TRUE 
        ORDER BY c.display_name
    ");
    $charities = $stmt->fetchAll();
}

// Handle charity actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $charity_id = $_POST['charity_id'];
    
    if ($_POST['action'] === 'deactivate') {
        $stmt = $conn->prepare("UPDATE users SET is_active = FALSE WHERE id = (SELECT user_id FROM charities WHERE id = ?)");
        $stmt->execute([$charity_id]);
    } elseif ($_POST['action'] === 'activate') {
        $stmt = $conn->prepare("UPDATE users SET is_active = TRUE WHERE id = (SELECT user_id FROM charities WHERE id = ?)");
        $stmt->execute([$charity_id]);
    }
    
    header('Location: charities.php');
    exit;
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
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link active" href="charities.php">
                        <i class="fas fa-heart me-2"></i> Charities
                    </a>
                    <a class="nav-link" href="applications.php">
                        <i class="fas fa-clipboard-list me-2"></i> Applications
                    </a>
                    <a class="nav-link" href="donors.php">
                        <i class="fas fa-users me-2"></i> Donors
                    </a>
                    <a class="nav-link" href="modules.php">
                        <i class="fas fa-cube me-2"></i> Modules
                    </a>
                    <a class="nav-link" href="receipts.php">
                        <i class="fas fa-receipt me-2"></i> Tax Receipts
                    </a>
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-chart-bar me-2"></i> Reports
                    </a>
                    <hr>
                    <a class="nav-link" href="../auth/logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 dashboard-main">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold text-primary">Manage Charities</h1>
                    <a href="add-charity.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Charity
                    </a>
                </div>

                <!-- Charities Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Approved Charities (<?php echo count($charities); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($charities)): ?>
                            <p class="text-muted text-center py-4">No charities found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Charity Name</th>
                                            <th>Business Number</th>
                                            <th>Category</th>
                                            <th>Contact Email</th>
                                            <th>Status</th>
                                            <th>Approved Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($charities as $charity): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo sanitizeOutput($charity['display_name']); ?></strong>
                                                <?php if ($charity['legal_name'] !== $charity['display_name']): ?>
                                                <br><small class="text-muted">Legal: <?php echo sanitizeOutput($charity['legal_name']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo sanitizeOutput($charity['business_number']); ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?php echo sanitizeOutput($charity['category']); ?></span>
                                            </td>
                                            <td><?php echo sanitizeOutput($charity['email']); ?></td>
                                            <td>
                                                <?php if ($charity['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $charity['approval_date'] ? date('M j, Y', strtotime($charity['approval_date'])) : 'N/A'; ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="charity-detail.php?id=<?php echo $charity['id']; ?>" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit-charity.php?id=<?php echo $charity['id']; ?>" class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($charity['is_active']): ?>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="charity_id" value="<?php echo $charity['id']; ?>">
                                                        <input type="hidden" name="action" value="deactivate">
                                                        <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Deactivate this charity?')">
                                                            <i class="fas fa-pause"></i>
                                                        </button>
                                                    </form>
                                                    <?php else: ?>
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="charity_id" value="<?php echo $charity['id']; ?>">
                                                        <input type="hidden" name="action" value="activate">
                                                        <button type="submit" class="btn btn-outline-success">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>