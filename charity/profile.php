<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

requireAuth('charity');

$page_title = 'Profile Settings';
$current_page = 'profile';

$db = new Database();
$conn = $db->getConnection();

$charity_id = $_SESSION['user_id'];
$charity = [];

if ($conn) {
    $stmt = $conn->prepare("
        SELECT c.*, u.email, u.phone, u.address 
        FROM charities c 
        JOIN users u ON c.user_id = u.id 
        WHERE u.id = ?
    ");
    $stmt->execute([$charity_id]);
    $charity = $stmt->fetch();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $display_name = trim($_POST['display_name']);
    $description = trim($_POST['description']);
    $website_url = trim($_POST['website_url']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Update charity table
    $stmt = $conn->prepare("UPDATE charities SET display_name = ?, description = ?, website_url = ? WHERE user_id = ?");
    $stmt->execute([$display_name, $description, $website_url, $charity_id]);
    
    // Update user table
    $stmt = $conn->prepare("UPDATE users SET phone = ?, address = ? WHERE id = ?");
    $stmt->execute([$phone, $address, $charity_id]);
    
    $success_message = "Profile updated successfully!";
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
                    <a class="nav-link" href="donations.php">
                        <i class="fas fa-donate me-2"></i> Donations
                    </a>
                    <a class="nav-link" href="donors.php">
                        <i class="fas fa-users me-2"></i> Donors
                    </a>
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-chart-bar me-2"></i> Reports
                    </a>
                    <a class="nav-link active" href="profile.php">
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
                    <h1 class="h3 fw-bold text-primary">Profile Settings</h1>
                </div>

                <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Charity Information</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Legal Name</label>
                                            <input type="text" class="form-control" value="<?php echo sanitizeOutput($charity['legal_name']); ?>" readonly>
                                            <small class="form-text text-muted">Legal name cannot be changed</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Business Number</label>
                                            <input type="text" class="form-control" value="<?php echo sanitizeOutput($charity['business_number']); ?>" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Display Name *</label>
                                            <input type="text" class="form-control" name="display_name" 
                                                   value="<?php echo sanitizeOutput($charity['display_name']); ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description" rows="4"><?php echo sanitizeOutput($charity['description']); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Website URL</label>
                                            <input type="url" class="form-control" name="website_url" 
                                                   value="<?php echo sanitizeOutput($charity['website_url']); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="<?php echo sanitizeOutput($charity['email']); ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone</label>
                                            <input type="tel" class="form-control" name="phone" 
                                                   value="<?php echo sanitizeOutput($charity['phone']); ?>">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Address</label>
                                            <textarea class="form-control" name="address" rows="3"><?php echo sanitizeOutput($charity['address']); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" name="update_profile" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Profile
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Account Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Verification Status:</span>
                                    <span class="badge bg-success">Verified</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Account Status:</span>
                                    <span class="badge bg-success">Active</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Approved Date:</span>
                                    <span><?php echo $charity['approval_date'] ? date('M j, Y', strtotime($charity['approval_date'])) : 'N/A'; ?></span>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        For major changes, please contact support.
                                    </small>
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