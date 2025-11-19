<!-- Mobile menu button -->
<div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200">
  <div class="flex items-center justify-between px-4 py-3">
    <h1 class="text-lg font-bold text-gray-900">Teacher Portal</h1>
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
        <p class="text-xs text-gray-600 mt-1">Teacher Portal</p>
      </div>
    </div>

    <!-- User Info -->
    <div class="px-6 py-4 border-b border-gray-200">
      <div class="flex items-center space-x-3">
        <div class="h-10 w-10 rounded-full bg-neutral-800 flex items-center justify-center">
          <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($_SESSION['user']['name']) ?></p>
          <p class="text-xs text-gray-600">Teacher</p>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
      <a href="index.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Dashboard
      </a>

      <a href="grading.php"
        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md <?= basename($_SERVER['PHP_SELF']) == 'grading.php' ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?> transition-colors">
        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Grading
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