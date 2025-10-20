<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

requireAuth('admin');

$page_title = 'Manage Donors';
$current_page = 'donors';

$db = new Database();
$conn = $db->getConnection();

$donors = [];
if ($conn) {
    $stmt = $conn->query("
        SELECT id, legal_name, email, address, phone, created_at, is_active 
        FROM users 
        WHERE user_type = 'donor' 
        ORDER BY created_at DESC
    ");
    $donors = $stmt->fetchAll();
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
                    <h1 class="h3 fw-bold text-primary">Manage Donors</h1>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Registered Donors (<?php echo count($donors); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($donors)): ?>
                            <p class="text-muted text-center py-4">No donors found.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Registered</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($donors as $donor): ?>
                                        <tr>
                                            <td><?php echo sanitizeOutput($donor['legal_name']); ?></td>
                                            <td><?php echo sanitizeOutput($donor['email']); ?></td>
                                            <td><?php echo sanitizeOutput($donor['phone'] ?? 'N/A'); ?></td>
                                            <td><?php echo sanitizeOutput(substr($donor['address'] ?? '', 0, 30) . '...'); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($donor['created_at'])); ?></td>
                                            <td>
                                                <?php if ($donor['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="donor-detail.php?id=<?php echo $donor['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
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