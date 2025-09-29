<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $modules_count = $_POST['modules_count'];
    $image_path = $_POST['image_path'] ?? 'images/course1.jpg';
    $is_safety_training = isset($_POST['is_safety_training']) ? 1 : 0;

    $db = getDB();
    $stmt = $db->prepare("INSERT INTO courses (title, description, modules_count, image_path, is_safety_training) VALUES (?, ?, ?, ?, ?)");

    if ($stmt->execute([$title, $description, $modules_count, $image_path, $is_safety_training])) {
        $_SESSION['message'] = "Course added successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error adding course.";
        $_SESSION['message_type'] = "error";
    }

    header("Location: coursemanagement.blade.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Add New Course</h1>

            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="title">Course Title</label>
                    <input type="text" id="title" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="description">Description</label>
                    <textarea id="description" name="description" required class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="modules_count">Number of Modules</label>
                    <input type="number" id="modules_count" name="modules_count" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="image_path">Image Path</label>
                    <input type="text" id="image_path" name="image_path" value="images/course1.jpg" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_safety_training" value="1" class="mr-2">
                        <span class="text-gray-700">Safety Training Course</span>
                    </label>
                </div>

                <div class="flex justify-between">
                    <a href="coursemanagement.blade.php" class="bg-gray-500 text-white px-4 py-2 rounded-md">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">Add Course</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
