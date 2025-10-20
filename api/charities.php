<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all approved charities for mobile app
    $stmt = $conn->query("
        SELECT c.id, c.display_name, c.description, c.website_url, c.category 
        FROM charities c 
        WHERE c.is_approved = TRUE 
        ORDER BY c.display_name
    ");
    $charities = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'charities' => $charities]);
}
?>