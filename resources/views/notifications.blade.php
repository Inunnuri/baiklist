<header class="relative bg-headerCalendar bg-center bg-cover min-h-[100px] flex items-center justify-center">
  <div class="absolute inset-0 bg-black opacity-50">
  </div>
</header>

<x-layout>
  <div class="container bg-primary-50 shadow-lg rounded-md p-4 m-4">
    <h1 class="text-xl font-bold mb-4">Notifications</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($notifications->isEmpty())
        <p>No new notifications.</p>
    @else
        <ul class="list-group">
          @foreach(auth()->user()->unreadNotifications as $notification)
            <li class="list-group-item {{ $notification->read_at ? 'read' : 'unread' }}">
                   @if(isset($data['task_id']) && $notification->notifiable_id === Auth::id())
                     <strong>{{ $data['task_title'] ?? 'Unknown Task' }}</strong> - Due on {{ $data['task_due'] ?? 'Unknown Date' }}
                     @endif
            </li>
           @endforeach
        </ul>
    @endif
  
  </div>
</x-layout>

