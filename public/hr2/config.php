<?php
// config.php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'calicrane_training');
define('DB_USER', 'root');
define('DB_PASS', ''); // Keep empty if no password, otherwise add your password

// Create database connection
function getDB() {
    static $db = null;

    if ($db === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
            $db = new PDO($dsn, DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // More detailed error message
            die("Database connection failed: " . $e->getMessage() .
                " - Using: " . DB_USER . "@" . DB_HOST . "/" . DB_NAME);
        }
    }

    return $db;
}

// Helper function to check if user is enrolled
function isUserEnrolled($user_id, $course_id, $db) {
    $stmt = $db->prepare("SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    return $stmt->rowCount() > 0;
}

// Helper function to get asset path
function asset($path) {
    return '/' . ltrim($path, '/');
}

// Set demo user data (no login required)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Demo User';
    $_SESSION['user_email'] = 'demo@calicrane.com';
    $_SESSION['user_photo'] = asset('images/uploadprof.png');
    $_SESSION['is_admin'] = true;
}
?>
