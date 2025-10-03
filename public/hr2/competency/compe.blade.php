<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Competency Management</title>
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

        /* Competency Management Styles */
        .profile-card {
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            border-color: #3b82f6;
            background-color: #f8fafc;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-expired {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-valid {
            background-color: #d1fae5;
            color: #065f46;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 500px;
        }

        /* Alert styles */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            display: none;
        }
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* New styles for competency management */
        .assessment-card {
            transition: all 0.3s ease;
            border-left: 4px solid #3b82f6;
        }
        .gap-item {
            border-left: 4px solid #ef4444;
        }
        .training-item {
            border-left: 4px solid #10b981;
        }

        /* Employee list modal styles */
        .employee-modal {
            max-width: 900px;
            width: 90%;
        }
        .employee-card {
            transition: all 0.3s ease;
        }
        .employee-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 .75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="#" class="flex items-center ms-2 md:me-24">
                    <img src="https://hr2.cranecali-ms.com/images/logo.png" class="h-8 me-2" alt="Logo">
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
                                        <p class="mt-1 text-sm text-gray-500">John Smith has completed the Crane Operation Basics course.</p>
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
                                src="images/uploadprof.png"
                                alt="Profile Photo">
                        </button>
                    </div>

                    <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-sm shadow-sm bg-white shadow" id="dropdown-user">
                        <!-- Profile Image in dropdown -->
                        <div class="flex justify-center items-center p-2">
                            <img class="w-20 h-20 rounded-full shadow-lg object-cover"
                                src="images/uploadprof.png"
                                alt="Profile Photo">
                        </div>

                        <!-- User Info -->
                        <div class="px-4 py-3 text-center" role="none">
                            <p class="text-sm font-semibold text-gray-900">
                                John Smith
                            </p>
                            <p class="text-sm font-medium text-gray-500 truncate">
                                john.doe@calicrane.com
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
                            <li>
                                <button type="button"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Sign out
                                </button>
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
                <a href="https://hr2.cranecali-ms.com/dashboard" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
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
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- Competency Management -->
            <li>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-900 transition cursor-pointer bg-blue-900" onclick="toggleSubmenu('competency-submenu')">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="ml-4">Competency Management</span>
                    </div>
                    <svg id="competency-arrow" class="w-4 h-4 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <ul id="competency-submenu" class="submenu pl-11 open">
                    <li class="my-2">
                        <a href="../competency/compe.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition bg-blue-900">Profile Status</a>
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
                        <a href="../succession/successions.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition">Roles Development & Contingency</a>
                    </li>
                </ul>
            </li>

            <!-- Claims and Reimbursement -->
            <li>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-900 transition cursor-pointer" onclick="toggleSubmenu('claims-submenu')">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.8.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <a href="https://hr2.cranecali-ms.com/dashboard" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Dashboard</a>
                </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Competency Management</span>
                    </div>
                </li>
            </ol>
        </div>
        <!-- breadcrumb -->

        <!-- Alert Messages -->
        <div id="alert-success" class="alert alert-success">
            Certification uploaded successfully!
        </div>
        <div id="alert-error" class="alert alert-error">
            Error uploading certification. Please try again.
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Employee Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg profile-card p-6">
                    <div class="flex flex-col items-center mb-6">
                        <img class="w-24 h-24 rounded-full object-cover mb-4 border-4 border-blue-100"
                            src="images/uploadprof.png"
                            alt="Profile Photo">
                        <h2 class="text-xl font-bold text-gray-900">John Smith</h2>
                        <p class="text-gray-600">Crane Operator</p>
                        <div class="mt-2 flex items-center">
                            <span class="status-badge status-active">Active</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Employee ID</p>
                                <p class="text-gray-900">CRN-2023-001</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Department</p>
                                <p class="text-gray-900">Operations</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Date Hired</p>
                                <p class="text-gray-900">January 15, 2023</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">License Number</p>
                                <p class="text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competency Details -->
            <div class="lg:col-span-2">
                <!-- Tabs Navigation -->
                <div class="bg-white rounded-2xl shadow-lg mb-6">
                    <div class="border-b border-gray-200">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="competency-tabs">
                            <li class="me-2">
                                <button type="button" id="certification-tab" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300 active-tab" onclick="switchTab('certification')">Certification & License</button>
                            </li>
                            <li class="me-2">
                                <button type="button" id="evaluation-tab" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300" onclick="switchTab('evaluation')">Competency Evaluation</button>
                            </li>
                            <li class="me-2">
                                <button type="button" id="training-tab" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300" onclick="switchTab('training')">Training Certification</button>
                            </li>
                            <li class="me-2">
                                <button type="button" id="management-tab" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-blue-600 hover:border-blue-300" onclick="switchTab('management')">Competency Management</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tab Contents -->
                <div id="certification-content" class="tab-content active">
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">License & Certification Validity</h3>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center" onclick="openEmployeeListModal()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                View Employee List
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Certification/License</th>
                                        <th scope="col" class="px-4 py-3">Issued Date</th>
                                        <th scope="col" class="px-4 py-3">Expiry Date</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="certifications-table-body">
                                    <!-- Certifications will be dynamically added here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">Upload Certification Proof</h4>
                            <div class="upload-area" onclick="document.getElementById('file-input').click()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-gray-600">Click to upload or drag and drop</p>
                                <p class="text-sm text-gray-500 mt-1">PNG, JPG, PDF up to 10MB</p>
                                <input type="file" id="file-input" class="hidden" accept=".png,.jpg,.jpeg,.pdf" onchange="handleFileSelect(event)">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="evaluation-content" class="tab-content">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Competency Evaluation</h3>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center" onclick="openEmployeeListModal()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                View Employee List
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Technical Skills</h4>
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">Crane Operation</span>
                                            <span class="text-sm font-medium text-gray-700">85%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 85%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">Equipment Maintenance</span>
                                            <span class="text-sm font-medium text-gray-700">70%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 70%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">Safety Procedures</span>
                                            <span class="text-sm font-medium text-gray-700">60%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-red-500 h-2.5 rounded-full" style="width: 60%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Soft Skills</h4>
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">Communication</span>
                                            <span class="text-sm font-medium text-gray-700">75%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-500 h-2.5 rounded-full" style="width: 75%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">Teamwork</span>
                                            <span class="text-sm font-medium text-gray-700">90%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 90%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">Problem Solving</span>
                                            <span class="text-sm font-medium text-gray-700">80%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-500 h-2.5 rounded-full" style="width: 80%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="training-content" class="tab-content">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Training Certification</h3>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center" onclick="openEmployeeListModal()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                View Employee List
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Training Name</th>
                                        <th scope="col" class="px-4 py-3">Date Completed</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3">Certificate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-4 font-medium text-gray-900">Basic Crane Operation</td>
                                        <td class="px-4 py-4">March 15, 2023</td>
                                        <td class="px-4 py-4">
                                            <span class="status-badge status-valid">Completed</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <button class="text-blue-600 hover:text-blue-800 font-medium">Download</button>
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-4 font-medium text-gray-900">Advanced Safety Training</td>
                                        <td class="px-4 py-4">June 22, 2023</td>
                                        <td class="px-4 py-4">
                                            <span class="status-badge status-valid">Completed</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <button class="text-blue-600 hover:text-blue-800 font-medium">Download</button>
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-4 font-medium text-gray-900">Emergency Response</td>
                                        <td class="px-4 py-4">-</td>
                                        <td class="px-4 py-4">
                                            <span class="status-badge status-pending">Pending</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <button class="text-gray-400 font-medium" disabled>Not Available</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- NEW: Competency Management Tab Content -->
                <div id="management-content" class="tab-content">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Competency Management</h3>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center" onclick="openEmployeeListModal()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                View Employee List
                            </button>
                        </div>

                        <!-- Employee Profiles Section -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Employee Profiles</h4>
                            <p class="text-sm text-gray-600 mb-4">A list of employee profiles will be maintained. This will eventually be integrated with the Core Human Resources System once full system integration is implemented. Each profile will include employee details such as name, position, department, and relevant background information.</p>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3">Employee Name</th>
                                            <th scope="col" class="px-4 py-3">Position</th>
                                            <th scope="col" class="px-4 py-3">Department</th>
                                            <th scope="col" class="px-4 py-3">Experience</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-4 py-4 font-medium text-gray-900">John Smith</td>
                                            <td class="px-4 py-4">Crane Operator</td>
                                            <td class="px-4 py-4">Operations</td>
                                            <td class="px-4 py-4">3 years</td>
                                        </tr>
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-4 py-4 font-medium text-gray-900">Jane Smith</td>
                                            <td class="px-4 py-4">Safety Officer</td>
                                            <td class="px-4 py-4">Safety</td>
                                            <td class="px-4 py-4">5 years</td>
                                        </tr>
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-4 py-4 font-medium text-gray-900">Robert Johnson</td>
                                            <td class="px-4 py-4">Maintenance Lead</td>
                                            <td class="px-4 py-4">Maintenance</td>
                                            <td class="px-4 py-4">7 years</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Skill Assessment Section -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Skill Assessment</h4>
                            <p class="text-sm text-gray-600 mb-4">Skill assessments will be conducted to evaluate the knowledge, skills, and performance levels of employees. This can be done through different methods:</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="bg-blue-50 p-4 rounded-lg assessment-card">
                                    <h5 class="font-semibold text-blue-800 mb-2">Written or Practical Examination</h5>
                                    <p class="text-sm text-gray-700">Employees undergo written tests or hands-on practical exams to measure their technical knowledge and job-related skills.</p>
                                    <p class="text-xs text-gray-600 mt-2">Example: Safety training exams, technical knowledge tests, or machine operation demonstrations.</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg assessment-card">
                                    <h5 class="font-semibold text-green-800 mb-2">Self-Assessment</h5>
                                    <p class="text-sm text-gray-700">Employees rate their own skill levels and competencies based on a structured evaluation form.</p>
                                    <p class="text-xs text-gray-600 mt-2">This encourages self-reflection and provides insight into how employees perceive their own capabilities.</p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg assessment-card">
                                    <h5 class="font-semibold text-purple-800 mb-2">Work Evaluation (Manager Observation)</h5>
                                    <p class="text-sm text-gray-700">Managers and supervisors directly observe employees during their daily tasks.</p>
                                    <p class="text-xs text-gray-600 mt-2">Performance, work habits, and skill application are recorded and evaluated.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Competency Gaps Section -->
                        <div class="mb-8">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Competency Gaps</h4>
                            <p class="text-sm text-gray-600 mb-4">A Competency Gap List will be created to identify the required skills that employees may currently lack. These gaps will highlight the difference between the employee's existing skills and the skills required for their role or future career progression.</p>

                            <div class="bg-red-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-red-800 mb-2">Example:</h5>
                                <p class="text-sm text-gray-700">An operator may have strong practical skills but lack knowledge in safety compliance or customer service.</p>
                            </div>

                            <div class="mt-4">
                                <h5 class="font-semibold text-gray-800 mb-2">Identified Gaps for John Smith:</h5>
                                <ul class="list-disc pl-5 text-sm text-gray-700 space-y-2">
                                    <li class="gap-item pl-2">Safety compliance knowledge (40% proficiency)</li>
                                    <li class="gap-item pl-2">Advanced equipment troubleshooting (55% proficiency)</li>
                                    <li class="gap-item pl-2">Emergency response procedures (60% proficiency)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Assign Training Section -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Assign Training (Connected to Training Management System)</h4>
                            <p class="text-sm text-gray-600 mb-4">Based on the results of Skill Assessments and identified Competency Gaps, employees will be matched with the appropriate training programs. The Training Management System will recommend and assign training modules that directly address the skills employees need to improve.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="bg-green-50 p-4 rounded-lg training-item">
                                    <h5 class="font-semibold text-green-800 mb-2">Example 1:</h5>
                                    <p class="text-sm text-gray-700">If an employee shows weakness in safety procedures, they will be assigned a Safety Training Course.</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg training-item">
                                    <h5 class="font-semibold text-green-800 mb-2">Example 2:</h5>
                                    <p class="text-sm text-gray-700">If an employee lacks communication skills, they will be assigned Customer Service Training.</p>
                                </div>
                            </div>

                            <div class="bg-green-100 p-4 rounded-lg">
                                <p class="text-sm text-green-800 font-medium">This ensures that training is targeted, efficient, and aligned with both employee development and organizational needs.</p>
                            </div>

                            <div class="mt-6">
                                <h5 class="font-semibold text-gray-800 mb-3">Recommended Training for John Smith:</h5>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                                        <div>
                                            <h6 class="font-medium text-gray-900">Advanced Safety Compliance Course</h6>
                                            <p class="text-sm text-gray-600">Addresses safety compliance knowledge gap</p>
                                        </div>
                                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Assign</button>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                                        <div>
                                            <h6 class="font-medium text-gray-900">Equipment Troubleshooting Workshop</h6>
                                            <p class="text-sm text-gray-600">Improves advanced equipment troubleshooting skills</p>
                                        </div>
                                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Assign</button>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
                                        <div>
                                            <h6 class="font-medium text-gray-900">Emergency Response Training</h6>
                                            <p class="text-sm text-gray-600">Enhances emergency response procedure knowledge</p>
                                        </div>
                                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Assign</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content -->

