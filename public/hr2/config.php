<?php
// config.php

// Add to config.php
function isUserEnrolled($user_id, $course_id, $db) {
    $stmt = $db->prepare("SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);
    return $stmt->rowCount() > 0;
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'calicrane_training');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create database connection
function getDB() {
    static $db = null;

    if ($db === null) {
        try {
            $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    return $db;
}

// Helper function to get asset path
function asset($path) {
    return '/' . ltrim($path, '/');
}

// Set user ID (no login required)
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Demo User';
$_SESSION['user_email'] = 'demo@calicrane.com';
$_SESSION['user_photo'] = 'images/uploadprof.png';
$_SESSION['is_admin'] = true;
?>
