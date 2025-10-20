<?php
// Admin Sidebar Component
?>
<nav class="nav flex-column">
    <a class="nav-link <?php echo $current_page === 'admin_dashboard' ? 'active' : ''; ?>" href="dashboard.php">
        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
    </a>
    <a class="nav-link <?php echo $current_page === 'charities' ? 'active' : ''; ?>" href="charities.php">
        <i class="fas fa-heart me-2"></i> Charities
    </a>
    <a class="nav-link <?php echo $current_page === 'applications' ? 'active' : ''; ?>" href="applications.php">
        <i class="fas fa-clipboard-list me-2"></i> Applications
    </a>
    <a class="nav-link <?php echo $current_page === 'donors' ? 'active' : ''; ?>" href="donors.php">
        <i class="fas fa-users me-2"></i> Donors
    </a>
    <a class="nav-link <?php echo $current_page === 'modules' ? 'active' : ''; ?>" href="modules.php">
        <i class="fas fa-cube me-2"></i> Modules
    </a>
    <a class="nav-link <?php echo $current_page === 'receipts' ? 'active' : ''; ?>" href="receipts.php">
        <i class="fas fa-receipt me-2"></i> Tax Receipts
    </a>
    <a class="nav-link <?php echo $current_page === 'reports' ? 'active' : ''; ?>" href="reports.php">
        <i class="fas fa-chart-bar me-2"></i> Reports
    </a>
    <hr>
    <a class="nav-link" href="../auth/logout.php">
        <i class="fas fa-sign-out-alt me-2"></i> Logout
    </a>
</nav>