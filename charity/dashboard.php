<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Require charity authentication
requireAuth('charity');

$page_title = 'Charity Dashboard';
$current_page = 'charity_dashboard';

$db = new Database();
$conn = $db->getConnection();

// Get charity information
$charity = [];
$stats = [];
if ($conn) {
    $charity_id = $_SESSION['user_id'];
    
    // Get charity details
    $stmt = $conn->prepare("
        SELECT c.*, u.email, u.phone, u.address 
        FROM charities c 
        JOIN users u ON c.user_id = u.id 
        WHERE u.id = ?
    ");
    $stmt->execute([$charity_id]);
    $charity = $stmt->fetch();
    
    if ($charity) {
        // Get donation statistics
        $stmt = $conn->prepare("
            SELECT 
                COUNT(*) as total_donations,
                COALESCE(SUM(ds.total_amount), 0) as total_amount,
                COALESCE(SUM(CASE WHEN DATE(ds.created_at) = CURDATE() THEN ds.total_amount ELSE 0 END), 0) as today_amount,
                COALESCE(SUM(CASE WHEN YEAR(ds.created_at) = YEAR(CURDATE()) AND MONTH(ds.created_at) = MONTH(CURDATE()) THEN ds.total_amount ELSE 0 END), 0) as month_amount,
                COUNT(DISTINCT ds.user_id) as unique_donors
            FROM donation_sessions ds
            WHERE ds.charity_id = ? AND ds.status = 'completed'
        ");
        $stmt->execute([$charity['id']]);
        $stats = $stmt->fetch();
        
        // Get recent donations
        $stmt = $conn->prepare("
            SELECT ds.total_amount, ds.created_at, u.legal_name as donor_name, m.location_name
            FROM donation_sessions ds
            JOIN users u ON ds.user_id = u.id
            JOIN modules m ON ds.module_id = m.id
            WHERE ds.charity_id = ? AND ds.status = 'completed'
            ORDER BY ds.created_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$charity['id']]);
        $recent_donations = $stmt->fetchAll();
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
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="donations.php">
                        <i class="fas fa-donate me-2"></i> Donations
                    </a>
                    <a class="nav-link" href="donors.php">
                        <i class="fas fa-users me-2"></i> Donors
                    </a>
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-chart-bar me-2"></i> Reports
                    </a>
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-cog me-2"></i> Profile Settings
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
                    <div>
                        <h1 class="h3 fw-bold text-primary mb-1">Welcome, <?php echo sanitizeOutput($charity['display_name']); ?></h1>
                        <p class="text-muted mb-0">Business Number: <?php echo sanitizeOutput($charity['business_number']); ?></p>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">Last updated</div>
                        <div class="fw-bold"><?php echo date('F j, Y g:i A'); ?></div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo formatCurrency($stats['total_amount'] ?? 0); ?></div>
                            <div class="stat-label">Total Donations</div>
                            <div class="text-primary">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $stats['total_donations'] ?? 0; ?></div>
                            <div class="stat-label">Total Donations</div>
                            <div class="text-info">
                                <i class="fas fa-donate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo $stats['unique_donors'] ?? 0; ?></div>
                            <div class="stat-label">Unique Donors</div>
                            <div class="text-success">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo formatCurrency($stats['today_amount'] ?? 0); ?></div>
                            <div class="stat-label">Today's Donations</div>
                            <div class="text-warning">
                                <i class="fas fa-sun"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo formatCurrency($stats['month_amount'] ?? 0); ?></div>
                            <div class="stat-label">This Month</div>
                            <div class="text-danger">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="stat-card">
                            <div class="stat-number">
                                <?php 
                                $avg_donation = $stats['total_donations'] > 0 ? $stats['total_amount'] / $stats['total_donations'] : 0;
                                echo formatCurrency($avg_donation);
                                ?>
                            </div>
                            <div class="stat-label">Average Donation</div>
                            <div class="text-info">
                                <i class="fas fa-calculator"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Recent Donations -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history me-2"></i>
                                    Recent Donations
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($recent_donations)): ?>
                                    <p class="text-muted text-center py-4">No recent donations yet.</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Date & Time</th>
                                                    <th>Donor</th>
                                                    <th>Location</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recent_donations as $donation): ?>
                                                <tr>
                                                    <td><?php echo date('M j, g:i A', strtotime($donation['created_at'])); ?></td>
                                                    <td><?php echo sanitizeOutput($donation['donor_name']); ?></td>
                                                    <td><?php echo sanitizeOutput($donation['location_name']); ?></td>
                                                    <td class="fw-bold text-success"><?php echo formatCurrency($donation['total_amount']); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="donations.php" class="btn btn-primary btn-sm">View All Donations</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Info -->
                    <div class="col-lg-4">
                        <!-- Charity Status -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Charity Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Verification Status:</span>
                                    <span class="badge bg-success">Verified</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Account Status:</span>
                                    <span class="badge bg-success">Active</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Approved Date:</span>
                                    <span><?php echo $charity['approval_date'] ? date('M j, Y', strtotime($charity['approval_date'])) : 'N/A'; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="donations.php" class="btn btn-outline-primary btn-sm text-start">
                                        <i class="fas fa-donate me-2"></i>View All Donations
                                    </a>
                                    <a href="reports.php" class="btn btn-outline-primary btn-sm text-start">
                                        <i class="fas fa-chart-bar me-2"></i>Generate Reports
                                    </a>
                                    <a href="profile.php" class="btn btn-outline-primary btn-sm text-start">
                                        <i class="fas fa-cog me-2"></i>Update Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Summary -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">This Month's Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <div class="text-primary fw-bold fs-4"><?php echo formatCurrency($stats['month_amount'] ?? 0); ?></div>
                                            <div class="text-muted">Total This Month</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <div class="text-primary fw-bold fs-4">
                                                <?php
                                                $month_donations = 0; // This would be calculated from database
                                                echo $month_donations;
                                                ?>
                                            </div>
                                            <div class="text-muted">Donations This Month</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <div class="text-primary fw-bold fs-4">
                                                <?php
                                                $new_donors = 0; // This would be calculated from database
                                                echo $new_donors;
                                                ?>
                                            </div>
                                            <div class="text-muted">New Donors This Month</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <div class="text-primary fw-bold fs-4">
                                                <?php
                                                $avg_month = $month_donations > 0 ? $stats['month_amount'] / $month_donations : 0;
                                                echo formatCurrency($avg_month);
                                                ?>
                                            </div>
                                            <div class="text-muted">Average This Month</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>