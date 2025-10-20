<?php
// Header component with bilingual navigation
?>
<header class="fixed-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light py-2">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold text-primary" href="index.php">
                <i class="fas fa-shield-alt me-2"></i>
                AidVeritas
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'home' ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-home me-1"></i>
                            <?php echo $lang['home']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'about' ? 'active' : ''; ?>" href="about.php">
                            <i class="fas fa-info-circle me-1"></i>
                            <?php echo $lang['about']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'charities' ? 'active' : ''; ?>" href="charities.php">
                            <i class="fas fa-heart me-1"></i>
                            <?php echo $lang['charities']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'resources' ? 'active' : ''; ?>" href="resources.php">
                            <i class="fas fa-book me-1"></i>
                            <?php echo $lang['resources']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'contact' ? 'active' : ''; ?>" href="contact.php">
                            <i class="fas fa-envelope me-1"></i>
                            <?php echo $lang['contact']; ?>
                        </a>
                    </li>
                </ul>

                <!-- Right side items -->
                <div class="d-flex align-items-center">
                    <!-- Language Switcher -->
                    <div class="dropdown me-3">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-language me-1"></i>
                            <?php echo strtoupper($current_language); ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?lang=fr&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Français (FR)</a></li>
                            <li><a class="dropdown-item" href="?lang=en&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">English (EN)</a></li>
                        </ul>
                    </div>

                    <!-- Auth Buttons -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?php echo $_SESSION['user_name']; ?>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if ($_SESSION['user_type'] === 'admin'): ?>
                            <li><a class="dropdown-item" href="admin/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                            <?php elseif ($_SESSION['user_type'] === 'charity'): ?>
                            <li><a class="dropdown-item" href="charity/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Charity Dashboard</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i><?php echo $lang['logout']; ?></a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <div class="d-flex gap-2">
                        <a href="auth/login.php" class="btn btn-outline-primary"><?php echo $lang['login']; ?></a>
                        <a href="auth/register.php" class="btn btn-primary"><?php echo $lang['register']; ?></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Spacer for fixed header -->
<div style="height: 80px;"></div>