<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_complete'])) {
    $user_id = $_SESSION['user_id'];
    $lesson_id = $_POST['lesson_id'];

    // Mark lesson as complete
    $stmt = $db->prepare("INSERT INTO user_lesson_progress (user_id, lesson_id, completed, completed_at) VALUES (?, ?, TRUE, NOW()) ON DUPLICATE KEY UPDATE completed = TRUE, completed_at = NOW()");

    if ($stmt->execute([$user_id, $lesson_id])) {
        // Update course progress
        $stmt = $db->prepare("
            SELECT c.id, COUNT(l.id) as total_lessons,
                   COUNT(ulp.lesson_id) as completed_lessons
            FROM courses c
            JOIN lessons l ON c.id = l.course_id
            LEFT JOIN user_lesson_progress ulp ON l.id = ulp.lesson_id AND ulp.user_id = ? AND ulp.completed = TRUE
            WHERE l.id = ?
            GROUP BY c.id
        ");
        $stmt->execute([$user_id, $lesson_id]);
        $progress_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($progress_data) {
            $progress_percentage = round(($progress_data['completed_lessons'] / $progress_data['total_lessons']) * 100);
            $status = $progress_percentage == 100 ? 'completed' : 'in_progress';

            // Update user course progress
            $stmt = $db->prepare("UPDATE user_courses SET progress = ?, status = ? WHERE user_id = ? AND course_id = ?");
            $stmt->execute([$progress_percentage, $status, $user_id, $progress_data['id']]);
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
    exit();
}

// Get lesson content
if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    $user_id = $_SESSION['user_id'];

    $db = getDB();

    // Get course details
    $stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        echo json_encode(['error' => 'Course not found']);
        exit();
    }

    // Get lessons for this course
    $stmt = $db->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY order_index");
    $stmt->execute([$course_id]);
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check which lessons are completed
    $completed_lessons = [];
    if (!empty($lessons)) {
        $lesson_ids = array_column($lessons, 'id');
        $placeholders = implode(',', array_fill(0, count($lesson_ids), '?'));

        $stmt = $db->prepare("SELECT lesson_id FROM user_lesson_progress WHERE user_id = ? AND lesson_id IN ($placeholders) AND completed = TRUE");
        $stmt->execute(array_merge([$user_id], $lesson_ids));
        $completed_lessons = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Generate HTML content
    $content = '<div class="lesson-content">';
    $content .= '<h3 class="text-xl font-semibold mb-4">' . htmlspecialchars($course['title']) . '</h3>';
    $content .= '<p class="text-gray-600 mb-6">' . htmlspecialchars($course['description']) . '</p>';

    $content .= '<div class="lessons-list">';
    foreach ($lessons as $lesson) {
        $is_completed = in_array($lesson['id'], $completed_lessons);
        $status_class = $is_completed ? 'text-green-600' : 'text-gray-400';
        $status_icon = $is_completed ? '✓' : '◯';

        $content .= '<div class="lesson-item mb-4 p-4 border border-gray-200 rounded-lg">';
        $content .= '<div class="flex items-center justify-between">';
        $content .= '<h4 class="font-medium">' . htmlspecialchars($lesson['title']) . '</h4>';
        $content .= '<span class="' . $status_class . '">' . $status_icon . '</span>';
        $content .= '</div>';

        if (!empty($lesson['content'])) {
            $content .= '<div class="mt-2 text-gray-700">' . $lesson['content'] . '</div>';
        }

        $content .= '</div>';
    }
    $content .= '</div>'; // .lessons-list
    $content .= '</div>'; // .lesson-content

    echo json_encode([
        'id' => $course_id,
        'title' => $course['title'],
        'content' => $content
    ]);
    exit();
}

echo json_encode(['error' => 'Invalid request']);
?>
