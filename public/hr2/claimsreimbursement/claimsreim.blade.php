<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Claim Form | CaliCrane</title>
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
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .receipt-style {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            background-color: #f9fafb;
        }
        .divider {
            height: 1px;
            background-color: #d1d5db;
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

            <!-- Claim Form -->
            <li>
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-900 transition cursor-pointer" onclick="toggleSubmenu('claim-form-submenu')">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="ml-4">Claim Form</span>
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
                    <a href="#" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Claim Form</a>
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
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <!-- Submit Claim Form -->
            <div id="submit-claim-section">
                <!-- Form Steps -->
                <div class="form-step active" id="step1">
                    <div class="receipt-style">
                        <h2 class="text-xl font-bold text-center mb-4">Claim Form</h2>
                        <div class="divider"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Employee Name</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter your name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter your ID">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter your address">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contact No</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter your contact number">
                            </div>
                        </div>

                        <div class="divider"></div>

                        <h3 class="text-lg font-semibold mb-3">Expense Details</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description of Expense</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Requested</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md" placeholder="Department">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md" placeholder="Description">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" class="w-full px-3 py-1 border border-gray-300 rounded-md" placeholder="Amount">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md" placeholder="Department">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" class="w-full px-3 py-1 border border-gray-300 rounded-md" placeholder="Description">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" class="w-full px-3 py-1 border border-gray-300 rounded-md" placeholder="Amount">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition" onclick="nextStep(2)">Next</button>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step2">
                    <div class="receipt-style">
                        <h2 class="text-xl font-bold text-center mb-4">Claim Category</h2>
                        <div class="divider"></div>

                        <div class="space-y-4 mb-6">
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="selectCategory('Travel Costs')">
                                <input type="radio" name="claimCategory" id="travel" value="Travel Costs" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <label for="travel" class="ml-3 block text-sm font-medium text-gray-700">Travel Costs</label>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="selectCategory('Entertainment and Meals')">
                                <input type="radio" name="claimCategory" id="entertainment" value="Entertainment and Meals" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <label for="entertainment" class="ml-3 block text-sm font-medium text-gray-700">Entertainment and Meals</label>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="selectCategory('Professional Development')">
                                <input type="radio" name="claimCategory" id="professional" value="Professional Development" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <label for="professional" class="ml-3 block text-sm font-medium text-gray-700">Professional Development (Training)</label>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="selectCategory('Office Supplies')">
                                <input type="radio" name="claimCategory" id="office" value="Office Supplies" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <label for="office" class="ml-3 block text-sm font-medium text-gray-700">Office Supplies and Equipment</label>
                            </div>

                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer" onclick="selectCategory('Miscellaneous')">
                                <input type="radio" name="claimCategory" id="misc" value="Miscellaneous" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <label for="misc" class="ml-3 block text-sm font-medium text-gray-700">Miscellaneous Expenses</label>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="flex justify-between mt-6">
                            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-6 rounded-md transition" onclick="prevStep(1)">Previous</button>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition" onclick="nextStep(3)">Next</button>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step3">
                    <div class="receipt-style">
                        <h2 class="text-xl font-bold text-center mb-4">Upload Supporting Documents</h2>
                        <div class="divider"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-4">Upload Documents</h3>

                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Official Receipt *</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="official-receipt" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500">PDF, PNG, JPG (MAX. 5MB)</p>
                                            </div>
                                            <input id="official-receipt" type="file" class="hidden" required />
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Document (Optional)</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="additional-doc" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-500">PDF, PNG, JPG (MAX. 5MB)</p>
                                            </div>
                                            <input id="additional-doc" type="file" class="hidden" />
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-4">Employee Confirmation</h3>
                                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                    <p class="text-sm text-gray-700 mb-4">I hereby confirm that all the information provided in this claim form is true and accurate to the best of my knowledge. I understand that providing false information may result in disciplinary action.</p>

                                    <div class="flex items-start mb-4">
                                        <div class="flex items-center h-5">
                                            <input id="confirmation" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" required>
                                        </div>
                                        <label for="confirmation" class="ms-2 text-sm font-medium text-gray-900">I confirm the above statement</label>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="terms" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2" required>
                                        </div>
                                        <label for="terms" class="ms-2 text-sm font-medium text-gray-900">I agree to the terms and conditions</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="flex justify-between mt-6">
                            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-6 rounded-md transition" onclick="prevStep(2)">Previous</button>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition" onclick="submitClaim()">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View Status Section -->
            <div id="view-status-section" class="hidden">
                <h2 class="text-2xl font-bold mb-6">Claim Status</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Submitted</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CLM-001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Oct 15, 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Travel Costs</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱2,500.00</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">View Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CLM-002</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Oct 10, 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Office Supplies</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱1,200.00</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">View Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">CLM-003</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Oct 5, 2023</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Professional Development</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">₱5,000.00</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">View Details</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- content -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
<script>
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
    }

    function showViewStatus() {
        document.getElementById('submit-claim-section').classList.add('hidden');
        document.getElementById('view-status-section').classList.remove('hidden');
        document.getElementById('current-page').textContent = 'View Status';
    }

    function nextStep(stepNumber) {
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        document.getElementById('step' + stepNumber).classList.add('active');
    }

    function prevStep(stepNumber) {
        document.querySelectorAll('.form-step').forEach(step => step.classList.remove('active'));
        document.getElementById('step' + stepNumber).classList.add('active');
    }

    function selectCategory(category) {
        document.querySelectorAll('input[name="claimCategory"]').forEach(input => {
            if (input.value === category) {
                input.checked = true;
            }
        });
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
            });

            // Restore button
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }, 1500);
    }

    // Initialize the form
    document.addEventListener('DOMContentLoaded', function() {
        showSubmitClaim();
    });
</script>
</body>
</html>
