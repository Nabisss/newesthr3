<?php
session_start();
require_once '../config.php';

// In the PHP section, add these helper functions after the database connection
function calculateOverallProgress($user_id, $db) {
    // Get all enrolled courses
    $stmt = $db->prepare("
        SELECT c.id, c.modules_count, uc.progress
        FROM courses c
        JOIN user_courses uc ON c.id = uc.course_id
        WHERE uc.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $enrolled_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($enrolled_courses)) {
        return 283; // Full circle (no progress)
    }

    $total_progress = 0;
    $total_courses = count($enrolled_courses);

    foreach ($enrolled_courses as $course) {
        $total_progress += $course['progress'];
    }

    $average_progress = $total_progress / $total_courses;
    $offset = 283 - (283 * ($average_progress / 100));

    return max(0, min(283, $offset));
}

function calculateOverallProgressPercentage($user_id, $db) {
    // Get all enrolled courses
    $stmt = $db->prepare("
        SELECT c.id, c.modules_count, uc.progress
        FROM courses c
        JOIN user_courses uc ON c.id = uc.course_id
        WHERE uc.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $enrolled_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($enrolled_courses)) {
        return 0;
    }

    $total_progress = 0;
    $total_courses = count($enrolled_courses);

    foreach ($enrolled_courses as $course) {
        $total_progress += $course['progress'];
    }

    return round($total_progress / $total_courses);
}

$user_id = $_SESSION['user_id'];
$db = getDB();

