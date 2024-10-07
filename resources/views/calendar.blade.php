<header class="relative bg-headerCalendar bg-center bg-cover min-h-[250px] flex items-center justify-center">
  <h1 class="text-white text-xl lg:text-3xl tracking-tight mt-20 z-50">{{$title}}</h1>
  <div class="absolute inset-0 bg-black opacity-50">
  </div>
</header>

<x-layout>
    <div id="calendar"></div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
                events: @json($calendarData),
                dateClick: function(info) {
                // Mengarahkan ke halaman tugas dengan parameter tanggal
                 window.location.href = '/tasks?due_date=' + encodeURIComponent(info.dateStr);
                }
            });
            calendar.render();
        });
    </script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
</x-layout>


