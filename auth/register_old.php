<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

$page_title = $lang['register'];
$current_page = 'register';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'];
    $legal_name = trim($_POST['legal_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    
    // Charity-specific fields
    $charity_bn = $user_type === 'charity' ? trim($_POST['charity_bn']) : null;
    $charity_description = $user_type === 'charity' ? trim($_POST['charity_description']) : null;
    $charity_website = $user_type === 'charity' ? trim($_POST['charity_website']) : null;
    
    // Validation
    if (empty($legal_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'Please fill in all required fields.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error_message = 'Password must be at least 8 characters long.';
    } else {
        $db = new Database();
        $conn = $db->getConnection();
        
        if ($conn) {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error_message = 'Email address is already registered.';
            } else {
                // Insert user
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $is_verified = $user_type === 'donor'; // Charity needs approval
                
                $stmt = $conn->prepare("
                    INSERT INTO users (user_type, email, password_hash, legal_name, address, phone, charity_bn, charity_description, charity_website, is_verified)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                $result = $stmt->execute([
                    $user_type, $email, $password_hash, $legal_name, $address, $phone, 
                    $charity_bn, $charity_description, $charity_website, $is_verified
                ]);
                
                if ($result) {
                    $user_id = $conn->lastInsertId();
                    
                    // If charity, also insert into charities table (pending approval)
                    if ($user_type === 'charity') {
                        $stmt = $conn->prepare("
                            INSERT INTO charities (user_id, legal_name, display_name, business_number, description, website_url, category)
                            VALUES (?, ?, ?, ?, ?, ?, 'other')
                        ");
                        $stmt->execute([$user_id, $legal_name, $legal_name, $charity_bn, $charity_description, $charity_website]);
                        
                        $success_message = 'Thank you for registering! Your charity application is under review. You will be notified once approved.';
                    } else {
                        $success_message = 'Registration successful! You can now log in to your account.';
                    }
                    
                    $_POST = []; // Clear form
                } else {
                    $error_message = 'Registration failed. Please try again.';
                }
            }
        } else {
            $error_message = 'Database connection error. Please try again.';
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
    <div class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="auth-card">
                        <div class="auth-header">
                            <h2 class="fw-bold mb-2"><?php echo $lang['register']; ?></h2>
                            <p class="mb-0">Créez votre compte AidVeritas</p>
                        </div>
                        <div class="card-body p-4">
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
                                <!-- User Type Selection -->
                                <div class="row mb-4">
                                    <div class="col">
                                        <label class="form-label fw-bold">I am registering as:</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="user_type" 
                                                       id="donor" value="donor" checked 
                                                       onchange="toggleCharityFields()">
                                                <label class="form-check-label" for="donor">
                                                    <i class="fas fa-user me-1"></i> Donor
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="user_type" 
                                                       id="charity" value="charity"
                                                       onchange="toggleCharityFields()">
                                                <label class="form-check-label" for="charity">
                                                    <i class="fas fa-heart me-1"></i> Charity Organization
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="legal_name" class="form-label"><?php echo $lang['legal_name']; ?> *</label>
                                        <input type="text" class="form-control" id="legal_name" name="legal_name" 
                                               value="<?php echo htmlspecialchars($_POST['legal_name'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label"><?php echo $lang['email']; ?> *</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="password" class="form-label"><?php echo $lang['password']; ?> *</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="confirm_password" class="form-label"><?php echo $lang['confirm_password']; ?> *</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="address" class="form-label"><?php echo $lang['address']; ?></label>
                                        <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label"><?php echo $lang['phone']; ?></label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                    </div>

                                    <!-- Charity-specific fields (hidden by default) -->
                                    <div id="charityFields" class="charity-fields" style="display: none;">
                                        <div class="col-12">
                                            <hr>
                                            <h5 class="text-primary">Charity Information</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="charity_bn" class="form-label"><?php echo $lang['charity_bn']; ?> *</label>
                                            <input type="text" class="form-control" id="charity_bn" name="charity_bn"
                                                   value="<?php echo htmlspecialchars($_POST['charity_bn'] ?? ''); ?>">
                                        </div>
                                        <div class="col-12">
                                            <label for="charity_description" class="form-label"><?php echo $lang['charity_description']; ?></label>
                                            <textarea class="form-control" id="charity_description" name="charity_description" rows="3"><?php echo htmlspecialchars($_POST['charity_description'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label for="charity_website" class="form-label"><?php echo $lang['charity_website']; ?></label>
                                            <input type="url" class="form-control" id="charity_website" name="charity_website"
                                                   value="<?php echo htmlspecialchars($_POST['charity_website'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-user-plus me-2"></i>
                                                <?php echo $lang['register']; ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <hr class="my-4">
                            
                            <div class="text-center">
                                <p class="mb-0"><?php echo $lang['has_account']; ?>
                                    <a href="login.php" class="text-decoration-none fw-bold"><?php echo $lang['login']; ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCharityFields() {
            const charityRadio = document.getElementById('charity');
            const charityFields = document.getElementById('charityFields');
            const charityInputs = charityFields.querySelectorAll('input, textarea');
            
            if (charityRadio.checked) {
                charityFields.style.display = 'block';
                charityInputs.forEach(input => input.required = true);
            } else {
                charityFields.style.display = 'none';
                charityInputs.forEach(input => input.required = false);
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', toggleCharityFields);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>