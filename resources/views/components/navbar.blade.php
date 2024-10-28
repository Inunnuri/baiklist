
    <nav class="navbar pt-1 lg:pt-4 pb-4 pl-10 pr-10">
      {{-- tampilkan pesan sukses --}}
      <div class="flex justify-end items-end">
        @if (session('success'))
          <x-alert type="success" :message="session('success')" />
        @elseif (session('info'))
          <x-alert type="info" :message="session('info')" />
        @endif
      </div>


      <div>
        <div class="flex items-center justify-between">
          {{-- kiri --}}
          <div class="flex items-center space-x-2 text-sm">
            <div class="block">
              <a href="{{route('home')}}"><span class="text-white font-medium text-xl lg:text-3xl bg-primary-900 hover:bg-primary-400 px-2 py-1 rounded-lg">Baiklist</span></a>
            </div>
            <div class="block">
              <div class="flex items-center">
                @auth
                <a href="{{route('calendar.index')}}" class="rounded-md hover:bg-primary-900 px-3 py-2 text-m lg:text-lg font-medium text-white">Calendar</a>
                <a href="{{route('tasks.index')}}" class="rounded-md hover:bg-primary-900 px-3 py-2 text-m text-center lg:text-lg font-medium text-white mr-2">My Tasks</a>
                @endauth
              </div>
            </div>
          </div>
          {{-- kanan --}}
          <div class="flex items-center space-x-1">
              @guest
              <a href="{{route('register')}}"><button type="button" class="relative rounded-lg bg-primary-900 px-2 py-1 text-white hover:bg-primary-400 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">Sign Up
              </button></a>
              @endguest
              @auth
              {{-- tambah task --}}
              <a href="{{route('task.form')}}">
                <button class="rounded-md bg-primary-700 px-3 py-2 text-sm font-semibold text-white hover:text-primary-950 hover:bg-primary-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 lg:size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
              </a>

              {{-- notif --}}
              <button type="button" data-dropdown-toggle="notification-dropdown" class="relative p-2 mr-1 text-white rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:ring-2 focus:ring-white">
                <!-- Bell icon -->
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20"><path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z"/></svg>
                {{-- tanda untuk notifikasi yang muncul sesuai dengan yang belum dibaca dan sesuai dengan reminder_at hari ini atau sebelumnya --}}
                @if(Auth::user()->notifications()
                ->whereNull('read_at')
                ->whereDate('data->reminder_at', '<=', now()->format('Y-m-d'))
                ->count())
                <span class="absolute top-0 right-0 inline-flex items-center justify-center w-2 h-2 p-2 text-sm font-medium text-white bg-red-500 rounded-full">
                  {{ Auth::user()->notifications()
                    ->whereNull('read_at')
                    ->whereDate('data->reminder_at', '<=', now()->format('Y-m-d'))
                    ->count() }}
                </span>
                @endif
              </button>
              <!-- Dropdown or modal for notifications -->
              <div class="hidden overflow-hidden z-50 max-w-sm text-base list-none bg-white dark:bg-primary-900 rounded-lg divide-y divide-gray-300 p-4" id="notification-dropdown">
                      <div class="block py-2 px-4 mb-2 text-m lg:text-lg font-bold text-center text-gray-900 dark:text-gray-100">
                        Notifications
                      </div>
                      <div class="text-m lg:text-base dark:text-white divide-y divide-gray-300">
                        <ul class="mt-2 mb-2">
                          @foreach(Auth::user()->notifications as $notification)
                              @php
                                  $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                                  // Mengambil reminder_at sebagai Carbon instance
                                  $reminderAt = isset($data['reminder_at']) ? \Carbon\Carbon::parse($data['reminder_at']) : null;
                                  $now = \Carbon\Carbon::now();
                                  $isRead = !is_null($notification->read_at); // Cek status notifikasi
                              @endphp

                              @if($reminderAt && $reminderAt->isToday()) {{-- Hanya menampilkan jika reminder_at adalah hari ini --}}
                                  <li class="">
                                      @if(isset($data['task_id']) && $notification->notifiable_id === Auth::id())
                                          <a href="{{ route('notifications.read', ['id' => $notification->id]) }}">
                                            <strong class="{{ $isRead ? 'read' : 'unread' }}">{{ $data['task_title'] ?? 'No Title' }} - Due: {{ \Carbon\Carbon::parse($data['task_due'])->translatedFormat('j F Y') }}
                                            </strong>
                                              <br>
                                          </a>
                                      @endif
                                  </li>
                              @elseif($reminderAt && $reminderAt->lt($now)) {{-- Menampilkan jika reminder_at sudah lewat --}}
                                  <li>
                                      @if(isset($data['task_id']) && $notification->notifiable_id === Auth::id())
                                          <a href="{{ route('notifications.read', ['id' => $notification->id]) }}">
                                            <strong class="{{ $isRead ? 'read' : 'unread' }}">{{ $data['task_title'] ?? 'No Title' }} - Due: {{ \Carbon\Carbon::parse($data['task_due'])->translatedFormat('j F Y') }}
                                            </strong>
                                              <br>
                                          </a>
                                      @endif
                                  </li>
                              @endif
                          @endforeach
                        </ul>
                         {{-- tombol markALLRead dan DeleteALlRead --}}
                        <div class="flex justify-between mt-8">
                          <form action="{{ route('notifications.Allread') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-4">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 dark:text-white dark:hover:text-primary-200 hover:text-primary-600">
                                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                              </svg>             
                            </button>
                          </form>
                          <form action="{{ route('notifications.delete' )}}" method="POST">
                            @csrf
                            @method ('DELETE')
                            <button type="submit" class="px-3 py-4">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 dark:text-white dark:hover:text-red-600 hover:text-red-600">
                                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                              </svg>     
                            </button>
                          </form>
                        </div>
                      </div>
              </div>


              {{-- toggle --}}
              <div class="relative">
                {{-- button --}}
                  <button type="button"class="flex max-w-xs items-center rounded-full" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown-mobile">
                    @if(isset($profiles) && $profiles->profile_photo)
                    <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . $profiles->profile_photo) }}" alt="Profile Photo">
                    @else
                    <img src="{{ asset('storage/default.jpg') }}" class="h-8 w-8 rounded-full" alt="Default Photo">
                    @endif
                  </button>
                {{-- dropdown menu --}}
                <div class="hidden bg-white dark:bg-primary-900 p-2 rounded-sm" id="dropdown-mobile">
                    <div class="flex items-center px-5">
                      <div>
                        @php
                          $user = Auth::user();
                        @endphp
                        <a href="{{route('profile', ['id'=>Auth::id()])}}">
                          <div class="text-base font-medium text-gray-800 dark:text-white hover:text-primary-400 dark:hover:text-primary-400 mt-2">{{$user->name}}</div>
                        </a>
                        <div class="text-sm font-medium text-gray-400">{{$user->email}}</div>
                      </div>
                    </div>
                    <div class="flex items-center px-5 py-3">
                      <a href="{{route('logout')}}">
                        <button type="button" class="relative rounded-lg text-sm bg-primary-900 dark:bg-primary-200 px-2 py-1 dark:text-gray-900 text-white hover:bg-primary-400 dark:hover:bg-primary-400">Sign Out
                        </button>
                      </a>
                    </div>
                    <hr class="border">
                    {{-- toggle dark mode --}}
                    <div class="flex items-center px-5 py-3">
                      <button id="theme-toggle">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 dark:text-white">
                        <path d="M12 .75a8.25 8.25 0 0 0-4.135 15.39c.686.398 1.115 1.008 1.134 1.623a.75.75 0 0 0 .577.706c.352.083.71.148 1.074.195.323.041.6-.218.6-.544v-4.661a6.714 6.714 0 0 1-.937-.171.75.75 0 1 1 .374-1.453 5.261 5.261 0 0 0 2.626 0 .75.75 0 1 1 .374 1.452 6.712 6.712 0 0 1-.937.172v4.66c0 .327.277.586.6.545.364-.047.722-.112 1.074-.195a.75.75 0 0 0 .577-.706c.02-.615.448-1.225 1.134-1.623A8.25 8.25 0 0 0 12 .75Z" />
                        <path fill-rule="evenodd" d="M9.013 19.9a.75.75 0 0 1 .877-.597 11.319 11.319 0 0 0 4.22 0 .75.75 0 1 1 .28 1.473 12.819 12.819 0 0 1-4.78 0 .75.75 0 0 1-.597-.876ZM9.754 22.344a.75.75 0 0 1 .824-.668 13.682 13.682 0 0 0 2.844 0 .75.75 0 1 1 .156 1.492 15.156 15.156 0 0 1-3.156 0 .75.75 0 0 1-.668-.824Z" clip-rule="evenodd" />
                      </svg>                      
                        <span class="tooltip-text">Dark or Light Mode</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              @endauth
          </div>
        </div>
      </div>
    </nav>

    {{-- done --}}