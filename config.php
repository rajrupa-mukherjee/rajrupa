<?php
/**
 * Configuration file for Rajrupa Mukherjee Website
 * Database and application settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'rajrupa');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application Configuration
define('SITE_NAME', 'Rajrupa Mukherjee');
define('SITE_URL', 'http://localhost/rajrupa');
define('SITE_EMAIL', 'rajrupa.m79@gmail.com');
define('ADMIN_EMAIL', 'admin@rajrupa.com');

// Security Settings
define('ENCRYPTION_KEY', 'your-secret-encryption-key-here');
define('SESSION_LIFETIME', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// File Upload Settings
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('ALLOWED_VIDEO_TYPES', ['mp4', 'avi', 'mov', 'wmv']);

// Pagination Settings
define('ITEMS_PER_PAGE', 12);
define('COMMENTS_PER_PAGE', 10);

// Cache Settings
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 3600); // 1 hour

// Email Settings (for contact forms)
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_FROM_EMAIL', SITE_EMAIL);
define('SMTP_FROM_NAME', SITE_NAME);

// Social Media Links
define('FACEBOOK_URL', '');
define('INSTAGRAM_URL', '');
define('YOUTUBE_URL', '');
define('TWITTER_URL', '');

// SEO Settings
define('DEFAULT_META_TITLE', 'Rajrupa Mukherjee | Renowned Bharatanatyam Dancer');
define('DEFAULT_META_DESCRIPTION', 'Rajrupa Mukherjee - Renowned Bharatanatyam Dancer in India, Bharatanatyam Dance Teacher in Kolkata');
define('DEFAULT_META_KEYWORDS', 'Rajrupa Mukherjee, Bharatanatyam, Indian Classical Dance, Kolkata');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include functions
require_once __DIR__ . '/functions.php';

// Connect to database
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Initialize site settings
function getSiteSettings() {
    global $pdo;
    
    static $settings = null;
    if ($settings === null) {
        try {
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            $result = $stmt->fetchAll();
            $settings = [];
            foreach ($result as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (PDOException $e) {
            error_log("Failed to load site settings: " . $e->getMessage());
            $settings = [];
        }
    }
    
    return $settings;
}

// Get SEO metadata
function getSEOMetadata($page) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM seo_metadata WHERE page_name = ?");
        $stmt->execute([$page]);
        $metadata = $stmt->fetch();
        
        if (!$metadata) {
            // Return default metadata
            return [
                'meta_title' => DEFAULT_META_TITLE,
                'meta_description' => DEFAULT_META_DESCRIPTION,
                'meta_keywords' => DEFAULT_META_KEYWORDS,
                'og_title' => DEFAULT_META_TITLE,
                'og_description' => DEFAULT_META_DESCRIPTION,
                'og_image' => ''
            ];
        }
        
        return $metadata;
    } catch (PDOException $e) {
        error_log("Failed to load SEO metadata: " . $e->getMessage());
        return [
            'meta_title' => DEFAULT_META_TITLE,
            'meta_description' => DEFAULT_META_DESCRIPTION,
            'meta_keywords' => DEFAULT_META_KEYWORDS,
            'og_title' => DEFAULT_META_TITLE,
            'og_description' => DEFAULT_META_DESCRIPTION,
            'og_image' => ''
        ];
    }
}

// Security functions
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}

// File upload helper
function handleFileUpload($file, $destination, $allowedTypes = []) {
    if (empty($allowedTypes)) {
        $allowedTypes = array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_VIDEO_TYPES);
    }
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'error' => 'No file uploaded or upload error'];
    }
    
    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'File size exceeds maximum limit'];
    }
    
    // Check file type
    $fileInfo = pathinfo($file['name']);
    $extension = strtolower($fileInfo['extension']);
    
    if (!in_array($extension, $allowedTypes)) {
        return ['success' => false, 'error' => 'File type not allowed'];
    }
    
    // Generate unique filename
    $filename = uniqid() . '.' . $extension;
    $filepath = $destination . $filename;
    
    // Create directory if it doesn't exist
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    // Move file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'filepath' => $filepath];
    } else {
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
}

// Email helper
function sendEmail($to, $subject, $message, $from = null) {
    $from = $from ?: SMTP_FROM_EMAIL;
    $headers = [
        'From: ' . $from,
        'Reply-To: ' . $from,
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8'
    ];
    
    return mail($to, $subject, $message, implode("\r\n", $headers));
}

// Pagination helper
function getPagination($totalItems, $itemsPerPage = ITEMS_PER_PAGE, $currentPage = 1) {
    $totalPages = ceil($totalItems / $itemsPerPage);
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    return [
        'total_items' => $totalItems,
        'items_per_page' => $itemsPerPage,
        'total_pages' => $totalPages,
        'current_page' => $currentPage,
        'offset' => $offset,
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'prev_page' => $currentPage - 1,
        'next_page' => $currentPage + 1
    ];
}

// Cache helper
function cacheGet($key) {
    if (!CACHE_ENABLED) {
        return null;
    }
    
    $cacheFile = __DIR__ . '/cache/' . md5($key) . '.cache';
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < CACHE_LIFETIME) {
        return unserialize(file_get_contents($cacheFile));
    }
    
    return null;
}

function cacheSet($key, $data) {
    if (!CACHE_ENABLED) {
        return;
    }
    
    $cacheDir = __DIR__ . '/cache';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    
    $cacheFile = $cacheDir . '/' . md5($key) . '.cache';
    file_put_contents($cacheFile, serialize($data));
}

// Logging helper
function logActivity($action, $details = '') {
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'user_id' => $_SESSION['user_id'] ?? null,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'action' => $action,
        'details' => $details
    ];
    
    $logFile = __DIR__ . '/logs/activity.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
}
?>