<!-- Employee List Modal -->
<div id="employee-list-modal" class="modal">
    <div class="modal-content employee-modal">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Employee List</h3>
            <button onclick="closeEmployeeListModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-4">
            <div class="relative">
                <input type="text" id="employee-search" placeholder="Search employees..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-5 h-5 absolute right-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2">
            <!-- Employee Cards -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 employee-card">
                <div class="flex items-center mb-3">
                    <img class="w-12 h-12 rounded-full object-cover mr-3" src="images/uploadprof.png" alt="John Smith">
                    <div>
                        <h4 class="font-semibold text-gray-900">John Smith</h4>
                        <p class="text-sm text-gray-600">Crane Operator</p>
                    </div>
                </div>
                <div class="text-sm text-gray-700">
                    <p><span class="font-medium">Department:</span> Operations</p>
                    <p><span class="font-medium">Experience:</span> 3 years</p>
                    <p><span class="font-medium">Status:</span> <span class="text-green-600">Active</span></p>
                </div>
                <button class="w-full mt-3 bg-blue-100 text-blue-700 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition" onclick="selectEmployee('Jane Smith')">Select Employee</button>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 employee-card">
                <div class="flex items-center mb-3">
                    <img class="w-12 h-12 rounded-full object-cover mr-3" src="images/uploadprof.png" alt="Robert Johnson">
                    <div>
                        <h4 class="font-semibold text-gray-900">Robert Johnson</h4>
                        <p class="text-sm text-gray-600">Maintenance Technician</p>
                    </div>
                </div>
                <div class="text-sm text-gray-700">
                    <p><span class="font-medium">Department:</span> Maintenance</p>
                    <p><span class="font-medium">Experience:</span> 7 years</p>
                    <p><span class="font-medium">Status:</span> <span class="text-green-600">Active</span></p>
                </div>
                <button class="w-full mt-3 bg-blue-100 text-blue-700 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition" onclick="selectEmployee('Robert Johnson')">Select Employee</button>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg p-4 employee-card">
                <div class="flex items-center mb-3">
                    <img class="w-12 h-12 rounded-full object-cover mr-3" src="images/uploadprof.png" alt="Maria Garcia">
                    <div>
                        <h4 class="font-semibold text-gray-900">Maria Garcia</h4>
                        <p class="text-sm text-gray-600">Customer Service Visor</p>
                    </div>
                </div>
                <div class="text-sm text-gray-700">
                    <p><span class="font-medium">Department:</span> CS Dept</p>
                    <p><span class="font-medium">Experience:</span> 10 years</p>
                    <p><span class="font-medium">Status:</span> <span class="text-green-600">Active</span></p>
                </div>
                <button class="w-full mt-3 bg-blue-100 text-blue-700 py-2 rounded-lg text-sm font-medium hover:bg-blue-200 transition" onclick="selectEmployee('Sarah Brown')">Select Employee</button>
            </div>
        </div>
    </div>
