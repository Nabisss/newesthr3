<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_course'])) {
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];
    $db = getDB();

    try {
        // Delete user course enrollment
        $stmt = $db->prepare("DELETE FROM user_courses WHERE user_id = ? AND course_id = ?");

        if ($stmt->execute([$user_id, $course_id])) {
            $_SESSION['message'] = "Course unenrolled successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error unenrolling from course.";
            $_SESSION['message_type'] = "error";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
