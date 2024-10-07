<!DOCTYPE html>
<html lang="en" class="h-full ">
  <head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    {{-- <script>
  
      // Enable pusher logging - don't include this in production
      Pusher.logToConsole = true;
  
      var pusher = new Pusher('59029105cd8e55e1e657', {
        cluster: 'ap1'
      });
  
      var channel = pusher.subscribe('my-channel');
      channel.bind('my-event', function(data) {
        alert(JSON.stringify(data));
      });
    </script> --}}
    <title>Baiklist</title>
  </head>

  <body class="h-full bg-gray-50 dark:bg-gray-800">
    <div>
      <x-navbar></x-navbar>
      <main>
        <div class="mx-auto max-w-7xl py-6 px-8 md:px-8 lg:px-10">
          <!-- Your content -->
          {{ $slot }}
        </div>
      </main>
    </div>
     {{-- toggle dark mode --}}
     <script>
      const toggleButton = document.getElementById('theme-toggle');
      const bodyElement = document.documentElement;
  
      toggleButton.addEventListener('click', () => {
          bodyElement.classList.toggle('dark');
          // Optionally save the user's preference in localStorage
          if (bodyElement.classList.contains('dark')) {
              localStorage.setItem('theme', 'dark');
          } else {
              localStorage.setItem('theme', 'light');
          }
      });
  
      // Load the theme on page load
      window.onload = () => {
          const savedTheme = localStorage.getItem('theme');
          if (savedTheme === 'dark') {
              bodyElement.classList.add('dark');
          }
      };
    </script>
  </body>
</html>