</div>

<!-- Certification Modal -->
<div id="certification-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Add New Certification</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="certification-form">
            <div class="mb-4">
                <label for="certification-name" class="block text-sm font-medium text-gray-700 mb-1">Certification Name</label>
                <input type="text" id="certification-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="issued-date" class="block text-sm font-medium text-gray-700 mb-1">Issued Date</label>
                    <input type="date" id="issued-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="expiry-date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" id="expiry-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<script>
    // Toggle submenu function
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        const arrow = document.getElementById(id.replace('submenu', 'arrow'));

        if (submenu.classList.contains('open')) {
            submenu.classList.remove('open');
            submenu.classList.add('close');
            arrow.classList.remove('rotate-90');
            arrow.classList.add('rotate-0');
        } else {
            submenu.classList.remove('close');
            submenu.classList.add('open');
            arrow.classList.remove('rotate-0');
            arrow.classList.add('rotate-90');
        }
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

        if (!button || !button.querySelector('svg')) {
            dropdown.classList.remove('open');
        }
    });

    // Philippine Time Display
    function updatePhilippineTime() {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        const phTime = now.toLocaleString('en-PH', options);
        document.getElementById('philippineTime').textContent = phTime;
    }

    // Update time every second
    setInterval(updatePhilippineTime, 1000);
    updatePhilippineTime();

    // Tab switching functionality
    function switchTab(tabName) {
        // Hide all tab contents
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });

        // Remove active class from all tabs
        const tabs = document.querySelectorAll('#competency-tabs button');
        tabs.forEach(tab => {
            tab.classList.remove('active-tab');
            tab.classList.remove('border-blue-600', 'text-blue-600');
        });

        // Show selected tab content
        document.getElementById(`${tabName}-content`).classList.add('active');

        // Add active class to selected tab
        document.getElementById(`${tabName}-tab`).classList.add('active-tab', 'border-blue-600', 'text-blue-600');
    }

    // Initialize with certification tab active
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('certification');
    });

    // Handle file selection for certification upload
    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            // In a real application, you would upload the file to a server here
            // For this demo, we'll just show a success message
            showAlert('success', 'Certification uploaded successfully!');

            // Reset the file input
            event.target.value = '';
        }
    }

    // Show alert message
    function showAlert(type, message) {
        const alert = document.getElementById(`alert-${type}`);
        alert.textContent = message;
        alert.style.display = 'block';

        // Hide alert after 5 seconds
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }

    // Employee List Modal functions
    function openEmployeeListModal() {
        document.getElementById('employee-list-modal').style.display = 'flex';
    }

    function closeEmployeeListModal() {
        document.getElementById('employee-list-modal').style.display = 'none';
    }

    // Select employee function
    function selectEmployee(employeeName) {
        // In a real application, you would load the selected employee's data
        // For this demo, we'll just show an alert and close the modal
        showAlert('success', `Selected employee: ${employeeName}`);
        closeEmployeeListModal();

        // Update the current employee name in the profile card
        document.querySelector('.profile-card h2').textContent = employeeName;
    }

    // Certification Modal functions
    function openModal() {
        document.getElementById('certification-modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('certification-modal').style.display = 'none';
    }

    // Handle certification form submission
    document.getElementById('certification-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.getElementById('certification-name').value;
        const issuedDate = document.getElementById('issued-date').value;
        const expiryDate = document.getElementById('expiry-date').value;

        // Format dates for display
        const issued = new Date(issuedDate).toLocaleDateString();
        const expiry = expiryDate ? new Date(expiryDate).toLocaleDateString() : 'N/A';

        // Determine status based on expiry date
        let status = 'Valid';
        let statusClass = 'status-valid';

        if (expiryDate) {
            const today = new Date();
            const expiryDateObj = new Date(expiryDate);

            if (expiryDateObj < today) {
                status = 'Expired';
                statusClass = 'status-expired';
            } else if ((expiryDateObj - today) / (1000 * 60 * 60 * 24) < 30) {
                status = 'Expiring Soon';
                statusClass = 'status-pending';
            }
        }

        // Add new certification to the table
        const tableBody = document.getElementById('certifications-table-body');
        const newRow = document.createElement('tr');
        newRow.className = 'bg-white border-b hover:bg-gray-50';
        newRow.innerHTML = `
            <td class="px-4 py-4 font-medium text-gray-900">${name}</td>
            <td class="px-4 py-4">${issued}</td>
            <td class="px-4 py-4">${expiry}</td>
            <td class="px-4 py-4"><span class="status-badge ${statusClass}">${status}</span></td>
        `;
        tableBody.appendChild(newRow);

        // Close modal and reset form
        closeModal();
        document.getElementById('certification-form').reset();

        // Show success message
        showAlert('success', 'Certification added successfully!');
    });

    // Employee search functionality
    document.getElementById('employee-search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const employeeCards = document.querySelectorAll('.employee-card');

        employeeCards.forEach(card => {
            const employeeName = card.querySelector('h4').textContent.toLowerCase();
            if (employeeName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