// Handle direct course links
if (isset($_GET['course_id'])) {
    $direct_course_id = intval($_GET['course_id']);

    // Check if user is enrolled
    $stmt = $db->prepare("SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $direct_course_id]);

    if ($stmt->rowCount() > 0) {
        // Auto-open the lesson modal
        echo '<script>document.addEventListener("DOMContentLoaded", function() { openLesson(' . $direct_course_id . '); });</script>';
    }
}


// Handle enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll_course'])) {
    $course_id = $_POST['course_id'];

    // Check if user is already enrolled
    $stmt = $db->prepare("SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?");
    $stmt->execute([$user_id, $course_id]);

    if ($stmt->rowCount() == 0) {
        // Enroll user in the course
        $stmt = $db->prepare("INSERT INTO user_courses (user_id, course_id, status, progress, enrolled_at) VALUES (?, ?, 'not_started', 0, NOW())");
        if ($stmt->execute([$user_id, $course_id])) {
            $_SESSION['message'] = "Course enrolled successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error enrolling in course.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "You are already enrolled in this course.";
        $_SESSION['message_type'] = "info";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get all courses
$courses_stmt = $db->query("SELECT * FROM courses ORDER BY id");
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's enrolled courses with progress
$user_courses_stmt = $db->prepare("
    SELECT c.*, uc.progress, uc.status
    FROM courses c
    JOIN user_courses uc ON c.id = uc.course_id
    WHERE uc.user_id = ?
");
$user_courses_stmt->execute([$user_id]);
$user_courses = $user_courses_stmt->fetchAll(PDO::FETCH_ASSOC);

// Create an array of enrolled course IDs for easy checking
$enrolled_course_ids = array_column($user_courses, 'id');

// Display messages if any
if (isset($_SESSION['message'])) {
    echo '<div class="fixed top-20 right-4 z-50 p-4 mb-4 text-sm text-'.$_SESSION['message_type'].'-800 bg-'.$_SESSION['message_type'].'-100 rounded-lg" role="alert">'.$_SESSION['message'].'</div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo asset('images/logo.png'); ?>" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <title>Safety Training Module</title>
    <style>
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .submenu.open {
            max-height: 500px;
        }
        .rotate-0 {
            transform: rotate(0deg);
            transition: transform 0.3s ease;
        }
        .rotate-90 {
            transform: rotate(90deg);
            transition: transform 0.3s ease;
        }
        .notification-container {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            width: 384px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 50;
            margin-top: 0.5rem;
        }
        .notification-container.open {
            display: block;
        }

        /* Loading Screen Styles */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .loading-logo {
            width: 120px;
            height: 120px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.7; }
        }

        /* Lesson Card Styles */
        .lesson-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .lesson-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #3B82F6;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            stroke-dasharray: 283;
            transition: stroke-dashoffset 0.5s ease;
        }
    </style>
</head>
<body>

   <!-- nav bar -->
<nav class="fixed bg-[#111111] top-0 z-50 w-full shadow">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-white rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="#" class="flex items-center ms-2 md:me-24">
                    <img src="<?php echo asset('images/logo.png'); ?>" class="h-8 me-2" alt="Logo">
                    <span class="self-center text-xl font-extrabold sm:text-2xl whitespace-nowrap text-white">CaliCrane</span>
                </a>
            </div>

            <div class="flex items-center">
                <!-- Philippine Time Display - Now beside notification -->
                <div class="hidden md:flex items-center text-white mr-4">
                    <svg class="w-5 h-5 mr-2 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span id="philippineTime" class="font-bold text-white"></span>
                </div>

                <!-- Notification Icon with Dropdown -->
                <div class="flex items-center ms-3 mr-4 relative">
                    <button type="button" onclick="toggleNotification()" class="relative p-2 text-white rounded-full hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-0 right-0 flex h-4 w-4 -mt-1 -mr-1">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-xs text-white items-center justify-center">3</span>
                        </span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="notification-container">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">New Training Module Available</p>
                                        <p class="mt-1 text-sm text-gray-500">Safety training module has been updated with new content.</p>
                                        <p class="mt-1 text-xs text-gray-400">2 hours ago</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items_start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Course Completion</p>
                                        <p class="mt-1 text-sm text-gray-500">John Doe has completed the Crane Operation Basics course.</p>
                                        <p class="mt-1 text-xs text-gray-400">5 hours ago</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Department Notification</p>
                                        <p class="mt-1 text-sm text-gray-500">You received a notification in another department.</p>
                                        <p class="mt-1 text-xs text-gray-400">Yesterday</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 border-t border-gray-200">
                            <a href="#" class="block text-center text-sm font-medium text-blue-600 hover:text-blue-800">View all notifications</a>
                        </div>
                    </div>
                </div>

                <div class="flex items-center ms-3">
                    <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full object-cover"
                                src="<?php echo isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : asset('images/uploadprof.png'); ?>"
                                alt="Profile Photo">
                        </button>
                    </div>

                    <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-sm shadow-sm bg-white shadow" id="dropdown-user">
                        <!-- Profile Image in dropdown -->
                        <div class="flex justify-center items-center p-2">
                            <img class="w-20 h-20 rounded-full shadow-lg object-cover"
                                src="<?php echo isset($_SESSION['user_photo']) ? $_SESSION['user_photo'] : asset('images/uploadprof.png'); ?>"
                                alt="Profile Photo">
                        </div>

                        <!-- User Info -->
                        <div class="px-4 py-3 text-center" role="none">
                            <p class="text-sm font-semibold text-gray-900">
                                <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User'; ?>
                            </p>
                            <p class="text-sm font-medium text-gray-500 truncate">
                                <?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'user@example.com'; ?>
                            </p>
                        </div>

                        <!-- Dropdown Links -->
                        <ul class="py-1" role="none">
                            <li>
                                <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Settings
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- nav bar -->

<!-- Loading Screen -->
<div id="loadingScreen" class="loading-screen">
    <div class="text-center">
        <img src="<?php echo asset('images/logo.png'); ?>" alt="CaliCrane Logo" class="loading-logo mx-auto mb-4">
        <p class="text-white text-xl font-semibold">Loading Safety Training Module...</p>
    </div>
</div>

<!-- side bar -->
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full sm:translate-x-0 bg-black shadow shadow-xl" aria-label="Sidebar">
    <div class="h-full px-4 pb-4 overflow-y-auto bg-black shadow">
        <!-- Title -->
        <div class="flex justify-center items-center mb-6">
            <h1 class="text-3xl font-bold text-white tracking-wide">Human Resource 2</h1>
        </div>

        <!-- Navigation Links -->
        <ul class="space-y-2 text-sm font-medium text-white">
            <!-- Dashboard -->
            <li>
                <a href="http://127.0.0.1:8000/dashboard" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m-4 4h14a2 2 0 002-2V10a2 2 0 00-2-2h-4l-2-2m-2 2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>


            <!-- Training Management with nested submenus -->
                <li>
                    <div class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-900 transition cursor-pointer" onclick="toggleSubmenu('training-submenu')">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="ml-4">Training Management</span>
                        </div>
                        <svg id="training-arrow" class="w-4 h-4 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <ul id="training-submenu" class="submenu pl-11 close">
                        <li class="my-2">
                            <a href="/hr2/training/coursemanagement.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition bg-blue-0">Course Management</a>
                        </li>
                        <li class="my-2">
                            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-blue-900 transition cursor-pointer" onclick="toggleSubmenu('learning-submenu')">
                                <span>Learning Management</span>
                                <svg id="learning-arrow" class="w-3 h-3 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <ul id="learning-submenu" class="submenu pl-6">
                                <li class="my-1">
                                    <a href="/hr2/learning/safetytraining.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition text-sm">Safety Training Module</a>
                                </li>
                                <li class="my-1">
                                    <a href="/hr2/learning/maintenanceinspect.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition text-sm">Maintenance and Inspection</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Competency Management -->
            <li>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-900 transition cursor-pointer" onclick="toggleSubmenu('competency-submenu')">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="ml-4">Competency Management</span>
                    </div>
                    <svg id="competency-arrow" class="w-4 h-4 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <ul id="competency-submenu" class="submenu pl-11">
                    <li class="my-2">
                        <a href="../competency/compe.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition">Profile Status</a>
                    </li>
                </ul>
            </li>

            <!-- Succession Planning -->
            <li>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-900 transition cursor-pointer" onclick="toggleSubmenu('succession-submenu')">
                        <div class="flex items-center">
                            <!-- Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16h14M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                            </svg>
                            <span class="ml-4">Succession Planning</span>
                        </div>

                        <!-- Right Arrow -->
                        <svg id="succession-arrow" class="w-4 h-4 transition-transform rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        </div>
                <ul id="succession-submenu" class="submenu pl-11">
                    <li class="my-2">
                        <a href="../succession/successions.php" class="block p-2 rounded-lg hover:bg-blue-900 transition">Roles Development & Contingency</a>
                    </li>
                </ul>
            </li>

            <!-- Claims and Reimbursement -->
            <li>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-900 transition cursor-pointer" onclick="toggleSubmenu('claims-submenu')">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-4">Claims and Reimbursement</span>
                    </div>
                    <svg id="claims-arrow" class="w-4 h-4 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <ul id="claims-submenu" class="submenu pl-11">
                    <li class="my-2">
                        <a href="../claimsreimbursement/claimsreim.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition">Claims Form</a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</aside><!-- side bar -->

<!-- content -->
<div class="p-4 sm:ml-64">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-14">

        <!-- breadcrumb -->
        <div class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-900 hover:text-blue-600">
                    <svg class="w-3 h-3 mr-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                </a>
                </li>
                <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="#" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Learning Management</a>
                </div>
                </li>
                <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-blue-600 md:ml-2">Safety Training Module</span>
                </div>
                </li>
            </ol>
        </div>
        <!-- breadcrumb -->

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Safety Training Module</h1>
            <p class="text-gray-600">Complete these essential safety training lessons to ensure safe operation of cranes and trucks.</p>
        </div>

        <!-- Progress Overview Card -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-800 rounded-2xl shadow-lg p-6 mb-8 text-white">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-2xl font-bold mb-2">Your Training Progress</h2>
                    <p class="text-blue-100">Complete all lessons to earn your Safety Training Certificate</p>
                </div>
                <div class="relative w-24 h-24">
                    <svg class="w-full h-full progress-ring" viewBox="0 0 100 100">
                        <circle class="text-blue-400 stroke-current" stroke-width="8" fill="transparent" r="45" cx="50" cy="50"/>
                        <circle class="progress-ring-circle text-white stroke-current" stroke-width="8" fill="transparent" r="45" cx="50" cy="50"
                                stroke-dashoffset="283" style="stroke-dashoffset: 141.5;" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold">50%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lessons Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach($courses as $course): ?>
            <?php
            $is_enrolled = in_array($course['id'], $enrolled_course_ids);
            $user_course = null;

            if ($is_enrolled) {
                foreach ($user_courses as $uc) {
                    if ($uc['id'] == $course['id']) {
                        $user_course = $uc;
                        break;
                    }
                }
            }

            $status_class = '';
            $status_text = '';
            $button_text = 'Start';
            $progress = 0;

            if ($is_enrolled) {
                $progress = $user_course['progress'];

                if ($user_course['status'] == 'completed') {
                    $status_class = 'bg-green-100 text-green-800';
                    $status_text = 'Completed';
                    $button_text = 'Review';
                } elseif ($user_course['status'] == 'in_progress') {
                    $status_class = 'bg-yellow-100 text-yellow-800';
                    $status_text = 'In Progress';
                    $button_text = 'Continue';
                } else {
                    $status_class = 'bg-gray-100 text-gray-800';
                    $status_text = 'Not Started';
                }
            } else {
                $status_class = 'bg-gray-100 text-gray-800';
                $status_text = 'Not Enrolled';
            }
            ?>

            <div class="lesson-card bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        Lesson <?php echo $course['id']; ?>
                    </span>
                    <span class="<?php echo $status_class; ?> text-xs font-semibold px-2.5 py-0.5 rounded">
                        <?php echo $status_text; ?>
                    </span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($course['title']); ?></h3>
                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($course['description']); ?></p>

                <?php if ($is_enrolled): ?>
                <div class="mb-4">
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-1">
                        <span>Progress</span>
                        <span><?php echo $progress; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500"><?php echo $course['modules_count']; ?> modules</span>

                    <?php if ($is_enrolled): ?>
                    <button onclick="openLesson(<?php echo $course['id']; ?>)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <?php echo $button_text; ?>
                    </button>
                    <?php else: ?>
                    <form method="POST" action="">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        <button type="submit" name="enroll_course" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Enroll
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Upcoming Training Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Upcoming Training Sessions</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3">Training</th>
                            <th scope="col" class="px-4 py-3">Date</th>
                            <th scope="col" class="px-4 py-3">Instructor</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                Crane Operation Refresher
                            </th>
                            <td class="px-4 py-3">Oct 15, 2023</td>
                            <td class="px-4 py-3">John Smith</td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Scheduled</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="#" class="font-medium text-blue-600 hover:underline">View details</a>
                            </td>
                        </tr>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                Advanced Safety Equipment
                            </th>
                            <td class="px-4 py-3">Oct 22, 2023</td>
                            <td class="px-4 py-3">Maria Garcia</td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Scheduled</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="#" class="font-medium text-blue-600 hover:underline">View details</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resources Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Training Resources</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="p-2 mr-4 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Safety Handbook</h3>
                        <p class="text-sm text-gray-500">PDF - 2.4 MB</p>
                    </div>
                </a>
                <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="p-2 mr-4 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 002-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 -900">Training Videos</h3>
                        <p class="text-sm text-gray-500">12 videos</p>
                    </div>
                </a>
                <a href="#" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="p-2 mr-4 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900">Checklists</h3>
                        <p class="text-sm text-gray-500">Printable forms</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>
<!-- content -->

<!-- Lesson Content Modal -->
<div id="lessonModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-11/12 md极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 -w-3/4 lg:w-2/3 max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Lesson Content</h2>
                <button onclick="closeLesson()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="text-gray-700">
                <!-- Content will be loaded here -->
            </div>
            <div class="mt-8 flex justify-between">
                <button onclick="closeLesson()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                    Close
                </button>
                <button onclick="markLessonComplete()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Mark as Complete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle submenu function
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        const arrow = document.getElementById(id.replace('submenu', 'arrow'));

        submenu.classList.toggle('open');
        arrow.classList.toggle('rotate-0');
        arrow.classList.toggle('rotate-90');
    }

    // Toggle notification dropdown
    function toggleNotification() {
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('open');
    }

    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notification-dropdown');
        const notifButton = document.querySelector('button[onclick="toggleNotification()"]');

        if (!notifButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('open');
        }
    });

    // Philippine Time Display
    function updatePhilippineTime() {
        const options = {
            timeZone: 'Asia/Manila',
            hour12: true,
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        };

        const formatter = new Intl.DateTimeFormat('en-PH', options);
        const phTime = formatter.format(new Date());

        document.getElementById('philippineTime').textContent = phTime;
    }

    setInterval(updatePhilippineTime, 1000);
    updatePhilippineTime();

    // Loading Screen Simulation
    document.addEventListener('DOMContentLoaded', function() {
        // Show loading screen initially
        const loadingScreen = document.getElementById('loadingScreen');

        // Simulate loading process
        setTimeout(function() {
            loadingScreen.style.opacity = '0';
            setTimeout(function() {
                loadingScreen.style.display = 'none';
            }, 500);
        }, 1500); // Show loading screen for 1.5 seconds
    });

    // Open lesson modal
    function openLesson(courseId) {
        console.log("Opening lesson for course:", courseId);

        const modal = document.getElementById('lessonModal');
        const title = document.getElementById('modalTitle');
        const content = document.getElementById('modalContent');

        title.textContent = "Loading Lesson...";
        content.innerHTML = "<div class='text-center py-8'><div class='animate-spin rounded-full h-12 w-12 border-b-2 border-blue-极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 00 mx-auto'></div><p class='mt-4'>Loading content...</p></div>";
        modal.classList.remove('hidden');

        // Fetch lesson content from server
        fetch(`lesson_content.php?course_id=${courseId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    title.textContent = "Error";
                    content.innerHTML = `<div class="p-4 bg-red-100 text-red-700 rounded-lg">
                        <p>${data.error}</p>
                        <button onclick="closeLesson()" class="mt-2 px-3 py-1 bg-red-600 text-white rounded">Close</button>
                    </div>`;
                } else {
                    title.textContent = data.title;
                    content.innerHTML = data.content;
                    content.setAttribute('data-course-id', courseId);
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                title.textContent = "Error";
                content.innerHTML = `
                    <div class="p-4 bg-red-100 text-red-700 rounded-lg">
                        <p>Error loading content. Please try again.</p>
                        <p class="text-sm mt-2">Error details: ${error.message}</p>
                        <button onclick="closeLesson()极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 " class="mt-2 px极速赛车开奖直播 极速赛车开奖结果 幸运飞艇开奖直播 幸运飞艇开奖结果 -3 py-1 bg-red-600 text-white rounded">Close</button>
                    </div>
                `;
            });
    }

    // Close lesson modal
    function closeLesson() {
        const modal = document.getElementById('lessonModal');
        modal.classList.add('hidden');
    }

    // Mark lesson as complete
    function markLessonComplete() {
        const lessonId = document.getElementById('modalContent').getAttribute('data-lesson-id');
        const courseId = document.getElementById('modalContent').getAttribute('data-course-id');

        if (!lessonId) {
            alert('No lesson loaded');
            return;
        }

        const completeBtn = document.querySelector('#lessonModal button:last-child');
        const originalText = completeBtn.textContent;
        completeBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mx-auto"></div>';
        completeBtn.disabled = true;

        // Create form data
        const formData = new FormData();
        formData.append('mark_complete', '1');
        formData.append('lesson_id', lessonId);

        fetch('lesson_content.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Lesson marked as complete!');
                closeLesson();

                // Refresh the page to update progress
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                alert('Error: ' + (data.error || 'Unknown error'));
                completeBtn.textContent = originalText;
                completeBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error marking lesson as complete. Please try again.');
            completeBtn.textContent = originalText;
            completeBtn.disabled = false;
        });
    }
</script>

</body>
</html>
