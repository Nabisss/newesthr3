<?php
require_once '../config.php';

$user_id = $_SESSION['user_id'];
$db = getDB();

// Handle course enrollment
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
$all_courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user's enrolled courses
$stmt = $db->prepare("
    SELECT c.*, uc.progress, uc.status, uc.enrolled_at
    FROM courses c
    JOIN user_courses uc ON c.id = uc.course_id
    WHERE uc.user_id = ?
    ORDER BY uc.enrolled_at DESC
");
$stmt->execute([$user_id]);
$enrolled_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group courses by status
$in_progress_courses = array_filter($enrolled_courses, function($course) {
    return $course['status'] == 'in_progress';
});

$completed_courses = array_filter($enrolled_courses, function($course) {
    return $course['status'] == 'completed';
});

$upcoming_courses = array_filter($enrolled_courses, function($course) {
    return $course['status'] == 'not_started';
});

// Create an array of enrolled course IDs for easy checking
$enrolled_course_ids = array_column($enrolled_courses, 'id');
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="<?php echo asset('images/logo.png'); ?>" type="image/png">
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

        <title>Course Management</title>
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
                box-shadow:  0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                z-index: 50;
                margin-top: 0.5rem;
            }
            .notification-container.open {
                display: block;
            }
            .tab-content {
                display: none;
            }
            .tab-content.active {
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
        </style>
    </head>
    <body>

    <!-- Loading Screen -->
    <div id="loadingScreen" class="loading-screen">
            <div class="text-center">
                <img src="<?php echo asset('images/logo.png'); ?>" alt="CaliCrane Logo" class="loading-logo mx-auto mb-4">
                <p class="text-white text-xl font-semibold">Loading Course Management...</p>
            </div>
        </div>

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
                                    <div class="flex items-start">
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
                                    <a href="{{ route('settings') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Settings
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- nav bar -->

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

                <!-- Training Management -->
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
                            <a href="../training/coursemanagement.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition bg-blue-0">Course Management</a>
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
                                    <a href="../learning/safetytraining.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition text-sm">Safety Training Module</a>
                                </li>
                                <li class="my-1">
                                    <a href="../learning/maintenanceinspect.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition text-sm">Maintenance and Inspection</a>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-6 0H5m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="ml-4">Succession Planning</span>
                        </div>
                        <svg id="succession-arrow" class="w-4 h-4 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <ul id="succession-submenu" class="submenu pl-11">
                        <li class="my-2">
                            <a href="../succession/successions.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition">Roles Development & Contingency</a>
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
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-900 hover:text-blue-600">
                        <svg class="w-3 h-3 mr-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Dashboard
                    </a>
                    </li>
                    <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="#" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Training Management</a>
                    </div>
                    </li>
                    <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-blue-600 md:ml-2">Course Management</span>
                    </div>
                    </li>
                </ol>
            </div>
            <!-- breadcrumb -->

            <!-- Main Content -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Course Management</h1>
                <p class="text-gray-600">Manage employee training courses, schedules, and instructors</p>
            </div>

            <!-- Tabs -->
            <div class="mb-6 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="course-tabs" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg border-blue-600 text-blue-600" id="admin-tab" data-tab-target="admin-content" type="button" role="tab" aria-controls="admin-content" aria-selected="true">Admin</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="trainee-tab" data-tab-target="trainee-content" type="button" role="tab" aria-controls="trainee-content" aria-selected="false">Trainee</button>
                    </li>
                </ul>
            </div>

            <!-- Tab Contents -->
            <div id="tab-contents">
                <!-- Admin Content -->
                <div id="admin-content" class="tab-content active">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Employee Course Management</h2>
                            <div class="flex space-x-2">
                                                                <button onclick="window.location.href='add_course.php'" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                        Add Course
                                    </button>
                                <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition">
                                    Export Data
                                </button>
                            </div>
                        </div>

                        <!-- Search and Filter -->
                        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                            <div class="relative w-full md:w-64">
                                <input type="text" id="search-employees" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Search employees...">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <select id="filter-department" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option selected value="">All Departments</option>
                                    <option value="operations">Operations</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="safety">Safety</option>
                                </select>
                                <select id="filter-status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option selected value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Employee Table -->
                        <div class="overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Employee</th>
                                        <th scope="col" class="px-6 py-3">Department</th>
                                        <th scope="col" class="px-6 py-3">Course</th>
                                        <th scope="col" class="px-6 py-3">Instructor</th>
                                        <th scope="col" class="px-6 py-3">Schedule</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                                            <img class="w-10 h-10 rounded-full object-cover" src="<?php echo asset('images/uploadprof.png'); ?>" alt="Jane image">
                                            <div class="ps-3">
                                                <div class="text-base font-semibold">John Doe</div>
                                                <div class="font-normal text-gray-500">john.doe@calicrane.com</div>
                                            </div>
                                        </th>
                                        <td class="px-6 py-4">Operations</td>
                                        <td class="px-6 py-4">
                                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                                <option selected>Crane Operation Basics</option>
                                                <option>Advanced Crane Safety</option>
                                                <option>Maintenance Procedures</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                                <option selected>Michael Johnson</option>
                                                <option>Sarah Williams</option>
                                                <option>Robert Brown</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" value="2024-06-15">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-2.5 w-2.5 rounded-full bg-yellow-500 me-2"></div> Pending
                                            </div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <button class="font-medium text-blue-600 hover:underline">Edit</button>
                                            <button class="font-medium text-red-600 hover:underline">Delete</button>
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                                            <img class="w-10 h-10 rounded-full object-cover" src="<?php echo asset('images/uploadprof.png'); ?>" alt="Jane image">
                                            <div class="ps-3">
                                                <div class="text-base font-semibold">Jane Smith</div>
                                                <div class="font-normal text-gray-500">jane.smith@calicrane.com</div>
                                            </div>
                                        </th>
                                        <td class="px-6 py-4">Maintenance</td>
                                        <td class="px-6 py-4">
                                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                                <option>Crane Operation Basics</option>
                                                <option selected>Advanced Crane Safety</option>
                                                <option>Maintenance Procedures</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                                <option>Michael Johnson</option>
                                                <option selected>Sarah Williams</option>
                                                <option>Robert Brown</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" value="2024-06-20">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-2.5 w-2.5 rounded-full bg-blue-500 me-2"></div> In Progress
                                            </div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <button class="font-medium text-blue-600 hover:underline">Edit</button>
                                            <button class="font-medium text-red-600 hover:underline">Delete</button>
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap">
                                            <img class="w-10 h-10 rounded-full object-cover" src="<?php echo asset('images/uploadprof.png'); ?>" alt="Jese image">
                                            <div class="ps-3">
                                                <div class="text-base font-semibold">Robert Johnson</div>
                                                <div class="font-normal text-gray-500">robert.j@calicrane.com</div>
                                            </div>
                                        </th>
                                        <td class="px-6 py-4">Safety</td>
                                        <td class="px-6 py-4">
                                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                                <option>Crane Operation Basics</option>
                                                <option>Advanced Crane Safety</option>
                                                <option selected>Maintenance Procedures</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                                                <option>Michael Johnson</option>
                                                <option>Sarah Williams</option>
                                                <option selected>Robert Brown</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" value="2024-06-25">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Completed
                                            </div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <button class="font-medium text-blue-600 hover:underline">Edit</button>
                                            <button class="font-medium text-red-600 hover:underline">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between pt-4" aria-label="Table navigation">
                            <span class="text-sm font-normal text-gray-500">Showing <span class="font-semibold text-gray-900">1-3</span> of <span class="font-semibold text-gray-900">100</span></span>
                            <ul class="inline-flex -space-x-px text-sm h-8">
                                <li>
                                    <a href="#" class="flex items-center justify-center px-3 h-8 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">Previous</a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">1</a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">2</a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700">3</a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">...</a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">10</a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">Next</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Trainee Content -->
                <div id="trainee-content" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- In Progress Courses -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">In Progress Courses</h2>
                            <?php if (count($in_progress_courses) > 0): ?>
                                <?php foreach ($in_progress_courses as $course): ?>
                                    <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($course['title']); ?></h3>
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">In Progress</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">Enrolled: <?php echo date('M d, Y', strtotime($course['enrolled_at'])); ?></p>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo $course['progress']; ?>%"></div>
                                        </div>
                                        <div class="flex justify-between items-center text-xs text-gray-500">
                                            <span><?php echo $course['progress']; ?>% Complete</span>
                                            <a href="#" class="text-blue-600 hover:underline">Continue Learning</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-500">No courses in progress.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Completed Courses -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Completed Courses</h2>
                            <?php if (count($completed_courses) > 0): ?>
                                <?php foreach ($completed_courses as $course): ?>
                                    <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($course['title']); ?></h3>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Completed</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">Completed: <?php echo date('M d, Y', strtotime($course['completed_at'] ?? $course['enrolled_at'])); ?></p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-500">Score: <?php echo $course['score'] ?? 'N/A'; ?></span>
                                            <div class="flex space-x-2">
                                                <a href="#" class="text-blue-600 hover:underline text-sm">View Certificate</a>
                                                <a href="#" class="text-gray-600 hover:underline text-sm">Review</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-500">No courses completed yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($all_courses as $course): ?>
    <?php
    $is_enrolled = in_array($course['id'], $enrolled_course_ids);
    $button_text = $is_enrolled ? 'Enrolled' : 'Enroll Now';
    $button_class = $is_enrolled ?
        'bg-gray-400 text-white px-3 py-2 rounded-lg text-sm cursor-not-allowed' :
        'bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition';
    ?>

    <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
        <img class="rounded-t-lg w-full h-40 object-cover" src="<?php echo asset($course['image_path']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
        <div class="p-5">
            <h3 class="mb-2 text-lg font-bold tracking-tight text-gray-900"><?php echo htmlspecialchars($course['title']); ?></h3>
            <p class="mb-3 text-sm text-gray-600"><?php echo htmlspecialchars($course['description']); ?></p>
            <div class="flex justify-between items-center mb-3">
                <span class="text-xs font-medium text-gray-500"><?php echo $course['modules_count']; ?> modules</span>
                <?php if($course['is_safety_training']): ?>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Safety Training</span>
                <?php endif; ?>
            </div>

            <?php if($is_enrolled): ?>
            <button class="<?php echo $button_class; ?>" disabled>
                <?php echo $button_text; ?>
            </button>
            <?php else: ?>
            <form method="POST" action="">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <button type="submit" name="enroll_course" class="<?php echo $button_class; ?>">
                    <?php echo $button_text; ?>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
    <!-- content -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        // Toggle submenu function
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id);
            const arrow = document.getElementById(id.replace('-submenu', '-arrow'));

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
            const button = event.target.closest('button');

            if (!dropdown.contains(event.target) && button !== document.querySelector('[onclick="toggleNotification()"]')) {
                dropdown.classList.remove('open');
            }
        });

        // Tab switching functionality
        document.querySelectorAll('[data-tab-target]').forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.getAttribute('data-tab-target');

                // Update tab buttons
                document.querySelectorAll('[data-tab-target]').forEach(t => {
                    t.classList.remove('border-blue-600', 'text-blue-600');
                    t.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
                });
                tab.classList.add('border-blue-600', 'text-blue-600');
                tab.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');

                // Update tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(target).classList.add('active');
            });
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
            const dateTimeParts = formatter.formatToParts(new Date());

            let hour, minute, second, day, month, year, timePeriod;

            for (const part of dateTimeParts) {
                switch (part.type) {
                    case 'hour': hour = part.value; break;
                    case 'minute': minute = part.value; break;
                    case 'second': second = part.value; break;
                    case 'day': day = part.value; break;
                    case 'month': month = part.value; break;
                    case 'year': year = part.value; break;
                    case 'dayPeriod': timePeriod = part.value; break;
                }
            }

            document.getElementById('philippineTime').textContent =
                `${month} ${day}, ${year} ${hour}:${minute}:${second} ${timePeriod}`;
        }

        setInterval(updatePhilippineTime, 1000);
        updatePhilippineTime();

        // Loading Screen
        document.addEventListener('DOMContentLoaded', function() {
            const loadingScreen = document.getElementById('loadingScreen');

            // Show loading screen initially
            loadingScreen.style.display = 'flex';

            // Hide loading screen after 2 seconds
            setTimeout(function() {
                loadingScreen.style.opacity = '0';
                setTimeout(function() {
                    loadingScreen.style.display = 'none';
                }, 500);
            }, 2000);
        });
</script>
</body>
</html>
