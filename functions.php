<?php
/**
 * Helper functions for Rajrupa Mukherjee Website
 */

// Database query helpers
function fetchAll($query, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return [];
    }
}

function fetchOne($query, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return null;
    }
}

function executeQuery($query, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($query);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return false;
    }
}

function getLastInsertId() {
    global $pdo;
    return $pdo->lastInsertId();
}

// Performance functions
function getPerformances($limit = null, $featured = false) {
    $cacheKey = "performances_" . ($featured ? 'featured_' : '') . ($limit ?? 'all');
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT * FROM performances WHERE 1=1";
    $params = [];
    
    if ($featured) {
        $query .= " AND is_featured = TRUE";
    }
    
    $query .= " ORDER BY year DESC, created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $results = fetchAll($query, $params);
    cacheSet($cacheKey, $results);
    
    return $results;
}

function getPerformanceById($id) {
    $query = "SELECT * FROM performances WHERE id = ?";
    return fetchOne($query, [$id]);
}

function getPerformancesByYear($year) {
    $cacheKey = "performances_year_$year";
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT * FROM performances WHERE year = ? ORDER BY created_at DESC";
    $results = fetchAll($query, [$year]);
    cacheSet($cacheKey, $results);
    
    return $results;
}

// Award functions
function getAwards($limit = null, $featured = false) {
    $cacheKey = "awards_" . ($featured ? 'featured_' : '') . ($limit ?? 'all');
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT * FROM awards WHERE 1=1";
    $params = [];
    
    if ($featured) {
        $query .= " AND is_featured = TRUE";
    }
    
    $query .= " ORDER BY year DESC, created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $results = fetchAll($query, $params);
    cacheSet($cacheKey, $results);
    
    return $results;
}

function getAwardById($id) {
    $query = "SELECT * FROM awards WHERE id = ?";
    return fetchOne($query, [$id]);
}

// Training functions
function getTrainingWorkshops($type = null, $active = true) {
    $cacheKey = "training_" . ($type ?? 'all') . '_' . ($active ? 'active' : 'all');
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT * FROM training_workshops WHERE 1=1";
    $params = [];
    
    if ($type) {
        $query .= " AND type = ?";
        $params[] = $type;
    }
    
    if ($active) {
        $query .= " AND is_active = TRUE";
    }
    
    $query .= " ORDER BY start_date DESC, created_at DESC";
    
    $results = fetchAll($query, $params);
    cacheSet($cacheKey, $results);
    
    return $results;
}

function getTrainingById($id) {
    $query = "SELECT * FROM training_workshops WHERE id = ?";
    return fetchOne($query, [$id]);
}

// Gallery functions
function getGalleryCategories() {
    $cacheKey = "gallery_categories";
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT * FROM gallery_categories ORDER BY name";
    $results = fetchAll($query);
    cacheSet($cacheKey, $results);
    
    return $results;
}

function getGalleryItems($categoryId = null, $type = null, $featured = false, $limit = null) {
    $cacheKey = "gallery_items_" . ($categoryId ?? 'all') . '_' . ($type ?? 'all') . '_' . ($featured ? 'featured' : 'all') . '_' . ($limit ?? 'all');
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT gi.*, gc.name as category_name FROM gallery_items gi 
              LEFT JOIN gallery_categories gc ON gi.category_id = gc.id WHERE 1=1";
    $params = [];
    
    if ($categoryId) {
        $query .= " AND gi.category_id = ?";
        $params[] = $categoryId;
    }
    
    if ($type) {
        $query .= " AND gi.file_type = ?";
        $params[] = $type;
    }
    
    if ($featured) {
        $query .= " AND gi.is_featured = TRUE";
    }
    
    $query .= " ORDER BY gi.display_order ASC, gi.created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $results = fetchAll($query, $params);
    cacheSet($cacheKey, $results);
    
    return $results;
}

function getGalleryItemById($id) {
    $query = "SELECT gi.*, gc.name as category_name FROM gallery_items gi 
              LEFT JOIN gallery_categories gc ON gi.category_id = gc.id WHERE gi.id = ?";
    return fetchOne($query, [$id]);
}

// Press functions
function getPressClippings($featured = false, $limit = null) {
    $cacheKey = "press_clippings_" . ($featured ? 'featured_' : '') . ($limit ?? 'all');
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT * FROM press_clippings WHERE 1=1";
    $params = [];
    
    if ($featured) {
        $query .= " AND is_featured = TRUE";
    }
    
    $query .= " ORDER BY publication_date DESC, created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $results = fetchAll($query, $params);
    cacheSet($cacheKey, $results);
    
    return $results;
}

function getPressClippingById($id) {
    $query = "SELECT * FROM press_clippings WHERE id = ?";
    return fetchOne($query, [$id]);
}

// Comments functions
function getComments($approved = true, $featured = false, $limit = null) {
    $cacheKey = "comments_" . ($approved ? 'approved_' : 'all_') . ($featured ? 'featured' : 'all') . '_' . ($limit ?? 'all');
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT * FROM comments WHERE 1=1";
    $params = [];
    
    if ($approved) {
        $query .= " AND is_approved = TRUE";
    }
    
    if ($featured) {
        $query .= " AND is_featured = TRUE";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT ?";
        $params[] = $limit;
    }
    
    $results = fetchAll($query, $params);
    cacheSet($cacheKey, $results);
    
    return $results;
}

function getCommentById($id) {
    $query = "SELECT * FROM comments WHERE id = ?";
    return fetchOne($query, [$id]);
}

