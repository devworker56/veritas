<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$page_title = $lang['contact_title'];
$current_page = 'contact';

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // In production, you would send an email or save to database
        $success_message = 'Thank you for your message! We will get back to you within 24 hours.';
        
        // Reset form
        $_POST = [];
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
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
<section class="page-header-banner">
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="display-4 fw-bold"><?php echo $lang['contact_title']; ?></h1>
                <p class="lead"><?php echo $lang['contact_info']; ?></p>
            </div>
        </div>
    </div>
</section>

    <!-- Contact Form -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-body p-5">
                            <?php if ($success_message): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $success_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($error_message): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label"><?php echo $lang['name']; ?> *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label"><?php echo $lang['email']; ?> *</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="subject" class="form-label">Sujet *</label>
                                        <input type="text" class="form-control" id="subject" name="subject"
                                               value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label"><?php echo $lang['message']; ?> *</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            <?php echo $lang['send_message']; ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="text-primary mb-3">
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                    </div>
                    <h5>Adresse</h5>
                    <p class="text-muted">123 Rue de la Charité<br>Montréal, QC H3A 1A1</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="text-primary mb-3">
                        <i class="fas fa-phone fa-2x"></i>
                    </div>
                    <h5>Téléphone</h5>
                    <p class="text-muted">+1 (514) 123-4567</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="text-primary mb-3">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                    <h5>Email</h5>
                    <p class="text-muted">info@aidveritas.com</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>