<!-- Mobile menu button -->
<div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200">
  <div class="flex items-center justify-between px-4 py-3">
    <h1 class="text-lg font-bold text-gray-900">Admin Portal</h1>
    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-neutral-800">
      <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        <path x-show="sidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
  </div>
</div>

<!-- Overlay for mobile -->
<div x-show="sidebarOpen"
  @click="sidebarOpen = false"
  x-transition:enter="transition-opacity ease-linear duration-300"
  x-transition:enter-start="opacity-0"
  x-transition:enter-end="opacity-100"
  x-transition:leave="transition-opacity ease-linear duration-300"
  x-transition:leave-start="opacity-100"
  x-transition:leave-end="opacity-0"
  class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden"
  style="display: none;">
</div>

<!-- Sidebar -->
<aside
  x-show="sidebarOpen || window.innerWidth >= 1024"
  @resize.window="if (window.innerWidth >= 1024) sidebarOpen = false"
  x-transition:enter="transition ease-in-out duration-300 transform"
  x-transition:enter-start="-translate-x-full"
  x-transition:enter-end="translate-x-0"
  x-transition:leave="transition ease-in-out duration-300 transform"
  x-transition:leave-start="translate-x-0"
  x-transition:leave-end="-translate-x-full"
  class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 lg:translate-x-0"
  style="display: none;">

  <div class="flex flex-col h-full">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
      <div>
        <h2 class="text-xl font-extrabold text-gray-900">DINO UNIVERSITY</h2>
        <p class="text-xs text-gray-600 mt-1">Admin Portal</p>
      </div>
    </div>

    <!-- User Info -->
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="flex items-center space-x-3">
        <div class="h-10 w-10 rounded-full bg-neutral-800 flex items-center justify-center">
          <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin') ?></p>
          <p class="text-xs text-gray-600">Administrator</p>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
      <a href="/index.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= basename($_SERVER['PHP_SELF']) == 'index.php' && !isset($_GET['semester']) ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 <?= basename($_SERVER['PHP_SELF']) == 'index.php' && !isset($_GET['semester']) ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Dashboard
      </a>

      <a href="/enrollment/index.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/enrollment/') !== false ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
        </svg>
        Enrollment
      </a>

      <a href="/students/index.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/students/') !== false ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        Students
      </a>

      <a href="/collections/index.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/collections/') !== false ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        Collections
      </a>

      <a href="/courses/index.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/courses/') !== false ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        Courses
      </a>

      <a href="/subjects/index.php?semester=1st25-26"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/subjects/') !== false ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        Subjects
      </a>

      <a href="/grades/index.php?semester=1st25-26"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/grades/') !== false ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
        </svg>
        Grades
      </a>

      <a href="/semesters/index.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= strpos($_SERVER['REQUEST_URI'], '/semesters/') !== false ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Semesters
      </a>

      <a href="/list.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= basename($_SERVER['PHP_SELF']) == 'list.php' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        List
      </a>

      <a href="/report.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= basename($_SERVER['PHP_SELF']) == 'report.php' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Report
      </a>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t border-gray-200">
      <form method="post" action="/logout.php">
        <button type="submit"
          class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-neutral-800 hover:bg-neutral-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-800 transition-colors">
          <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          Logout
        </button>
      </form>
    </div>
  </div>
</aside>