function addComment($name, $email, $comment, $rating = 5) {
    $query = "INSERT INTO comments (name, email, comment_text, rating, is_approved) VALUES (?, ?, ?, ?, FALSE)";
    $params = [sanitizeInput($name), sanitizeInput($email), sanitizeInput($comment), (int)$rating];
    
    if (executeQuery($query, $params)) {
        logActivity('comment_added', "New comment from $name");
        return getLastInsertId();
    }
    
    return false;
}

// Guru functions
function getGuruInfo($primary = false) {
    $cacheKey = "guru_info_" . ($primary ? 'primary' : 'all');
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $query = "SELECT * FROM guru_info WHERE 1=1";
    $params = [];
    
    if ($primary) {
        $query .= " AND is_primary = TRUE";
    }
    
    $query .= " ORDER BY is_primary DESC, created_at ASC";
    
    $results = fetchAll($query, $params);
    cacheSet($cacheKey, $results);
    
    return $results;
}

function getGuruById($id) {
    $query = "SELECT * FROM guru_info WHERE id = ?";
    return fetchOne($query, [$id]);
}

// Contact functions
function addContactMessage($name, $email, $phone, $subject, $message) {
    $query = "INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
    $params = [
        sanitizeInput($name),
        sanitizeInput($email),
        sanitizeInput($phone),
        sanitizeInput($subject),
        sanitizeInput($message)
    ];
    
    if (executeQuery($query, $params)) {
        logActivity('contact_message', "New contact message from $name");
        
        // Send email notification
        $emailSubject = "New Contact Message from Website";
        $emailBody = "
            <h2>New Contact Message</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message) . "</p>
        ";
        
        sendEmail(SITE_EMAIL, $emailSubject, $emailBody);
        
        return getLastInsertId();
    }
    
    return false;
}

function getContactMessages($unread = null, $limit = null) {
    $query = "SELECT * FROM contact_messages WHERE 1=1";
    $params = [];
    
    if ($unread !== null) {
        $query .= " AND is_read = " . ($unread ? 'TRUE' : 'FALSE');
    }
    
    $query .= " ORDER BY created_at DESC";
    
    if ($limit) {
        $query .= " LIMIT ?";
        $params[] = $limit;
    }
    
    return fetchAll($query, $params);
}

function markMessageAsRead($id) {
    $query = "UPDATE contact_messages SET is_read = TRUE WHERE id = ?";
    return executeQuery($query, [$id]);
}

// Statistics functions
function getDashboardStats() {
    $cacheKey = "dashboard_stats";
    $cached = cacheGet($cacheKey);
    
    if ($cached) {
        return $cached;
    }
    
    $stats = [
        'total_performances' => 0,
        'total_awards' => 0,
        'total_gallery_items' => 0,
        'total_comments' => 0,
        'unread_messages' => 0,
        'pending_comments' => 0
    ];
    
    // Get counts
    $queries = [
        'total_performances' => "SELECT COUNT(*) as count FROM performances",
        'total_awards' => "SELECT COUNT(*) as count FROM awards",
        'total_gallery_items' => "SELECT COUNT(*) as count FROM gallery_items",
        'total_comments' => "SELECT COUNT(*) as count FROM comments",
        'unread_messages' => "SELECT COUNT(*) as count FROM contact_messages WHERE is_read = FALSE",
        'pending_comments' => "SELECT COUNT(*) as count FROM comments WHERE is_approved = FALSE"
    ];
    
    foreach ($queries as $key => $query) {
        $result = fetchOne($query);
        $stats[$key] = $result['count'] ?? 0;
    }
    
    cacheSet($cacheKey, $stats);
    return $stats;
}

// URL helpers
function baseUrl($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function assetUrl($path) {
    return baseUrl('assets/' . ltrim($path, '/'));
}

function uploadUrl($path) {
    return baseUrl('uploads/' . ltrim($path, '/'));
}

// String helpers
function truncateText($text, $length = 150, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    
    return $text . $suffix;
}

function slugify($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    
    return $text;
}

function formatDate($date, $format = 'F j, Y') {
    if (!$date) return '';
    
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    return date($format, $timestamp);
}

// Form helpers
function getOldInput($field, $default = '') {
    return $_SESSION['old_input'][$field] ?? $default;
}

function getError($field) {
    return $_SESSION['errors'][$field] ?? '';
}

function hasError($field) {
    return !empty($_SESSION['errors'][$field]);
}

function clearFormSession() {
    unset($_SESSION['old_input']);
    unset($_SESSION['errors']);
    unset($_SESSION['success']);
}

// Validation helpers
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateRequired($fields) {
    $errors = [];
    
    foreach ($fields as $field => $value) {
        if (empty(trim($value))) {
            $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }
    
    return $errors;
}

function validateLength($field, $value, $min, $max) {
    $length = strlen(trim($value));
    
    if ($length < $min) {
        return ucfirst(str_replace('_', ' ', $field)) . " must be at least $min characters";
    }
    
    if ($length > $max) {
        return ucfirst(str_replace('_', ' ', $field)) . " must not exceed $max characters";
    }
    
    return null;
}

// API helpers
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function successResponse($message, $data = null) {
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

function errorResponse($message, $status = 400) {
    jsonResponse([
        'success' => false,
        'message' => $message
    ], $status);
}

// SEO helpers
function generateTitle($title, $suffix = true) {
    $settings = getSiteSettings();
    $siteTitle = $settings['site_title'] ?? SITE_NAME;
    
    return $title . ($suffix ? ' | ' . $siteTitle : '');
}

function generateDescription($description) {
    return truncateText(strip_tags($description), 160);
}

function generateKeywords($keywords) {
    if (is_array($keywords)) {
        $keywords = implode(', ', $keywords);
    }
    
    return sanitizeInput($keywords);
}
?>
