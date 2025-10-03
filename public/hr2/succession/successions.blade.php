<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Succession Planning Dashboard</title>
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
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 8px solid #fff;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .succession-content {
            display: none;
        }

        /* Enhanced Professional Styling */
        .dashboard-card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .table-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .table-header {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            color: white;
        }

        .table-row:hover {
            background-color: #f9fafb;
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .nav-pill {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .nav-pill.active {
            background-color: #2563eb;
            color: white;
        }

        .nav-pill:hover:not(.active) {
            background-color: #f3f4f6;
        }

        .content-section {
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* New Professional Elements */
        .metric-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-left: 4px solid #3b82f6;
            padding: 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e40af;
            line-height: 1;
        }

        .metric-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }

        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #e5e7eb;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .filter-control {
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .action-btn-primary {
            background-color: #3b82f6;
            color: white;
            border: none;
        }

        .action-btn-primary:hover {
            background-color: #2563eb;
        }

        .action-btn-secondary {
            background-color: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .action-btn-secondary:hover {
            background-color: #f9fafb;
        }

        .search-box {
            background-color: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            width: 100%;
            max-width: 300px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
        }

        .section-subtitle {
            font-size: 1rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .risk-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .high-risk { background-color: #ef4444; }
        .medium-risk { background-color: #f59e0b; }
        .low-risk { background-color: #10b981; }
    </style>
</head>
<body class="bg-gray-50">

   <!-- Navbar -->
   <nav class="fixed bg-[#111111] top-0 z-50 w-full shadow">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-white rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="#" class="flex items-center ms-2 md:me-24">
                    <img src="https://hr2.cranecali-ms.com/images/logo.png" class="h-8 me-2" alt="Logo">
                    <span class="self-center text-xl font-extrabold sm:text-2xl whitespace-nowrap text-white">CaliCrane</span>
                </a>
            </div>

            <div class="flex items-center">
                <!-- Philippine Time Display -->
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
                            <!-- New Department Notification -->
                            <div id="department-notification" class="p-4 border-b border-gray-200 hover:bg-gray-50">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Department Notification</p>
                                        <p class="mt-1 text-sm text-gray-500">You have a notification from another department</p>
                                        <div class="mt-2 flex space-x-2">
                                            <button onclick="handleNotificationResponse('confirm')" class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600">Confirm</button>
                                            <button onclick="handleNotificationResponse('reject')" class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">Reject</button>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-400">Just now</p>
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
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full object-cover"
                                src="https://hr2.cranecali-ms.com/images/uploadprof.png"
                                alt="Profile Photo">
                        </button>
                    </div>

                    <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-sm shadow-sm bg-white shadow" id="dropdown-user">
                        <!-- Profile Image in dropdown -->
                        <div class="flex justify-center items-center p-2">
                            <img class="w-8 h-8 rounded-full object-cover" src="https://hr2.cranecali-ms.com/images/uploadprof.png" alt="Profile Photo">
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
                                <form action="#" method="POST">
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
<!-- Navbar -->

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<!-- Sidebar -->
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
                        <a href="#" onclick="showContent('key-roles')" class="block p-2 rounded-lg hover:bg-blue-900 transition">Key Roles</a>
                    </li>
                    <li class="my-2">
                        <a href="#" onclick="showContent('development-plan')" class="block p-2 rounded-lg hover:bg-blue-900 transition">Development Plan</a>
                    </li>
                    <li class="my-2">
                        <a href="#" onclick="showContent('contingency')" class="block p-2 rounded-lg hover:bg-blue-900 transition">Contingency</a>
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
</aside>
<!-- Sidebar -->

<!-- Main Content -->
<div class="p-4 sm:ml-64">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-14">

        <!-- Breadcrumb -->
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
                    <a href="https://hr2.cranecali-ms.com" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Dashboard</a>
                </div>
                </li>
            </ol>
        </div>
        <!-- Breadcrumb -->

        <!-- Succession Planning Navigation -->
        <div class="bg-white p-4 rounded-lg mb-6 dashboard-card">
            <div class="flex flex-wrap gap-2">
                <div class="nav-pill active" onclick="showContent('key-roles')">Key Roles</div>
                <div class="nav-pill" onclick="showContent('development-plan')">Development Plan</div>
                <div class="nav-pill" onclick="showContent('contingency')">Contingency</div>
            </div>
        </div>

        <!-- Succession Planning Overview Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="metric-card">
                <div class="metric-value">12</div>
                <div class="metric-label">Key Positions</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">24</div>
                <div class="metric-label">Succession Candidates</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">8</div>
                <div class="metric-label">Development Plans</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">92%</div>
                <div class="metric-label">Readiness Score</div>
            </div>
        </div>

        <!-- Key Roles Content -->
        <div id="key-roles" class="succession-content content-section">
            <div class="bg-white p-6 rounded-lg shadow-sm dashboard-card">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Key Roles</h2>
                        <p class="section-subtitle">Critical positions requiring succession planning</p>
                    </div>
                    <div class="flex gap-2">
                        <input type="text" class="search-box" placeholder="Search positions...">
                        <select class="filter-control">
                            <option>All Departments</option>
                            <option>Tools & Equipment</option>
                            <option>Customer Service</option>
                            <option>Crane & Truck Monitoring</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Key Positions Card -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Critical Positions</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <div>
                                    <h4 class="font-semibold">Operations Officer</h4>
                                    <p class="text-sm text-gray-600">Operations Department</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Readiness</div>
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 85%"></div>
                                        </div>
                                        <span class="text-sm font-medium">85%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <div>
                                    <h4 class="font-semibold">Customer Service Visor</h4>
                                    <p class="text-sm text-gray-600">CS Department</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Readiness</div>
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 65%"></div>
                                        </div>
                                        <span class="text-sm font-medium">65%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <div>
                                    <h4 class="font-semibold">Maintenance Supervisor</h4>
                                    <p class="text-sm text-gray-600">Maintenance Department</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Readiness</div>
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                            <div class="bg-red-500 h-2.5 rounded-full" style="width: 45%"></div>
                                        </div>
                                        <span class="text-sm font-medium">45%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Succession Candidates Card -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Succession Candidates</h3>
                        <div class="space-y-4">
                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John Smith" class="employee-avatar mr-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold">John Smith</h4>
                                    <p class="text-sm text-gray-600">Tools & Equipment Manager</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Readiness</div>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">85%</span>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Maria Garcia" class="employee-avatar mr-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold">Maria Garcia</h4>
                                    <p class="text-sm text-gray-600">Customer Service</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Readiness</div>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">65%</span>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Robert Johnson" class="employee-avatar mr-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold">Robert Johnson</h4>
                                    <p class="text-sm text-gray-600">Maintenance Lead</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Readiness</div>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">45%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee List Table -->
                <div class="table-container mt-6">
                    <div class="table-header p-4">
                        <h3 class="text-lg font-semibold">Employee List</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Employee</th>
                                    <th scope="col" class="px-6 py-3">Position</th>
                                    <th scope="col" class="px-6 py-3">Department</th>
                                    <th scope="col" class="px-6 py-3">Training Status</th>
                                    <th scope="col" class="px-6 py-3">Readiness</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John Smith" class="employee-avatar mr-3">
                                            <div>
                                                <div>John Smith</div>
                                                <div class="text-xs text-gray-500">ID: EMP001</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">Tools & Equipment Manager</td>
                                    <td class="px-6 py-4">HR Facilitator</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-green-100 text-green-800">Completed</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                                <div class="bg-green-600 h-2.5 rounded-full" style="width: 85%"></div>
                                            </div>
                                            <span class="text-sm font-medium">85%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="action-btn action-btn-primary mr-2">View</button>
                                        <button class="action-btn action-btn-secondary">Assign</button>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Maria Garcia" class="employee-avatar mr-3">
                                            <div>
                                                <div>Maria Garcia</div>
                                                <div class="text-xs text-gray-500">ID: EMP002</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">Customer Service Visor</td>
                                    <td class="px-6 py-4">CS Dept</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-yellow-100 text-yellow-800">In Progress</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 65%"></div>
                                            </div>
                                            <span class="text-sm font-medium">65%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="action-btn action-btn-primary mr-2">View</button>
                                        <button class="action-btn action-btn-secondary">Assign</button>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Robert Johnson" class="employee-avatar mr-3">
                                            <div>
                                                <div>Robert Johnson</div>
                                                <div class="text-xs text-gray-500">ID: EMP003</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">Crane and Truck Monitoring</td>
                                    <td class="px-6 py-4">HR</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-blue-100 text-blue-800">Started</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2">
                                                <div class="bg-blue-500 h-2.5 rounded-full" style="width: 45%"></div>
                                            </div>
                                            <span class="text-sm font-medium">45%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="action-btn action-btn-primary mr-2">View</button>
                                        <button class="action-btn action-btn-secondary">Assign</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Development Plan Content -->
        <div id="development-plan" class="succession-content content-section">
            <div class="bg-white p-6 rounded-lg shadow-sm dashboard-card">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Development Plans</h2>
                        <p class="section-subtitle">Individual development plans for succession candidates</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Development Activities</h3>
                        <div class="space-y-4">
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold">Leadership Training</h4>
                                    <span class="status-badge bg-green-100 text-green-800">Completed</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">Advanced leadership course for senior management roles</p>
                                <div class="text-xs text-gray-500">Completed: 15 Oct 2023</div>
                            </div>
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold">Safety Certification</h4>
                                    <span class="status-badge bg-yellow-100 text-yellow-800">In Progress</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">OSHA certification for safety management</p>
                                <div class="text-xs text-gray-500">Due: 30 Nov 2023</div>
                            </div>
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold">Technical Skills Upgrade</h4>
                                    <span class="status-badge bg-red-100 text-red-800">Not Started</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">Advanced equipment operation and maintenance</p>
                                <div class="text-xs text-gray-500">Scheduled: Jan 2024</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Progress Overview</h3>
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">John Smith</span>
                                    <span class="text-sm font-medium text-gray-700">85%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-green-600" style="width: 85%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Maria Garcia</span>
                                    <span class="text-sm font-medium text-gray-700">65%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-yellow-500" style="width: 65%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Robert Johnson</span>
                                    <span class="text-sm font-medium text-gray-700">45%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill bg-red-500" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contingency Content -->
        <div id="contingency" class="succession-content content-section">
            <div class="bg-white p-6 rounded-lg shadow-sm dashboard-card">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Contingency Planning</h2>
                        <p class="section-subtitle">Emergency succession and backup plans</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="action-btn action-btn-primary">
                            <i class="fas fa-download mr-2"></i>Export Report
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-red-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Emergency Succession</h3>
                        <div class="space-y-4">
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <h4 class="font-semibold mb-2">Operations Officer</h4>
                                <div class="flex items-center mb-2">
                                    <div class="text-sm text-gray-600 mr-4">Primary:</div>
                                    <div class="flex items-center">
                                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="John Smith" class="employee-avatar mr-2">
                                        <span>John Smith</span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 mr-4">Backup:</div>
                                    <div class="flex items-center">
                                        <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Robert Garcia" class="employee-avatar mr-2">
                                        <span>Robert Garcia</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <h4 class="font-semibold mb-2">Safety Manager</h4>
                                <div class="flex items-center mb-2">
                                    <div class="text-sm text-gray-600 mr-4">Primary:</div>
                                    <div class="flex items-center">
                                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Maria Garcia" class="employee-avatar mr-2">
                                        <span>Maria Garcia</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Risk Assessment</h3>
                        <div class="space-y-4">
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold">Operations Department</h4>
                                    <span class="status-badge bg-yellow-100 text-yellow-800">Medium Risk</span>
                                </div>
                                <p class="text-sm text-gray-600">2 key positions with adequate backup</p>
                            </div>
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold">Maintenance Department</h4>
                                    <span class="status-badge bg-red-100 text-red-800">High Risk</span>
                                </div>
                                <p class="text-sm text-gray-600">1 key position with limited backup options</p>
                            </div>
                            <div class="p-4 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold">Safety Department</h4>
                                    <span class="status-badge bg-green-100 text-green-800">Low Risk</span>
                                </div>
                                <p class="text-sm text-gray-600">All key positions have trained backups</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Main Content -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
    // Submenu toggle function
    function toggleSubmenu(submenuId) {
        const submenu = document.getElementById(submenuId);
        const arrow = document.getElementById(submenuId.replace('-submenu', '-arrow'));

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

    // Notification toggle function
    function toggleNotification() {
        const notificationDropdown = document.getElementById('notification-dropdown');
        notificationDropdown.classList.toggle('open');
    }

    // Close notification when clicking outside
    document.addEventListener('click', function(event) {
        const notificationDropdown = document.getElementById('notification-dropdown');
        const notificationButton = event.target.closest('button[onclick="toggleNotification()"]');

        if (!notificationButton && !notificationDropdown.contains(event.target)) {
            notificationDropdown.classList.remove('open');
        }
    });

    // Handle notification response
    function handleNotificationResponse(action) {
        const notification = document.getElementById('department-notification');
        if (action === 'confirm') {
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">Request Confirmed</p>
                        <p class="mt-1 text-sm text-gray-500">You have confirmed the department notification.</p>
                        <p class="mt-1 text-xs text-gray-400">Just now</p>
                    </div>
                </div>
            `;
        } else if (action === 'reject') {
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">Request Rejected</p>
                        <p class="mt-1 text-sm text-gray-500">You have rejected the department notification.</p>
                        <p class="mt-1 text-xs text-gray-400">Just now</p>
                    </div>
                </div>
            `;
        }
    }

    // Show loading overlay
    function showLoadingOverlay() {
        document.getElementById('loading-overlay').style.display = 'flex';
    }

    // Hide loading overlay
    function hideLoadingOverlay() {
        document.getElementById('loading-overlay').style.display = 'none';
    }

    // Succession Planning Content Navigation
    function showContent(contentId) {
        // Hide all content sections
        const contents = document.querySelectorAll('.succession-content');
        contents.forEach(content => {
            content.style.display = 'none';
        });

        // Remove active class from all nav pills
        const navPills = document.querySelectorAll('.nav-pill');
        navPills.forEach(pill => {
            pill.classList.remove('active');
        });

        // Show selected content
        document.getElementById(contentId).style.display = 'block';

        // Add active class to clicked nav pill
        event.target.classList.add('active');
    }

    // Philippine Time Display
    function updatePhilippineTime() {
        const options = {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const formatter = new Intl.DateTimeFormat('en-PH', options);
        const philippineTime = formatter.format(new Date());
        document.getElementById('philippineTime').textContent = philippineTime;
    }

    // Update time immediately and then every second
    updatePhilippineTime();
    setInterval(updatePhilippineTime, 1000);

    // Initialize with Key Roles content visible
    document.addEventListener('DOMContentLoaded', function() {
        showContent('key-roles');
    });
</script>

</body>
</html>
