<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'login':
                // Mobile app login
                $email = $input['email'];
                $password = $input['password'];
                
                $stmt = $conn->prepare("SELECT id, user_type, password_hash, is_verified FROM users WHERE email = ? AND is_active = TRUE");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    echo json_encode(['success' => true, 'user_id' => $user['id'], 'user_type' => $user['user_type']]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
                }
                break;
                
            default:
                echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    }
}
?>