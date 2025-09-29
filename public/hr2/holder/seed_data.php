<?php
require_once '../config.php';

// Insert sample courses
$courses = [
    [
        'title' => 'Operating Cranes and Trucks',
        'description' => 'Learn the fundamentals of safely operating cranes and trucks in various working conditions.',
        'modules_count' => 5,
        'progress' => 100
    ],
    [
        'title' => 'Safety Gears, Tools, and Equipment',
        'description' => 'Understand the proper use and maintenance of safety equipment for crane and truck operations.',
        'modules_count' => 4,
        'progress' => 60
    ],
    [
        'title' => 'Maintenance and Inspection for Cranes and Trucks',
        'description' => 'Master the procedures for routine maintenance and inspection to ensure equipment safety.',
        'modules_count' => 6,
        'progress' => 0
    ]
];

foreach ($courses as $course) {
    $stmt = $pdo->prepare("INSERT INTO courses (title, description, modules_count, progress) VALUES (?, ?, ?, ?)");
    $stmt->execute([$course['title'], $course['description'], $course['modules_count'], $course['progress']]);

    $course_id = $pdo->lastInsertId();

    // Add sample lessons for each course
    for ($i = 1; $i <= $course['modules_count']; $i++) {
        $stmt = $pdo->prepare("INSERT INTO lessons (course_id, title, content, order_index) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $course_id,
            "Module $i: " . $course['title'],
            "<h3>Module $i Content</h3><p>This is the content for module $i of the course: " . $course['title'] . "</p>",
            $i
        ]);
    }
}

echo "Sample data inserted successfully!";
?>
