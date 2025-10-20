<?php
// Utility functions for the AidVeritas system

/**
 * Generate a unique receipt number
 */
function generateReceiptNumber($conn) {
    $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'current_receipt_number'");
    $stmt->execute();
    $current_number = $stmt->fetchColumn();
    
    $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'tax_receipt_prefix'");
    $stmt->execute();
    $prefix = $stmt->fetchColumn();
    
    $receipt_number = $prefix . $current_number;
    
    // Increment the current number
    $new_number = intval($current_number) + 1;
    $stmt = $conn->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = 'current_receipt_number'");
    $stmt->execute([$new_number]);
    
    return $receipt_number;
}

/**
 * Generate SHA-256 hash for transaction verification
 */
function generateTransactionHash($session_id, $amount, $previous_hash) {
    $data = $session_id . $amount . $previous_hash . time();
    return hash('sha256', $data);
}

/**
 * Check if user is logged in and has required role
 */
function requireAuth($required_role = null) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit;
    }
    
    if ($required_role && $_SESSION['user_type'] !== $required_role) {
        http_response_code(403);
        die('Access denied. Insufficient permissions.');
    }
    
    return true;
}

/**
 * Sanitize output for display
 */
function sanitizeOutput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Format currency for display
 */
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Get user's display name
 */
function getUserDisplayName($conn, $user_id) {
    $stmt = $conn->prepare("SELECT legal_name FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}
?>