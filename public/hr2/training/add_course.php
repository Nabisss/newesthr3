<?php
session_start();
require_once '../config.php';

// Check if user is admin - fixed the redirect issue
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Redirect to the correct file name
    header("Location: coursemanagement.blade.php");
    exit();
}

$db = getDB();
$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $modules_count = intval($_POST['modules_count']);
    $is_safety_training = isset($_POST['is_safety_training']) ? 1 : 0;

    // Validate inputs
    if (empty($title) || empty($description) || $modules_count <= 0) {
        $message = "Please fill all required fields correctly.";
        $message_type = "error";
    } else {
        try {
            // Insert the new course without image_path
            $stmt = $db->prepare("INSERT INTO courses (title, description, modules_count, is_safety_training, created_at) VALUES (?, ?, ?, ?, NOW())");

            if ($stmt->execute([$title, $description, $modules_count, $is_safety_training])) {
                $course_id = $db->lastInsertId();

                // Create default lessons based on modules_count
                for ($i = 1; $i <= $modules_count; $i++) {
                    $lesson_title = "Module $i: " . $title;
                    $lesson_content = "<h3>Module $i Content</h3><p>This is the content for module $i of the course '$title'.</p>";

                    $stmt = $db->prepare("INSERT INTO lessons (course_id, title, content, order_index, created_at) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->execute([$course_id, $lesson_title, $lesson_content, $i]);
                }

                $message = "Course added successfully!";
                $message_type = "success";

                // Clear form fields
                $_POST = array();
            } else {
                $message = "Error adding course. Please try again.";
                $message_type = "error";
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Add New Course</h1>

            <?php if ($message): ?>
            <div class="p-4 mb-4 text-sm text-<?php echo $message_type == 'success' ? 'green' : 'red'; ?>-800 bg-<?php echo $message_type == 'success' ? 'green' : 'red'; ?>-100 rounded-lg">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Course Title *</label>
                    <select id="title" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a course</option>
                        <option value="Tools and Equipment Training Course" <?php echo (isset($_POST['title']) && $_POST['title'] == 'Tools and Equipment Training Course') ? 'selected' : ''; ?>>Tools and Equipment Training Course</option>
                        <option value="Crane and Truck Training Course" <?php echo (isset($_POST['title']) && $_POST['title'] == 'Crane and Truck Training Course') ? 'selected' : ''; ?>>Crane and Truck Training Course</option>
                        <option value="Customer Service Training Course" <?php echo (isset($_POST['title']) && $_POST['title'] == 'Customer Service Training Course') ? 'selected' : ''; ?>>Customer Service Training Course</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea id="description" name="description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="modules_count" class="block text-sm font-medium text-gray-700 mb-1">Number of Modules *</label>
                    <input type="number" id="modules_count" name="modules_count" min="1" value="<?php echo isset($_POST['modules_count']) ? htmlspecialchars($_POST['modules_count']) : '1'; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_safety_training" name="is_safety_training" value="1" <?php echo (isset($_POST['is_safety_training']) && $_POST['is_safety_training']) ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_safety_training" class="ms-2 text-sm font-medium text-gray-900">This is a Safety Training course</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="coursemanagement.blade.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">Cancel</a>
                    <button type="submit" name="add_course" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Add Course</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
