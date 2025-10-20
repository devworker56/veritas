<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Require admin authentication
requireAuth('admin');

$page_title = 'Admin Dashboard';
$current_page = 'admin_dashboard';

$db = new Database();
$conn = $db->getConnection();

// Get dashboard statistics
$stats = [];
if ($conn) {
    // Total charities
    $stmt = $conn->query("SELECT COUNT(*) FROM charities WHERE is_approved = TRUE");
    $stats['total_charities'] = $stmt->fetchColumn();
    
    // Pending charity applications
    $stmt = $conn->query("SELECT COUNT(*) FROM charity_applications WHERE status = 'pending'");
    $stats['pending_applications'] = $stmt->fetchColumn();
    
    // Total donors
    $stmt = $conn->query("SELECT COUNT(*) FROM users WHERE user_type = 'donor' AND is_verified = TRUE");
    $stats['total_donors'] = $stmt->fetchColumn();
    
    // Active modules
    $stmt = $conn->query("SELECT COUNT(*) FROM modules WHERE status = 'active'");
    $stats['active_modules'] = $stmt->fetchColumn();
    
    // Total donations (today)
    $stmt = $conn->query("
        SELECT COALESCE(SUM(total_amount), 0) 
        FROM donation_sessions 
        WHERE DATE(created_at) = CURDATE() AND status = 'completed'
    ");
    $stats['today_donations'] = $stmt->fetchColumn();
    
    // Total donations (month)
    $stmt = $conn->query("
        SELECT COALESCE(SUM(total_amount), 0) 
        FROM donation_sessions 
        WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) AND status = 'completed'
    ");
    $stats['month_donations'] = $stmt->fetchColumn();
}

// Get recent charity applications
$recent_applications = [];
if ($conn) {
    $stmt = $conn->query("
        SELECT * FROM charity_applications 
        WHERE status = 'pending' 
        ORDER BY applied_at DESC 
        LIMIT 5
    ");
    $recent_applications = $stmt->fetchAll();
}

// Get recent donations
$recent_donations = [];
if ($conn) {
    $stmt = $conn->query("
        SELECT ds.total_amount, ds.created_at, c.display_name as charity_name, u.legal_name as donor_name
        FROM donation_sessions ds
        JOIN charities c ON ds.charity_id = c.id
        JOIN users u ON ds.user_id = u.id
        WHERE ds.status = 'completed'
        ORDER BY ds.created_at DESC 
        LIMIT 10
    ");
    $recent_donations = $stmt->fetchAll();
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
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="charities.php">
                        <i class="fas fa-heart me-2"></i> Charities
                    </a>
                    <a class="nav-link" href="applications.php">
                        <i class="fas fa-clipboard-list me-2"></i> Applications
                        <?php if ($stats['pending_applications'] > 0): ?>
                        <span class="badge bg-danger ms-2"><?php echo $stats['pending_applications']; ?></span>
                        <?php endif; ?>
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
                    <h1 class="h3 fw-bold text-primary">Admin Dashboard</h1>
                    <div class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        <?php echo date('F j, Y'); ?>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $stats['total_charities']; ?></div>
                            <div class="stat-label">Verified Charities</div>
                            <div class="text-primary">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $stats['pending_applications']; ?></div>
                            <div class="stat-label">Pending Applications</div>
                            <div class="text-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $stats['total_donors']; ?></div>
                            <div class="stat-label">Registered Donors</div>
                            <div class="text-info">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $stats['active_modules']; ?></div>
                            <div class="stat-label">Active Modules</div>
                            <div class="text-success">
                                <i class="fas fa-cube"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo formatCurrency($stats['today_donations']); ?></div>
                            <div class="stat-label">Today's Donations</div>
                            <div class="text-primary">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo formatCurrency($stats['month_donations']); ?></div>
                            <div class="stat-label">This Month</div>
                            <div class="text-success">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Recent Charity Applications -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Recent Charity Applications
                                    <?php if ($stats['pending_applications'] > 0): ?>
                                    <span class="badge bg-warning ms-2"><?php echo $stats['pending_applications']; ?> pending</span>
                                    <?php endif; ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($recent_applications)): ?>
                                    <p class="text-muted text-center py-3">No pending applications</p>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($recent_applications as $application): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?php echo sanitizeOutput($application['organization_name']); ?></h6>
                                                <small class="text-muted">BN: <?php echo sanitizeOutput($application['business_number']); ?></small>
                                                <br>
                                                <small class="text-muted">Applied: <?php echo date('M j, Y', strtotime($application['applied_at'])); ?></small>
                                            </div>
                                            <a href="application-detail.php?id=<?php echo $application['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                Review
                                            </a>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="text-center mt-3">
                                    <a href="applications.php" class="btn btn-primary btn-sm">View All Applications</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Donations -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-donate me-2"></i>
                                    Recent Donations
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($recent_donations)): ?>
                                    <p class="text-muted text-center py-3">No recent donations</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Amount</th>
                                                    <th>Charity</th>
                                                    <th>Donor</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recent_donations as $donation): ?>
                                                <tr>
                                                    <td class="fw-bold text-success"><?php echo formatCurrency($donation['total_amount']); ?></td>
                                                    <td><?php echo sanitizeOutput($donation['charity_name']); ?></td>
                                                    <td><?php echo sanitizeOutput($donation['donor_name']); ?></td>
                                                    <td><?php echo date('M j, g:i A', strtotime($donation['created_at'])); ?></td>
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

                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex gap-3 flex-wrap">
                                    <a href="applications.php" class="btn btn-outline-primary">
                                        <i class="fas fa-clipboard-check me-2"></i>Review Applications
                                    </a>
                                    <a href="charities.php" class="btn btn-outline-primary">
                                        <i class="fas fa-plus me-2"></i>Add Charity
                                    </a>
                                    <a href="modules.php" class="btn btn-outline-primary">
                                        <i class="fas fa-cube me-2"></i>Manage Modules
                                    </a>
                                    <a href="reports.php" class="btn btn-outline-primary">
                                        <i class="fas fa-download me-2"></i>Generate Reports
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
</body>
</html>