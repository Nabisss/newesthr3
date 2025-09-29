<?php
require_once 'config.php';

if (!isset($_GET['course_id'])) {
    echo json_encode(['error' => 'Course ID not provided']);
    exit;
}

$course_id = intval($_GET['course_id']);
$db = getDB();

// Get course details
$stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    echo json_encode(['error' => 'Course not found']);
    exit;
}

// Get lessons for this course
$stmt = $db->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY order_index");
$stmt->execute([$course_id]);
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format the response
$response = [
    'id' => $course_id,
    'title' => $course['title'],
    'content' => '<div class="course-content">'
];

foreach ($lessons as $lesson) {
    $response['content'] .= '
        <div class="lesson-section mb-6">
            <h3 class="text-xl font-semibold mb-3">' . htmlspecialchars($lesson['title']) . '</h3>
            <div class="lesson-body">' . $lesson['content'] . '</div>
        </div>
    ';
}

$response['content'] .= '</div>';

echo json_encode($response);
?>
