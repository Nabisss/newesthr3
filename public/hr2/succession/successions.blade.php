<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Dashboard</title>
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

        /* New Professional Styling */
        .dashboard-card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 500;
        }

        .table-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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
                    <img src="{{ asset('images/logo.png') }}" class="h-8 me-2" alt="Logo">
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
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full object-cover"
                                src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </button>
                    </div>

                    <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-sm shadow-sm bg-white shadow" id="dropdown-user">
                        <!-- Profile Image in dropdown -->
                        <div class="flex justify-center items-center p-2">
                            <img class="w-20 h-20 rounded-full shadow-lg object-cover"
                                src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </div>

                        <!-- User Info -->
                        <div class="px-4 py-3 text-center" role="none">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ Auth::user()->name }} {{ Auth::user()->lastname }}
                            </p>
                            <p class="text-sm font-medium text-gray-500 truncate">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        <!-- Dropdown Links -->
                        <ul class="py-1" role="none">
                            <li>
                                <a href="{{ route('profile') }}"
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

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
    <div class="loading-spinner"></div>
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
                <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
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
            </ol>
        </div>
        <!-- breadcrumb -->

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Confirmed Employees Card -->
            <div class="dashboard-card bg-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="stat-value">3</h2>
                        <p class="stat-label">Confirmed Employees</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-gray-500 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Updated 2 hours ago
                    </div>
                </div>
            </div>

            <!-- Employees in Training Card -->
            <div class="dashboard-card bg-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="stat-value">4</h2>
                        <p class="stat-label">In Training</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-gray-500 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                        Updated 2 hours ago
                    </div>
                </div>
            </div>

            <!-- Employees Studying Card -->
            <div class="dashboard-card bg-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="stat-value">6</h2>
                        <p class="stat-label">Currently Studying</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-gray-500 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        Updated 2 hours ago
                    </div>
                </div>
            </div>
        </div>

        <!-- Succession Planning Navigation -->
        <div class="bg-white p-4 rounded-lg mb-6 dashboard-card">
            <div class="flex flex-wrap gap-2">
                <div class="nav-pill active" onclick="showContent('key-roles')">Key Roles</div>
                <div class="nav-pill" onclick="showContent('development-plan')">Development Plan</div>
                <div class="nav-pill" onclick="showContent('contingency')">Contingency</div>
            </div>
        </div>

        <!-- Key Roles Content -->
        <div id="key-roles" class="succession-content content-section">
            <div class="bg-white p-6 rounded-lg shadow-sm dashboard-card">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Key Roles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Key Positions Card -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-blue-800">Key Positions</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <span class="font-medium">Crane Operator</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">High Priority</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <span class="font-medium">Maintenance Supervisor</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Medium Priority</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <span class="font-medium">Safety Officer</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">High Priority</span>
                            </div>
                        </div>
                    </div>

                    <!-- Succession Candidates Card -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-green-800">Succession Candidates</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <div>
                                    <span class="font-medium block">John Smith</span>
                                    <span class="text-sm text-gray-500">Crane Operator Trainee</span>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Ready in 6-12 mos</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <div>
                                    <span class="font-medium block">Maria Garcia</span>
                                    <span class="text-sm text-gray-500">Maintenance Technician</span>
                                </div>
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Ready in 12-18 mos</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm">
                                <div>
                                    <span class="font-medium block">Robert Johnson</span>
                                    <span class="text-sm text-gray-500">Safety Assistant</span>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Ready in 3-6 mos</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Key Roles Table -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Key Roles Overview</h3>
                    <div class="table-container">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="text-xs text-white uppercase table-header">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Position</th>
                                    <th scope="col" class="px-6 py-4">Current Holder</th>
                                    <th scope="col" class="px-6 py-4">Potential Successors</th>
                                    <th scope="col" class="px-6 py-4">Readiness Level</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">Senior Crane Operator</td>
                                    <td class="px-6 py-4">Michael Chen</td>
                                    <td class="px-6 py-4">John Smith, David Wilson</td>
                                    <td class="px-6 py-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 70%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-blue-100 text-blue-800">In Progress</span>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">Maintenance Supervisor</td>
                                    <td class="px-6 py-4">Sarah Johnson</td>
                                    <td class="px-6 py-4">Maria Garcia, James Brown</td>
                                    <td class="px-6 py-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-yellow-100 text-yellow-800">Planning</span>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">Safety Officer</td>
                                    <td class="px-6 py-4">Emily Williams</td>
                                    <td class="px-6 py-4">Robert Johnson, Jennifer Lee</td>
                                    <td class="px-6 py-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 90%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-green-100 text-green-800">Ready</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Development Plan Content -->
        <div id="development-plan" class="succession-content content-section" style="display: none;">
            <div class="bg-white p-6 rounded-lg shadow-sm dashboard-card">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Development Plans</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Development Activities Card -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-purple-800">Development Activities</h3>
                        <div class="space-y-4">
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium block">Leadership Training</span>
                                        <span class="text-sm text-gray-500">For Crane Operator candidates</span>
                                    </div>
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">Ongoing</span>
                                </div>
                                <div class="mt-2 text-sm">
                                    <span class="text-gray-600">Completion: 75%</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: 75%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="font-medium block">Technical Certification</span>
                                        <span class="text-sm text-gray-500">For Maintenance candidates</span>
                                    </div>
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Scheduled</span>
                                </div>
                                <div class="mt-2 text-sm">
                                    <span class="text-gray-600">Starts: Oct 15, 2023</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Tracking Card -->
                    <div class="bg-orange-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-orange-800">Progress Tracking</h3>
                        <div class="space-y-4">
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">John Smith</span>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">On Track</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Completed 5 of 7 required trainings
                                </div>
                            </div>
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium">Maria Garcia</span>
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Needs Attention</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Behind schedule on technical certification
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Development Plan Table -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Development Plan Overview</h3>
                    <div class="table-container">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="text-xs text-white uppercase table-header">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Employee</th>
                                    <th scope="col" class="px-6 py-4">Target Position</th>
                                    <th scope="col" class="px-6 py-4">Development Activities</th>
                                    <th scope="col" class="px-6 py-4">Timeline</th>
                                    <th scope="col" class="px-6 py-4">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">John Smith</td>
                                    <td class="px-6 py-4">Senior Crane Operator</td>
                                    <td class="px-6 py-4">Leadership Training, Certification</td>
                                    <td class="px-6 py-4">6 months</td>
                                    <td class="px-6 py-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-green-600 h-2.5 rounded-full" style="width: 75%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">Maria Garcia</td>
                                    <td class="px-6 py-4">Maintenance Supervisor</td>
                                    <td class="px-6 py-4">Technical Certification, Management Course</td>
                                    <td class="px-6 py-4">12 months</td>
                                    <td class="px-6 py-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-yellow-400 h-2.5 rounded-full" style="width: 30%"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">Robert Johnson</td>
                                    <td class="px-6 py-4">Safety Officer</td>
                                    <td class="px-6 py-4">Safety Certification, Compliance Training</td>
                                    <td class="px-6 py-4">3 months</td>
                                    <td class="px-6 py-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 90%"></div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contingency Content -->
        <div id="contingency" class="succession-content content-section" style="display: none;">
            <div class="bg-white p-6 rounded-lg shadow-sm dashboard-card">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Contingency Planning</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Risk Assessment Card -->
                    <div class="bg-red-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-red-800">Risk Assessment</h3>
                        <div class="space-y-4">
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">Key Position Vacancy</span>
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">High Risk</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">No ready successor for Senior Crane Operator position</p>
                            </div>
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">Skills Gap</span>
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Medium Risk</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">Limited candidates with advanced technical skills</p>
                            </div>
                        </div>
                    </div>

                    <!-- Mitigation Strategies Card -->
                    <div class="bg-teal-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-teal-800">Mitigation Strategies</h3>
                        <div class="space-y-4">
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <h4 class="font-medium text-teal-700">Cross-Training Program</h4>
                                <p class="text-sm text-gray-600 mt-1">Implement cross-training for multiple roles to increase flexibility</p>
                            </div>
                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                <h4 class="font-medium text-teal-700">External Recruitment Pipeline</h4>
                                <p class="text-sm text-gray-600 mt-1">Develop relationships with technical schools for potential hires</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contingency Plan Table -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Contingency Plan Overview</h3>
                    <div class="table-container">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="text-xs text-white uppercase table-header">
                                <tr>
                                    <th scope="col" class="px-6 py-4">Risk Scenario</th>
                                    <th scope="col" class="px-6 py-4">Impact Level</th>
                                    <th scope="col" class="px-6 py-4">Probability</th>
                                    <th scope="col" class="px-6 py-4">Contingency Plan</th>
                                    <th scope="col" class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">Sudden departure of key position holder</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-red-100 text-red-800">High</span>
                                    </td>
                                    <td class="px-6 py-4">Medium</td>
                                    <td class="px-6 py-4">Interim leadership assignment and accelerated development</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-green-100 text-green-800">In Place</span>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">Multiple simultaneous vacancies</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-red-100 text-red-800">Critical</span>
                                    </td>
                                    <td class="px-6 py-4">Low</td>
                                    <td class="px-6 py-4">External temporary staffing and internal mobility program</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-yellow-100 text-yellow-800">In Progress</span>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50 table-row">
                                    <td class="px-6 py-4 font-medium">Skills obsolescence</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-yellow-100 text-yellow-800">Medium</span>
                                    </td>
                                    <td class="px-6 py-4">High</td>
                                    <td class="px-6 py-4">Continuous learning program and skills assessment</td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-blue-100 text-blue-800">Planning</span>
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
<!-- content -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
    // Show Key Roles by default
    document.addEventListener('DOMContentLoaded', function() {
        showContent('key-roles');
        updatePhilippineTime();
        setInterval(updatePhilippineTime, 1000);
    });

    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        const arrow = document.getElementById(id.replace('submenu', 'arrow'));

        if (submenu.classList.contains('open')) {
            submenu.classList.remove('open');
            arrow.classList.remove('rotate-90');
            arrow.classList.add('rotate-0');
        } else {
            submenu.classList.add('open');
            arrow.classList.remove('rotate-0');
            arrow.classList.add('rotate-90');
        }
    }

    function showContent(contentId) {
        // Hide all content sections
        const contents = document.querySelectorAll('.succession-content');
        contents.forEach(content => {
            content.style.display = 'none';
        });

        // Show the selected content
        document.getElementById(contentId).style.display = 'block';

        // Update navigation pills
        const pills = document.querySelectorAll('.nav-pill');
        pills.forEach(pill => {
            pill.classList.remove('active');
        });

        // Find and activate the clicked pill
        event.target.classList.add('active');
    }

    function toggleNotification() {
        const notification = document.getElementById('notification-dropdown');
        notification.classList.toggle('open');
    }

    function handleNotificationResponse(response) {
        const notification = document.getElementById('department-notification');
        if (response === 'confirm') {
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">Request Confirmed</p>
                        <p class="mt-1 text-sm text-gray-500">You have confirmed the department notification</p>
                        <p class="mt-1 text-xs text-gray-400">Just now</p>
                    </div>
                </div>
            `;
        } else {
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">Request Rejected</p>
                        <p class="mt-1 text-sm text-gray-500">You have rejected the department notification</p>
                        <p class="mt-1 text-xs text-gray-400">Just now</p>
                    </div>
                </div>
            `;
        }
    }

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
        const formatter = new Intl.DateTimeFormat('en-US', options);
        const philippineTime = formatter.format(new Date());
        document.getElementById('philippineTime').textContent = philippineTime;
    }

    setInterval(updatePhilippineTime, 1000);
    updatePhilippineTime();
    function showLoading() {
        document.getElementById('loading-overlay').style.display = 'flex';
    }

    function hideLoading() {
        document.getElementById('loading-overlay').style.display = 'none';
    }
</script>

</body>
</html>
