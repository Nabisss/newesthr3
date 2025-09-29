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

        /* Loading screen styles */
        #loading-screen {
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

        .crane-loader {
            width: 120px;
            height: 120px;
            position: relative;
            animation: crane-lift 2s infinite ease-in-out;
        }

        .crane-loader img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        @keyframes crane-lift {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .loading-text {
            color: white;
            margin-top: 20px;
            font-size: 1.2rem;
            text-align: center;
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
    </style>
</head>
<body>

   <!-- Loading Screen -->
   <div id="loading-screen">
        <div class="flex flex-col items-center">
            <div class="crane-loader">
                <img src="images/logo.png" alt="CaliCrane Logo">
            </div>
            <p class="loading-text">Loading Competency Management...</p>
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
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 .75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="#" class="flex items-center ms-2 md:me-24">
                    <img src="images/logo.png" class="h-8 me-2" alt="Logo">
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
                                John Doe
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
                    <a href="http://127.0.0.1:8000/dashboard" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Dashboard</a>
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
                        <h2 class="text-xl font-bold text-gray-900">John Doe</h2>
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
                        </ul>
                    </div>
                </div>

                <!-- Tab Contents -->
                <div id="certification-content" class="tab-content active">
                    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">License & Certification Validity</h3>
                            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5" onclick="openUploadModal()">
                                Upload Proof
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
                                        <th scope="col" class="px-4 py-3">Reminder</th>
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Competency Evaluation</h3>
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
                                            <span class="text-sm font-medium text-gray-700">Safety Procedures</span>
                                            <span class="text-sm font-medium text-gray-700">92%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 92%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700">Equipment Maintenance</span>
                                            <span class="text-sm font-medium text-gray-700">78%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 78%"></div>
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
                                            <span class="text-sm font-medium text-gray-700">88%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 88%"></div>
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
                                            <span class="text-sm font-medium text-gray-700">82%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 82%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">Latest Evaluation</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700"><span class="font-semibold">Date:</span> October 15, 2023</p>
                                <p class="text-gray-700"><span class="font-semibold">Evaluator:</span> Maria Santos</p>
                                <p class="text-gray-700"><span class="font-semibold">Overall Rating:</span> 4.2/5</p>
                                <p class="text-gray-700 mt-2"><span class="font-semibold">Comments:</span> John demonstrates excellent crane operation skills and follows safety protocols diligently. Continued improvement in equipment maintenance knowledge is recommended.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="training-content" class="tab-content">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Training Certifications</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Training Program</th>
                                        <th scope="col" class="px-4 py-3">Completion Date</th>
                                        <th scope="col" class="px-4 py-3">Validity Period</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3">Certificate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white border-b">
                                        <td class="px-4 py-4 font-medium text-gray-900">Advanced Crane Safety</td>
                                        <td class="px-4 py-4">March 15, 2023</td>
                                        <td class="px-4 py-4">2 years</td>
                                        <td class="px-4 py-4">
                                            <span class="status-badge status-valid">Valid</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <button type="button" class="text-blue-600 hover:text-blue-900 font-medium">Download</button>
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b">
                                        <td class="px-4 py-4 font-medium text-gray-900">Heavy Equipment Operation</td>
                                        <td class="px-4 py-4">January 20, 2023</td>
                                        <td class="px-4 py-4">3 years</td>
                                        <td class="px-4 py-4">
                                            <span class="status-badge status-valid">Valid</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <button type="button" class="text-blue-600 hover:text-blue-900 font-medium">Download</button>
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b">
                                        <td class="px-4 py-4 font-medium text-gray-900">Emergency Response Training</td>
                                        <td class="px-4 py-4">June 10, 2022</td>
                                        <td class="px-4 py-4">1 year</td>
                                        <td class="px-4 py-4">
                                            <span class="status-badge status-expired">Expired</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <button type="button" class="text-blue-600 hover:text-blue-900 font-medium">Download</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="upload-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Upload Certification Proof</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeUploadModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="upload-form">
            <div class="mb-4">
                <label for="certification-type" class="block text-sm font-medium text-gray-700 mb-2">Certification Type</label>
                <select id="certification-type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Certification Type</option>
                    <option value="Crane Operator License">Crane Operator License</option>
                    <option value="Safety Certification">Safety Certification</option>
                    <option value="First Aid Certification">First Aid Certification</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="issued-date" class="block text-sm font-medium text-gray-700 mb-2">Issued Date</label>
                <input type="date" id="issued-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="expiry-date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                <input type="date" id="expiry-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="modal-file-input" class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                <div class="upload-area" onclick="document.getElementById('modal-file-input').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-gray-600 text-sm">Click to upload or drag and drop</p>
                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, PDF up to 10MB</p>
                    <input type="file" id="modal-file-input" class="hidden" accept=".png,.jpg,.jpeg,.pdf">
                </div>
                <p id="file-name" class="text-sm text-gray-600 mt-2"></p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300" onclick="closeUploadModal()">Cancel</button>
                <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700" onclick="submitCertification()">Upload</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<script>
    // Hide loading screen after page loads
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.getElementById('loading-screen').style.opacity = '0';
            setTimeout(function() {
                document.getElementById('loading-screen').style.display = 'none';
            }, 500);
        }, 1000);
    });

    // Initialize data
    let certifications = [
        {
            id: 1,
            name: "Crane Operator License",
            issuedDate: "2023-03-15",
            expiryDate: "2025-03-15",
            status: "Valid",
            reminder: "2 months before expiry"
        },
        {
            id: 2,
            name: "Safety Certification",
            issuedDate: "2022-06-10",
            expiryDate: "2023-06-10",
            status: "Expired",
            reminder: "Already expired"
        }
    ];

    // Philippine Time Display
    function updatePhilippineTime() {
        const now = new Date();
        const phTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZone: 'Asia/Manila'
        };
        document.getElementById('philippineTime').textContent = phTime.toLocaleDateString('en-PH', options);
    }

    setInterval(updatePhilippineTime, 1000);
    updatePhilippineTime();

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
        const notificationButton = document.querySelector('[onclick="toggleNotification()"]');

        if (!notificationButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('open');
        }
    });

    // Tab switching functionality
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        // Remove active class from all tabs
        document.querySelectorAll('#competency-tabs button').forEach(tab => {
            tab.classList.remove('active-tab');
            tab.classList.remove('border-blue-600');
            tab.classList.remove('text-blue-600');
        });

        // Show selected tab content
        document.getElementById(`${tabName}-content`).classList.add('active');

        // Add active class to selected tab
        document.getElementById(`${tabName}-tab`).classList.add('active-tab', 'border-blue-600', 'text-blue-600');
    }

    // Initialize the certifications table
    function renderCertificationsTable() {
        const tbody = document.getElementById('certifications-table-body');
        tbody.innerHTML = '';

        certifications.forEach(cert => {
            const row = document.createElement('tr');
            row.className = 'bg-white border-b';
            row.innerHTML = `
                <td class="px-4 py-4 font-medium text-gray-900">${cert.name}</td>
                <td class="px-4 py-4">${formatDate(cert.issuedDate)}</td>
                <td class="px-4 py-4">${formatDate(cert.expiryDate)}</td>
                <td class="px-4 py-4">
                    <span class="status-badge ${getStatusClass(cert.status)}">${cert.status}</span>
                </td>
                <td class="px-4 py-4">${cert.reminder}</td>
            `;
            tbody.appendChild(row);
        });
    }

    // Format date to readable format
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    // Get status class based on status text
    function getStatusClass(status) {
        switch(status) {
            case 'Valid': return 'status-valid';
            case 'Expired': return 'status-expired';
            case 'Pending': return 'status-pending';
            default: return 'status-active';
        }
    }

    // Modal functions
    function openUploadModal() {
        document.getElementById('upload-modal').style.display = 'flex';
    }

    function closeUploadModal() {
        document.getElementById('upload-modal').style.display = 'none';
        document.getElementById('upload-form').reset();
        document.getElementById('file-name').textContent = '';
    }

    // Handle file selection
    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('file-name').textContent = `Selected file: ${file.name}`;
        }
    }

    // Submit certification
    function submitCertification() {
        const type = document.getElementById('certification-type').value;
        const issuedDate = document.getElementById('issued-date').value;
        const expiryDate = document.getElementById('expiry-date').value;
        const fileInput = document.getElementById('modal-file-input');

        if (!type || !issuedDate || !expiryDate || !fileInput.files[0]) {
            showAlert('error', 'Please fill in all fields and select a file.');
            return;
        }

        // Create new certification object
        const newCert = {
            id: certifications.length + 1,
            name: type,
            issuedDate: issuedDate,
            expiryDate: expiryDate,
            status: new Date(expiryDate) > new Date() ? 'Valid' : 'Expired',
            reminder: calculateReminder(expiryDate)
        };

        // Add to certifications array
        certifications.push(newCert);

        // Update table
        renderCertificationsTable();

        // Close modal and show success message
        closeUploadModal();
        showAlert('success', 'Certification uploaded successfully!');
    }

    // Calculate reminder text based on expiry date
    function calculateReminder(expiryDate) {
        const today = new Date();
        const expiry = new Date(expiryDate);
        const diffTime = expiry - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays < 0) {
            return 'Already expired';
        } else if (diffDays < 30) {
            return 'Expires soon';
        } else if (diffDays < 90) {
            return '3 months before expiry';
        } else {
            return '6 months before expiry';
        }
    }

    // Show alert message
    function showAlert(type, message) {
        const alert = document.getElementById(`alert-${type}`);
        alert.textContent = message;
        alert.style.display = 'block';

        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        renderCertificationsTable();
        switchTab('certification');

        // Set up file input event listeners
        document.getElementById('modal-file-input').addEventListener('change', handleFileSelect);
    });
</script>
</body>
</html>
