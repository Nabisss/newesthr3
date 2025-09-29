<?php
session_start();
require_once '../config.php';

$user_id = $_SESSION['user_id'];
$db = getDB();

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);

    // Check if user is enrolled in this course
    $stmt = $db->prepare("SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);

    if ($stmt->rowCount() > 0) {
        // Get course title
        $stmt = $db->prepare("SELECT title FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get lessons for this course
        $stmt = $db->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY order_index");
        $stmt->execute([$course_id]);
        $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get completion status for each lesson
        foreach ($lessons as &$lesson) {
            $stmt = $db->prepare("SELECT completed FROM user_lesson_progress WHERE user_id = ? AND lesson_id = ?");
            $stmt->execute([$user_id, $lesson['id']]);
            $progress = $stmt->fetch(PDO::FETCH_ASSOC);
            $lesson['completed'] = $progress ? (bool)$progress['completed'] : false;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'course_title' => $course['title'],
            'lessons' => $lessons
        ]);
        exit();
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'You are not enrolled in this course.']);
        exit();
    }
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Course ID is required.']);
    exit();
}
