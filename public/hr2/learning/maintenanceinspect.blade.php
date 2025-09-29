<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('./images/logo.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <title>Maintenance & Inspection Dashboard</title>
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
        .tab-button {
            transition: all 0.3s ease;
        }
        .tab-button.active {
            background-color: #3B82F6;
            color: white;
        }
        .equipment-card, .employee-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .equipment-card:hover, .employee-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }
        .progress-bar {
            height: 0.5rem;
            border-radius: 0.25rem;
            overflow: hidden;
            background-color: #E5E7EB;
        }
        .progress-fill {
            height: 100%;
            border-radius: 0.25rem;
            transition: width 0.5s ease;
        }

        /* Loading Screen Styles - Updated to match Safety Training */
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

        /* Professional border enhancements */
        .professional-border {
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            background-color: rgba(17, 24, 39, 0.7);
        }

        .dashboard-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            background-color: rgba(31, 41, 55, 0.8);
        }

        .summary-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            background-color: rgba(17, 24, 39, 0.7);
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        .critical-card {
            border-left-color: #EF4444;
        }

        .warning-card {
            border-left-color: #F59E0B;
        }

        .info-card {
            border-left-color: #3B82F6;
        }

        .success-card {
            border-left-color: #10B981;
        }

        /* Additional transparent elements */
        .transparent-dark {
            background-color: rgba(17, 24, 39, 0.8);
        }

        .transparent-darker {
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* New Professional Theme Styles */
        .monitoring-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .metric-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.8));
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gauge-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .gauge {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(#10B981 0% 70%, #F59E0B 70% 85%, #EF4444 85% 100%);
            mask: radial-gradient(white 55%, transparent 60%);
            -webkit-mask: radial-gradient(white 55%, transparent 60%);
        }

        .gauge-needle {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 4px;
            height: 45%;
            background: white;
            transform-origin: bottom center;
            transform: translateX(-50%) rotate(0deg);
            transition: transform 1s ease-in-out;
            border-radius: 4px 4px 0 0;
        }

        .gauge-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            background: #1F2937;
            border-radius: 50%;
            border: 2px solid white;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-online {
            background-color: #10B981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3);
        }

        .status-offline {
            background-color: #EF4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.3);
        }

        .status-maintenance {
            background-color: #F59E0B;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.3);
        }

        .maintenance-progress {
            height: 8px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .maintenance-progress-bar {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, #3B82F6, #10B981);
            transition: width 0.5s ease;
        }

        .maintenance-timeline {
            position: relative;
            padding-left: 1.5rem;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
            margin-left: 0.5rem;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -1.5rem;
            top: 0.25rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3B82F6;
        }

        .inspection-score {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(135deg, #3B82F6, #10B981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .data-table th {
            background: rgba(31, 41, 55, 0.7);
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #D1D5DB;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .data-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .data-table tr:hover td {
            background: rgba(55, 65, 81, 0.3);
        }

        .priority-high {
            background: rgba(239, 68, 68, 0.2);
            color: #FCA5A5;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .priority-medium {
            background: rgba(245, 158, 11, 0.2);
            color: #FCD34D;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }

        .priority-low {
            background: rgba(16, 185, 129, 0.2);
            color: #6EE7B7;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

   <!-- Loading Screen - Updated to match Safety Training -->
   <div id="loadingScreen" class="loading-screen">
        <div class="text-center">
            <img src="{{ asset('images/logo.png') }}" alt="CaliCrane Logo" class="loading-logo mx-auto mb-4">
            <p class="text-white text-xl font-semibold">Loading Maintenance & Inspection Module...</p>
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
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-xs text-white items-center justify-center" id="notification-count">3</span>
                        </span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="notification-container">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                        </div>
                        <div class="max-h-96 overflow-y-auto" id="notification-list">
                            <div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-0.5">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">Maintenance Alert</p>
                                        <p class="mt-1 text-sm text-gray-500">Crane #C-1023 requires immediate inspection.</p>
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
                                        <p class="text-sm font-medium text-gray-900">Inspection Completed</p>
                                        <p class="mt-1 text-sm text-gray-500">Forklift #F-4587 inspection has been completed successfully.</p>
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
                                        <p class="text-sm font-medium text-gray-900">Critical Issue</p>
                                        <p class="mt-1 text-sm text-gray-500">Truck #T-7891 has a critical issue that needs attention.</p>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox=" 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.8.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-4">Claims and Reimbursement</span>
                    </div>
                    <svg id="claims-arrow" class="w-4 h-4 rotate-0" fill="none" stroke currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                <li classinline-flex items-center>
                <a href="http://127.0.0.1:8000/dashboard" class="inline-flex items-center text-sm font-medium text-gray-900 hover:text-blue-600">
                    <svg class="w-3 h-3 mr-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l7 7-2 2a1 1 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                </a>
                </li>
                <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="../dashboard.blade.php" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Dashboard</a>
                </div>
                </li>
            </ol>
        </div>
        <!-- breadcrumb -->

        <!-- Main Content -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Maintenance and Inspection</h1>
            <p class="text-gray-600 mt-2">Monitor equipment status, schedule maintenance, and track inspection records</p>
        </div>

        <!-- Status Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="summary-card critical-card rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Critical Issues</h3>
                        <p class="text-2xl font-bold text-white">5</p>
                    </div>
                </div>
            </div>
            <div class="summary-card warning-card rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Pending Inspections</h3>
                        <p class="text-2xl font-bold text-white">12</p>
                    </div>
                </div>
            </div>
            <div class="summary-card info-card rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Scheduled Maintenance</h3>
                        <p class="text-2xl font-bold text-white">8</p>
                    </div>
                </div>
            </div>
            <div class="summary-card success-card rounded-lg p-4 shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Completed This Month</h3>
                        <p class="text-2xl font-bold text-white">24</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment Monitoring Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Equipment Status -->
            <div class="lg:col-span-2 dashboard-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-white">Equipment Status Overview</h2>
                    <button class="text-sm text-blue-400 hover:text-blue-300">View All</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full data-table">
                        <thead>
                            <tr>
                                <th>Equipment ID</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Last Inspection</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-medium text-white">C-1023</td>
                                <td class="text-gray-300">Crane</td>
                                <td><span class="priority-high">Critical</span></td>
                                <td class="text-gray-300">15 Oct 2023</td>
                                <td><button class="text-blue-400 hover:text-blue-300 text-sm">Details</button></td>
                            </tr>
                            <tr>
                                <td class="font-medium text-white">F-4587</td>
                                <td class="text-gray-300">Forklift</td>
                                <td><span class="priority-medium">Needs Attention</span></td>
                                <td class="text-gray-300">20 Oct 2023</td>
                                <td><button class="text-blue-400 hover:text-blue-300 text-sm">Details</button></td>
                            </tr>
                            <tr>
                                <td class="font-medium text-white">T-7891</td>
                                <td class="text-gray-300">Truck</td>
                                <td><span class="priority-high">Critical</span></td>
                                <td class="text-gray-300">18 Oct 2023</td>
                                <td><button class="text-blue-400 hover:text-blue-300 text-sm">Details</button></td>
                            </tr>
                            <tr>
                                <td class="font-medium text-white">E-3345</td>
                                <td class="text-gray-300">Excavator</td>
                                <td><span class="priority-low">Operational</span></td>
                                <td class="text-gray-300">22 Oct 2023</td>
                                <td><button class="text-blue-400 hover:text-blue-300 text-sm">Details</button></td>
                            </tr>
                            <tr>
                                <td class="font-medium text-white">L-5567</td>
                                <td class="text-gray-300">Loader</td>
                                <td><span class="priority-low">Operational</span></td>
                                <td class="text-gray-300">19 Oct 2023</td>
                                <td><button class="text-blue-400 hover:text-blue-300 text-sm">Details</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upcoming Maintenance -->
            <div class="dashboard-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-white">Upcoming Maintenance</h2>
                    <button class="text-sm text-blue-400 hover:text-blue-300">View All</button>
                </div>
                <div class="space-y-4">
                    <div class="p-4 transparent-darker rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-white">Crane #C-1023</h3>
                                <p class="text-sm text-gray-300">Preventive Maintenance</p>
                            </div>
                            <span class="priority-high">Tomorrow</span>
                        </div>
                        <div class="mt-2">
                            <div class="flex justify-between text-sm text-gray-400 mb-1">
                                <span>Progress</span>
                                <span>30%</span>
                            </div>
                            <div class="maintenance-progress">
                                <div class="maintenance-progress-bar" style="width: 30%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 transparent-darker rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-white">Forklift #F-4587</h3>
                                <p class="text-sm text-gray-300">Routine Inspection</p>
                            </div>
                            <span class="priority-medium">In 3 days</span>
                        </div>
                        <div class="mt-2">
                            <div class="flex justify-between text-sm text-gray-400 mb-1">
                                <span>Progress</span>
                                <span>65%</span>
                            </div>
                            <div class="maintenance-progress">
                                <div class="maintenance-progress-bar" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 transparent-darker rounded-lg">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-white">Truck #T-7891</h3>
                                <p class="text-sm text-gray-300">Oil Change</p>
                            </div>
                            <span class="priority-low">In 1 week</span>
                        </div>
                        <div class="mt-2">
                            <div class="flex justify-between text-sm text-gray-400 mb-1">
                                <span>Progress</span>
                                <span>15%</span>
                            </div>
                            <div class="maintenance-progress">
                                <div class="maintenance-progress-bar" style="width: 15%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inspection Reports & Maintenance History -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Inspection Reports -->
            <div class="dashboard-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-white">Recent Inspection Reports</h2>
                    <button class="text-sm text-blue-400 hover:text-blue-300">View All</button>
                </div>
                <div class="space-y-4">
                    <div class="p-4 transparent-darker rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-white">Crane #C-1023</h3>
                            <span class="text-sm text-red-400">Failed</span>
                        </div>
                        <p class="text-sm text-gray-300 mb-2">Structural issues detected in boom assembly</p>
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>Inspector: John Smith</span>
                            <span>22 Oct 2023</span>
                        </div>
                    </div>
                    <div class="p-4 transparent-darker rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-white">Forklift #F-4587</h3>
                            <span class="text-sm text-green-400">Passed</span>
                        </div>
                        <p class="text-sm text-gray-300 mb-2">All systems operational, minor wear on tires</p>
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>Inspector: Maria Garcia</span>
                            <span>21 Oct 2023</span>
                        </div>
                    </div>
                    <div class="p-4 transparent-darker rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-white">Truck #T-7891</h3>
                            <span class="text-sm text-red-400">Failed</span>
                        </div>
                        <p class="text-sm text-gray-300 mb-2">Brake system requires immediate attention</p>
                        <div class="flex justify-between text-xs text-gray-400">
                            <span>Inspector: Robert Johnson</span>
                            <span>20 Oct 2023</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance History -->
            <div class="dashboard-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-white">Maintenance History</h2>
                    <button class="text-sm text-blue-400 hover:text-blue-300">View All</button>
                </div>
                <div class="maintenance-timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot bg-blue-500"></div>
                        <div class="p-4 transparent-darker rounded-lg">
                            <h3 class="font-semibold text-white">Crane #C-1023 - Hydraulic Repair</h3>
                            <p class="text-sm text-gray-300 mb-2">Fixed hydraulic leak in main cylinder</p>
                            <div class="flex justify-between text-xs text-gray-400">
                                <span>Technician: Mike Wilson</span>
                                <span>18 Oct 2023</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot bg-green-500"></div>
                        <div class="p-4 transparent-darker rounded-lg">
                            <h3 class="font-semibold text-white">Forklift #F-4587 - Regular Service</h3>
                            <p class="text-sm text-gray-300 mb-2">Completed 500-hour service</p>
                            <div class="flex justify-between text-xs text-gray-400">
                                <span>Technician: Sarah Lee</span>
                                <span>15 Oct 2023</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot bg-yellow-500"></div>
                        <div class="p-4 transparent-darker rounded-lg">
                            <h3 class="font-semibold text-white">Truck #T-7891 - Electrical Repair</h3>
                            <p class="text-sm text-gray-300 mb-2">Replaced alternator and battery</p>
                            <div class="flex justify-between text-xs text-gray-400">
                                <span>Technician: James Brown</span>
                                <span>12 Oct 2023</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card p-6 mb-8">
            <h2 class="text-xl font-bold text-white mb-6">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button class="flex flex-col items-center justify-center p-4 transparent-darker rounded-lg hover:bg-gray-700 transition">
                    <div class="p-3 rounded-full bg-blue-500 mb-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">New Report</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 transparent-darker rounded-lg hover:bg-gray-700 transition">
                    <div class="p-3 rounded-full bg-green-500 mb-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">Schedule</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 transparent-darker rounded-lg hover:bg-gray-700 transition">
                    <div class="p-3 rounded-full bg-yellow-500 mb-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">Issue Alert</span>
                </button>
                <button class="flex flex-col items-center justify-center p-4 transparent-darker rounded-lg hover:bg-gray-700 transition">
                    <div class="p-3 rounded-full bg-red-500 mb-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-white">Delete Record</span>
                </button>
            </div>
        </div>

    </div>
</div>
<!-- content -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script>
    // Loading screen functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Show loading screen initially
        const loadingScreen = document.getElementById('loadingScreen');

        // Simulate loading process (replace with actual loading logic)
        setTimeout(() => {
            loadingScreen.style.opacity = '0';
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        }, 1500); // Adjust time as needed for actual content loading

        // Initialize other functionality
        initializePage();
    });

    // Initialize page functionality
    function initializePage() {
        // Update Philippine Time
        updatePhilippineTime();
        setInterval(updatePhilippineTime, 60000);

        // Initialize tooltips and other UI elements
        initTooltips();

        // Set up button functionality
        setupButtonFunctionality();
    }

    // Update Philippine Time
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
        const phTime = new Date().toLocaleString('en-US', options);
        document.getElementById('philippineTime').textContent = phTime;
    }

    // Toggle submenu function
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        const arrowId = id.replace('-submenu', '-arrow');
        const arrow = document.getElementById(arrowId);

        submenu.classList.toggle('open');
        arrow.classList.toggle('rotate-90');
        arrow.classList.toggle('rotate-0');
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

    // Initialize tooltips
    function initTooltips() {
        // This would initialize any tooltips if using a library like Flowbite
        // For custom tooltips, you would add the logic here
    }

    // Set up button functionality
    function setupButtonFunctionality() {
        // Equipment table details buttons
        const detailButtons = document.querySelectorAll('.data-table button');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const equipmentId = this.closest('tr').querySelector('td:first-child').textContent;
                alert(`Showing details for ${equipmentId}`);
                // In a real application, this would open a modal or navigate to a detail page
            });
        });

        // Quick action buttons
        const quickActionButtons = document.querySelectorAll('.grid.grid-cols-2 button');
        quickActionButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                const actions = [
                    'Create New Inspection Report',
                    'Schedule Maintenance',
                    'Issue Equipment Alert',
                    'Delete Selected Record'
                ];
                alert(`${actions[index]} action triggered`);
                // In a real application, this would open the appropriate form/modal
            });
        });

        // View All buttons
        const viewAllButtons = document.querySelectorAll('button:not(.grid button)');
        viewAllButtons.forEach(button => {
            if (button.textContent === 'View All') {
                button.addEventListener('click', function() {
                    const section = this.closest('.dashboard-card').querySelector('h2').textContent;
                    alert(`Navigating to all ${section}`);
                    // In a real application, this would navigate to the full list view
                });
            }
        });
    }

    // Handle equipment status gauge updates
    function updateGauge(needle, value) {
        // Value between 0-100, with 0 = -90deg, 100 = 90deg
        const rotation = -90 + (value * 180 / 100);
        needle.style.transform = `translateX(-50%) rotate(${rotation}deg)`;
    }

    // Initialize gauges if any exist on the page
    function initGauges() {
        const gauges = document.querySelectorAll('.gauge-needle');
        gauges.forEach((gauge, index) => {
            // Set random values for demonstration
            const randomValue = Math.floor(Math.random() * 100);
            updateGauge(gauge, randomValue);

            // Update the value display
            const valueDisplay = gauge.closest('.gauge-container').nextElementSibling;
            if (valueDisplay && valueDisplay.classList.contains('inspection-score')) {
                valueDisplay.textContent = `${randomValue}%`;
            }
        });
    }

    // Initialize the page when DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        initializePage();
        initGauges();
    });
</script>
</body>
</html>
