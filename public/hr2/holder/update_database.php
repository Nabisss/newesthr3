<?php
require_once '../config.php';

try {
    $db = getDB();

    // Add completed_at column to user_courses table if it doesn't exist
    $db->exec("ALTER TABLE user_courses ADD COLUMN IF NOT EXISTS completed_at TIMESTAMP NULL AFTER enrolled_at");

    // Add created_at column to courses table if it doesn't exist
    $db->exec("ALTER TABLE courses ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");

    // Add created_at column to lessons table if it doesn't exist
    $db->exec("ALTER TABLE lessons ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");

    echo "Database updated successfully!";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>
