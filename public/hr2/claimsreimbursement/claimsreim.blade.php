<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Claims & Reimbursement</title>
    <style>
        /* Previous styles remain the same */
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
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .receipt-style {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 2rem;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #d1d5db, transparent);
            margin: 1.5rem 0;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }
        .step:not(:last-child):after {
            content: '';
            position: absolute;
            top: 1rem;
            left: 50%;
            width: 100%;
            height: 2px;
            background-color: #e5e7eb;
            z-index: 1;
        }
        .step.active:not(:last-child):after {
            background-color: #3b82f6;
        }
        .step-circle {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
            border: 2px solid #d1d5db;
            color: #6b7280;
            font-weight: 600;
            z-index: 2;
            transition: all 0.3s ease;
        }
        .step.active .step-circle {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        .step.completed .step-circle {
            background-color: #10b981;
            border-color: #10b981;
            color: white;
        }
        .step-label {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            text-align: center;
        }
        .step.active .step-label {
            color: #3b82f6;
        }
        .step.completed .step-label {
            color: #10b981;
        }
        .category-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            background-color: #ffffff;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .category-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1), 0 2px 4px -1px rgba(59, 130, 246, 0.06);
        }
        .category-card.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 0.75rem;
            padding: 2rem;
            background-color: #f9fafb;
            transition: all 0.3s ease;
            text-align: center;
        }
        .upload-area:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .upload-area.dragover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .form-header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 0.75rem 0.75rem 0 0;
            margin: -2rem -2rem 2rem -2rem;
        }

        /* New styles for enhanced UI */
        .petty-cash-info {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .petty-cash-alert {
            background-color: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        .amount-limit {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        .supervisor-approval {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        .reimbursement-type-tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1.5rem;
        }
        .reimbursement-tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .reimbursement-tab.active {
            border-bottom-color: #3b82f6;
            color: #3b82f6;
        }
        .reimbursement-content {
            display: none;
        }
        .reimbursement-content.active {
            display: block;
        }
        .expense-table-row {
            transition: all 0.3s ease;
        }
        .expense-table-row:hover {
            background-color: #f9fafb;
        }

        /* Auto-logout modal styles */
        #autoLogoutModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        #autoLogoutModal.active {
            display: flex;
        }
        .logout-modal-content {
            background: white;
            padding: 2rem;
            border-radius: 0.75rem;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .countdown-timer {
            font-size: 2rem;
            font-weight: bold;
            color: #ef4444;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-gray-100">

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
                                <form action="http://127.0.0.1:8000/logout" method="POST">
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

<!-- Auto Logout Modal -->
<div id="autoLogoutModal">
    <div class="logout-modal-content">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Session Timeout</h2>
        <p class="text-gray-600 mb-4">Your session is about to expire due to inactivity.</p>
        <div class="countdown-timer" id="countdownTimer">02:00</div>
        <p class="text-gray-600 mb-6">You will be automatically logged out in <span id="countdownSeconds">120</span> seconds.</p>
        <div class="flex justify-center space-x-4">
            <button id="stayLoggedIn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                Stay Logged In
            </button>
            <button id="logoutNow" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-6 rounded-lg transition">
                Logout Now
            </button>
        </div>
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
                        <a href="../succession/successions.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition">Roles Development & Contingency</a>
                    </li>
                </ul>
            </li>

            <!-- Claim Form -->
            <li>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-900 transition cursor-pointer" onclick="toggleSubmenu('claim-form-submenu')">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-4">Claims and Reimbursement</span>
                    </div>
                    <svg id="claim-form-arrow" class="w-4 h-4 rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
                <ul id="claim-form-submenu" class="submenu pl-11">
                    <li class="my-2">
                        <a href="../claimsreimbursement/claimsreim.blade.php" class="block p-2 rounded-lg hover:bg-blue-900 transition" onclick="showSubmitClaim()">Claims Form</a>
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
                    <a href="#" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Claims & Reimbursement</a>
                </div>
                </li>
                <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2" id="current-page">Submit Claim</span>
                </div>
                </li>
            </ol>
        </div>
        <!-- breadcrumb -->

        <!-- Main Content Area for Claim Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <!-- Submit Claim Form -->
            <div id="submit-claim-section">
                <!-- Step Indicator -->
                <div class="step-indicator pt-6 px-6">
                    <div class="step active" id="step1-indicator">
                        <div class="step-circle">1</div>
                        <div class="step-label">Personal Details</div>
                    </div>
                    <div class="step" id="step2-indicator">
                        <div class="step-circle">2</div>
                        <div class="step-label">Claim Category</div>
                    </div>
                    <div class="step" id="step3-indicator">
                        <div class="step-circle">3</div>
                        <div class="step-label">Upload Documents</div>
                    </div>
                </div>

                <!-- Form Steps -->
                <div class="form-step active" id="step1">
                    <div class="receipt-style">
                        <div class="form-header">
                            <h2 class="text-2xl font-bold text-center">Claims & Reimbursement</h2>
                            <p class="text-center text-blue-100 mt-2">Complete the form below to submit your reimbursement request</p>
                        </div>

                        <!-- Reimbursement Type Tabs -->
                        <div class="reimbursement-type-tabs">
                            <div class="reimbursement-tab active" onclick="switchReimbursementType('regular')">Regular Reimbursement</div>
                            <div class="reimbursement-tab" onclick="switchReimbursementType('petty-cash')">Cash Fund</div>
                        </div>

                        <!-- Regular Reimbursement Content -->
                        <div id="regular-reimbursement" class="reimbursement-content active">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Employee Name</label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter your full name">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Employee ID</label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter your employee ID">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter your complete address">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contact No</label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter your contact number">
                                </div>
                            </div>

                            <div class="divider"></div>

                            <h3 class="text-xl font-bold mb-4 text-gray-800">Expense Details</h3>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Department</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description of Expense</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount Requested</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date Incurred</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="expense-table-body">
                                        <tr class="expense-table-row">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                                    <option value="">Select Department</option>
                                                    <option value="hr">Human Resources 1</option>
                                                    <option value="hr">Human Resources 2</option>
                                                    <option value="hr">Human Resources 3</option>
                                                    <option value="hr">Human Resources 4</option>
                                                    <option value="core">Core 1</option>
                                                    <option value="core">Core 2</option>
                                                    <option value="core">Core 3</option>
                                                    <option value="core">Core 4</option>
                                                    <option value="admin">Administrator</option>
                                                    <option value="finance">Financial</option>
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Description">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Amount">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button type="button" class="text-red-600 hover:text-red-900" onclick="removeExpenseRow(this)">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition" onclick="addExpenseRow()">
                                    + Add Another Expense
                                </button>
                            </div>
                        </div>

                        <!-- Petty Cash Content -->
                        <div id="petty-cash-reimbursement" class="reimbursement-content">
                            <div class="petty-cash-info">
                                <h3 class="text-lg font-semibold text-blue-800 mb-2">Petty Cash Information</h3>
                                <p class="text-blue-700">Petty cash is available for small, emergency expenses up to <span class="amount-limit">₱1,800</span> per department per month. Requires supervisor approval.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Employee Name</label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter your full name">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Employee ID</label>
                                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter your employee ID">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="">Select Department</option>
                                        <option value="hr">Human Resources</option>
                                        <option value="finance">Finance</option>
                                        <option value="operations">Operations</option>
                                        <option value="it">IT</option>
                                        <option value="marketing">Marketing</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Petty Cash Amount</label>
                                    <input type="number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter amount" max="2000">
                                    <p class="text-xs text-gray-500 mt-1">Maximum: ₱2,000</p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Petty Cash Request</label>
                                <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" rows="3" placeholder="Please provide a detailed explanation for this petty cash request"></textarea>
                            </div>

                            <div class="petty-cash-alert">
                                <h4 class="font-semibold text-amber-800 mb-1">Important Notice</h4>
                                <p class="text-amber-700 text-sm">Petty cash requests require immediate supervisor approval and are intended for emergency expenses only. All petty cash disbursements are reconciled monthly.</p>
                            </div>

                            <div class="supervisor-approval">
                                <h4 class="font-semibold text-gray-800 mb-3">Supervisor Approval</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Supervisor Name</label>
                                        <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter supervisor name">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Supervisor Email</label>
                                        <input type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter supervisor email">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:-translate-y-1 shadow-md" onclick="nextStep(2)">Next</button>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step2">
                    <div class="receipt-style">
                        <div class="form-header">
                            <h2 class="text-2xl font-bold text-center">Claim Category</h2>
                            <p class="text-center text-blue-100 mt-2">Select the appropriate category for your reimbursement request</p>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div class="category-card" onclick="selectCategory('Travel Costs')">
                                <div class="flex items-center">
                                    <input type="radio" name="claimCategory" id="travel" value="Travel Costs" class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                    <label for="travel" class="ml-4 block text-lg font-semibold text-gray-800">Travel Costs</label>
                                </div>
                                <p class="mt-2 text-gray-600 ml-9">Transportation, accommodation, and other travel-related expenses</p>
                            </div>

                            <div class="category-card" onclick="selectCategory('Entertainment and Meals')">
                                <div class="flex items-center">
                                    <input type="radio" name="claimCategory" id="entertainment" value="Entertainment and Meals" class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                    <label for="entertainment" class="ml-4 block text-lg font-semibold text-gray-800">Entertainment and Meals</label>
                                </div>
                                <p class="mt-2 text-gray-600 ml-9">Client meetings, business meals, and entertainment expenses</p>
                            </div>

                            <div class="category-card" onclick="selectCategory('Professional Development')">
                                <div class="flex items-center">
                                    <input type="radio" name="claimCategory" id="professional" value="Professional Development" class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                    <label for="professional" class="ml-4 block text-lg font-semibold text-gray-800">Professional Development (Training)</label>
                                </div>
                                <p class="mt-2 text-gray-600 ml-9">Courses, certifications, and skill development programs</p>
                            </div>

                            <div class="category-card" onclick="selectCategory('Office Supplies')">
                                <div class="flex items-center">
                                    <input type="radio" name="claimCategory" id="office" value="Office Supplies" class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                    <label for="office" class="ml-4 block text-lg font-semibold text-gray-800">Office Supplies and Equipment</label>
                                </div>
                                <p class="mt-2 text-gray-600 ml-9">Stationery, tools, and other office-related purchases</p>
                            </div>

                            <div class="category-card" onclick="selectCategory('Miscellaneous')">
                                <div class="flex items-center">
                                    <input type="radio" name="claimCategory" id="misc" value="Miscellaneous" class="h-5 w-5 text-blue-600 focus:ring-blue-500">
                                    <label for="misc" class="ml-4 block text-lg font-semibold text-gray-800">Miscellaneous Expenses</label>
                                </div>
                                <p class="mt-2 text-gray-600 ml-9">Other business-related expenses not covered by other categories</p>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="flex justify-between mt-8">
                            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-8 rounded-lg transition duration-300 shadow-md" onclick="prevStep(1)">Previous</button>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:-translate-y-1 shadow-md" onclick="nextStep(3)">Next</button>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step3">
                    <div class="receipt-style">
                        <div class="form-header">
                            <h2 class="text-2xl font-bold text-center">Upload Supporting Documents</h2>
                            <p class="text-center text-blue-100 mt-2">Provide necessary documentation for your reimbursement request</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                            <div>
                                <h3 class="text-xl font-bold mb-4 text-gray-800">Upload Documents</h3>

                                <div class="mb-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Official Receipt *</label>
                                    <div class="upload-area" id="official-receipt-area">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500">PDF, PNG, JPG (MAX. 5MB)</p>
                                        </div>
                                        <input id="official-receipt" type="file" class="hidden" required />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Additional Document (Optional)</label>
                                    <div class="upload-area" id="additional-doc-area">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-10 h-10 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500">PDF, PNG, JPG (MAX. 5MB)</p>
                                        </div>
                                        <input id="additional-doc" type="file" class="hidden" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-xl font-bold mb-4 text-gray-800">Employee Confirmation</h3>
                                <div class="p-6 border border-gray-200 rounded-xl bg-gray-50">
                                    <p class="text-sm text-gray-700 mb-6">I hereby confirm that all the information provided in this claims & reimbursement is true and accurate to the best of my knowledge. I understand that providing false information may result in disciplinary action.</p>

                                    <div class="flex items-start mb-5">
                                        <div class="flex items-center h-5">
                                            <input id="confirmation" type="checkbox" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" required>
                                        </div>
                                        <label for="confirmation" class="ms-3 text-sm font-medium text-gray-900">I confirm the above statement</label>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="terms" type="checkbox" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" required>
                                        </div>
                                        <label for="terms" class="ms-3 text-sm font-medium text-gray-900">I agree to the terms and conditions</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="flex justify-between mt-8">
                            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-3 px-8 rounded-lg transition duration-300 shadow-md" onclick="prevStep(2)">Previous</button>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:-translate-y-1 shadow-md" onclick="submitClaim()">Submit Claim</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Status Section -->
            <div id="view-status-section" class="hidden">
                <div class="p-8">
                    <h2 class="text-3xl font-bold mb-2 text-gray-800">Claim Status</h2>
                    <p class="text-gray-600 mb-8">Track the progress of your reimbursement requests</p>

                    <div class="overflow-x-auto rounded-xl shadow">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Claim ID</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date Submitted</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">CLM-001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Oct 15, 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Travel Costs</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">₱2,500.00</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge status-pending">Pending</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900 font-semibold">View Details</a>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">CLM-002</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Oct 10, 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Office Supplies</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">₱1,200.00</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge status-approved">Approved</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900 font-semibold">View Details</a>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">CLM-003</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Oct 5, 2023</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Professional Development</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">₱5,000.00</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge status-rejected">Rejected</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900 font-semibold">View Details</a>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
<script>
    // Auto Logout Functionality
    let logoutTimer;
    let countdownTimer;
    const logoutTime = 2 * 60 * 1000; // 2 minutes in milliseconds
    let timeLeft = 120; // 2 minutes in seconds

    function startLogoutTimer() {
        // Clear existing timers
        clearTimeout(logoutTimer);
        clearInterval(countdownTimer);

        // Reset time left
        timeLeft = 120;

        // Set the main logout timer
        logoutTimer = setTimeout(showLogoutWarning, logoutTime);

        // Update countdown every second
        countdownTimer = setInterval(updateCountdown, 1000);
    }

    function resetLogoutTimer() {
        startLogoutTimer();
    }

    function showLogoutWarning() {
    const modal = document.getElementById('autoLogoutModal');
    modal.classList.add('active');

    // Clear any existing final timer
    if (window.finalLogoutTimer) {
        clearInterval(window.finalLogoutTimer);
    }

    // Start final countdown (30 seconds)
    timeLeft = 30;
    updateCountdownDisplay(); // Display immediately

    // Final logout timer - update first, then start interval
    window.finalLogoutTimer = setInterval(() => {
        timeLeft--;
        updateCountdownDisplay();

        if (timeLeft <= 0) {
            clearInterval(window.finalLogoutTimer);
            performLogout();
        }
    }, 1000);
}

    function updateCountdown() {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(countdownTimer);
        }
    }

    function updateCountdownDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        const countdownTimer = document.getElementById('countdownTimer');
        const countdownSeconds = document.getElementById('countdownSeconds');

        countdownTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        countdownSeconds.textContent = timeLeft;
    }

    function performLogout() {
    // Find and submit the logout form
    const logoutForm = document.querySelector('form[action="{{ route(\'logout\') }}"]');
    if (logoutForm) {
        logoutForm.submit();
    } else {
        // Fallback: redirect to login URL
        window.location.href = "http://127.0.0.1:8000/login";
    }
}

    function stayLoggedIn() {
        const modal = document.getElementById('autoLogoutModal');
        modal.classList.remove('active');
        clearInterval(window.finalLogoutTimer);
        resetLogoutTimer();
    }

    // Event listeners for user activity
    function setupActivityListeners() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'];

        events.forEach(event => {
            document.addEventListener(event, resetLogoutTimer, { passive: true });
        });

        // Add event listeners for modal buttons
        document.getElementById('stayLoggedIn').addEventListener('click', stayLoggedIn);
        document.getElementById('logoutNow').addEventListener('click', performLogout);
    }

    // Toggle submenu function
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        const arrow = document.getElementById(id.replace('submenu', 'arrow'));

        submenu.classList.toggle('open');
        arrow.classList.toggle('rotate-0');
        arrow.classList.toggle('rotate-90');
    }

    // Notification dropdown toggle
    function toggleNotification() {
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('open');
    }

    // Close notification dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notification-dropdown');
        const notificationButton = event.target.closest('button[onclick="toggleNotification()"]');

        if (!notificationButton && !dropdown.contains(event.target)) {
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
        const formatter = new Intl.DateTimeFormat('en-US', options);
        const philippineTime = formatter.format(new Date());
        document.getElementById('philippineTime').textContent = philippineTime;
    }

    setInterval(updatePhilippineTime, 1000);
    updatePhilippineTime();

    // Claim Form Functions
    function showSubmitClaim() {
        document.getElementById('submit-claim-section').classList.remove('hidden');
        document.getElementById('view-status-section').classList.add('hidden');
        document.getElementById('current-page').textContent = 'Submit Claim';
        // Reset form to first step
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        document.getElementById('step1').classList.add('active');
        updateStepIndicator(1);
    }

    function showViewStatus() {
        document.getElementById('submit-claim-section').classList.add('hidden');
        document.getElementById('view-status-section').classList.remove('hidden');
        document.getElementById('current-page').textContent = 'View Status';
    }

    function updateStepIndicator(stepNumber) {
        // Reset all steps
        document.querySelectorAll('.step').forEach(step => {
            step.classList.remove('active', 'completed');
        });

        // Mark previous steps as completed
        for (let i = 1; i < stepNumber; i++) {
            document.getElementById(`step${i}-indicator`).classList.add('completed');
        }

        // Mark current step as active
        document.getElementById(`step${stepNumber}-indicator`).classList.add('active');
    }

    function nextStep(stepNumber) {
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        document.getElementById('step' + stepNumber).classList.add('active');
        updateStepIndicator(stepNumber);
    }

    function prevStep(stepNumber) {
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        document.getElementById('step' + stepNumber).classList.add('active');
        updateStepIndicator(stepNumber);
    }

    function selectCategory(category) {
        document.querySelectorAll('input[name="claimCategory"]').forEach(input => {
            if (input.value === category) {
                input.checked = true;
                // Add selected class to parent card
                input.closest('.category-card').classList.add('selected');
            } else {
                // Remove selected class from other cards
                input.closest('.category-card').classList.remove('selected');
            }
        });
    }

    // File upload area interactions
    document.addEventListener('DOMContentLoaded', function() {
        const uploadAreas = document.querySelectorAll('.upload-area');

        uploadAreas.forEach(area => {
            const input = area.querySelector('input[type="file"]');

            // Click to upload
            area.addEventListener('click', () => {
                input.click();
            });

            // Drag and drop
            area.addEventListener('dragover', (e) => {
                e.preventDefault();
                area.classList.add('dragover');
            });

            area.addEventListener('dragleave', () => {
                area.classList.remove('dragover');
            });

            area.addEventListener('drop', (e) => {
                e.preventDefault();
                area.classList.remove('dragover');

                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    // Update UI to show file name
                    updateFileDisplay(area, e.dataTransfer.files[0].name);
                }
            });

            // Handle file selection via input
            input.addEventListener('change', () => {
                if (input.files.length) {
                    updateFileDisplay(area, input.files[0].name);
                }
            });
        });
    });

    function updateFileDisplay(area, fileName) {
        const existingFileName = area.querySelector('.file-name');
        if (existingFileName) {
            existingFileName.remove();
        }

        const fileNameElement = document.createElement('p');
        fileNameElement.className = 'file-name text-sm text-green-600 font-medium mt-2';
        fileNameElement.textContent = `Selected: ${fileName}`;
        area.appendChild(fileNameElement);
    }

    function submitClaim() {
        // Validate form
        const officialReceipt = document.getElementById('official-receipt');
        const confirmation = document.getElementById('confirmation');
        const terms = document.getElementById('terms');

        if (!officialReceipt.files.length) {
            alert('Please upload an official receipt.');
            return;
        }

        if (!confirmation.checked) {
            alert('Please confirm the statement.');
            return;
        }

        if (!terms.checked) {
            alert('Please agree to the terms and conditions.');
            return;
        }

        // Show loading state
        const submitButton = document.querySelector('button[onclick="submitClaim()"]');
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Submitting...';
        submitButton.disabled = true;

        // Simulate API call
        setTimeout(() => {
            alert('Claim Submitted Successfully');
            // Reset form
            document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
            document.getElementById('step1').classList.add('active');
            updateStepIndicator(1);
            document.querySelectorAll('input').forEach(input => {
                if (input.type !== 'button' && input.type !== 'submit') {
                    input.value = '';
                }
            });
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.checked = false;
                radio.closest('.category-card').classList.remove('selected');
            });

            // Remove file name displays
            document.querySelectorAll('.file-name').forEach(el => el.remove());

            // Restore button
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }, 1500);
    }

    // New functions for enhanced UI
    function switchReimbursementType(type) {
        // Update tabs
        document.querySelectorAll('.reimbursement-tab').forEach(tab => {
            tab.classList.remove('active');
        });

        if (type === 'regular') {
            document.querySelector('.reimbursement-tab:nth-child(1)').classList.add('active');
        } else {
            document.querySelector('.reimbursement-tab:nth-child(2)').classList.add('active');
        }

        // Update content
        document.querySelectorAll('.reimbursement-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(`${type}-reimbursement`).classList.add('active');
    }

    function addExpenseRow() {
        const tableBody = document.getElementById('expense-table-body');
        const newRow = document.createElement('tr');
        newRow.className = 'expense-table-row';
        newRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Select Department</option>
                    <option value="hr">Human Resources</option>
                    <option value="finance">Finance</option>
                    <option value="operations">Operations</option>
                    <option value="it">IT</option>
                    <option value="marketing">Marketing</option>
                </select>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Description">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Amount">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <button type="button" class="text-red-600 hover:text-red-900" onclick="removeExpenseRow(this)">
                    Remove
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
    }

    function removeExpenseRow(button) {
        const row = button.closest('tr');
        if (document.querySelectorAll('.expense-table-row').length > 1) {
            row.remove();
        } else {
            alert('You need at least one expense entry.');
        }
    }

    // Initialize the form and auto-logout
    document.addEventListener('DOMContentLoaded', function() {
        showSubmitClaim();

        // Add click handlers for upload areas
        document.getElementById('official-receipt-area').addEventListener('click', function() {
            document.getElementById('official-receipt').click();
        });

        document.getElementById('additional-doc-area').addEventListener('click', function() {
            document.getElementById('additional-doc').click();
        });

        // Initialize auto-logout functionality
        setupActivityListeners();
        startLogoutTimer();
    });
</script>
</body>
</html>
