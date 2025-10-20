<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

$page_title = $lang['login'];
$current_page = 'login';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error_message = $current_language === 'fr' ? 'Veuillez saisir votre email et mot de passe.' : 'Please enter both email and password.';
    } else {
        $db = new Database();
        $conn = $db->getConnection();
        
        if ($conn) {
            $stmt = $conn->prepare("
                SELECT id, user_type, email, password_hash, legal_name, is_active, is_verified 
                FROM users 
                WHERE email = ? AND is_active = TRUE
            ");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['is_verified']) {
                    // Login successful
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = $user['user_type'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['legal_name'];
                    
                    // Redirect based on user type
                    if ($user['user_type'] === 'admin') {
                        header('Location: ../admin/dashboard.php');
                    } elseif ($user['user_type'] === 'charity') {
                        header('Location: ../charity/dashboard.php');
                    } else {
                        header('Location: ../index.php');
                    }
                    exit;
                } else {
                    $error_message = $current_language === 'fr' ? 'Veuillez vérifier votre adresse email avant de vous connecter.' : 'Please verify your email address before logging in.';
                }
            } else {
                $error_message = $current_language === 'fr' ? 'Email ou mot de passe invalide.' : 'Invalid email or password.';
            }
        } else {
            $error_message = $current_language === 'fr' ? 'Erreur de connexion à la base de données. Veuillez réessayer.' : 'Database connection error. Please try again.';
        }
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

    <div class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="auth-card">
                        <div class="auth-header">
                            <h2 class="fw-bold mb-2"><?php echo $page_title; ?></h2>
                            <p class="mb-0">
                                <?php echo $current_language === 'fr' ? 'Connectez-vous à votre compte AidVeritas' : 'Sign in to your AidVeritas account'; ?>
                            </p>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($error_message): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $error_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="email" class="form-label"><?php echo $lang['email']; ?></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label"><?php echo $lang['password']; ?></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">
                                        <?php echo $current_language === 'fr' ? 'Se souvenir de moi' : 'Remember me'; ?>
                                    </label>
                                </div>
                                
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        <?php echo $lang['login']; ?>
                                    </button>
                                </div>
                                
                                <div class="text-center">
                                    <a href="forgot-password.php" class="text-decoration-none">
                                        <?php echo $lang['forgot_password']; ?>
                                    </a>
                                </div>
                            </form>
                            
                            <hr class="my-4">
                            
                            <div class="text-center">
                                <p class="mb-0"><?php echo $lang['no_account']; ?>
                                    <a href="register.php" class="text-decoration-none fw-bold"><?php echo $lang['register']; ?></a>
                                </p>
                            </div>

                            <!-- Add a home link -->
                            <div class="text-center mt-3">
                                <a href="../index.php" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    <?php echo $current_language === 'fr' ? 'Retour à l\'accueil' : 'Back to Home'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>