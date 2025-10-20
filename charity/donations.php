<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

requireAuth('charity');

$page_title = 'Donations';
$current_page = 'donations';

$db = new Database();
$conn = $db->getConnection();

$charity_id = $_SESSION['user_id'];
$donations = [];

if ($conn) {
    $stmt = $conn->prepare("
        SELECT ds.total_amount, ds.created_at, u.legal_name as donor_name, 
               m.location_name, ds.session_hash
        FROM donation_sessions ds
        JOIN users u ON ds.user_id = u.id
        JOIN modules m ON ds.module_id = m.id
        WHERE ds.charity_id = ? AND ds.status = 'completed'
        ORDER BY ds.created_at DESC
    ");
    $stmt->execute([$charity_id]);
    $donations = $stmt->fetchAll();
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
            <!-- Charity Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link active" href="donations.php">
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
                    <h1 class="h3 fw-bold text-primary">Donation History</h1>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download me-2"></i>Export CSV
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">All Donations (<?php echo count($donations); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($donations)): ?>
                            <p class="text-muted text-center py-4">No donations received yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date & Time</th>
                                            <th>Donor</th>
                                            <th>Location</th>
                                            <th>Amount</th>
                                            <th>Verification Hash</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($donations as $donation): ?>
                                        <tr>
                                            <td><?php echo date('M j, Y g:i A', strtotime($donation['created_at'])); ?></td>
                                            <td><?php echo sanitizeOutput($donation['donor_name']); ?></td>
                                            <td><?php echo sanitizeOutput($donation['location_name']); ?></td>
                                            <td class="fw-bold text-success"><?php echo formatCurrency($donation['total_amount']); ?></td>
                                            <td>
                                                <small class="text-muted" title="<?php echo $donation['session_hash']; ?>">
                                                    <?php echo substr($donation['session_hash'], 0, 12) . '...'; ?>
                                                </small>
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