<header class="relative bg-headerCalendar bg-center bg-cover min-h-[250px] flex items-center justify-center">
  <div class="absolute inset-0 bg-black opacity-50"></div>
  <div class="item-start mt-20 z-10">
    <h1 class=" text-xl lg:text-3xl text-white font-semibold mb-2">{{$title}}</h1>
    {{-- <input type="text" class="bg-gray-900 text-white rounded-lg" placeholder="search"> --}}
  </div>
</header>

<x-layout>
    <section>
        <div class="bg-white dark:bg-primary-900 p-4 m-5 rounded-lg shadow-lg">
            {{-- header --}}
            <div class="lg:flex lg:justify-between grid">
                <form action="{{ route('tasks.index') }}" method="GET" class="flex">
                    <input type="date" name="due_date" class="text-sm p-2 border rounded dark:bg-gray-400">
                    <button type="submit" class="text-sm lg:text-lg px-2 py-2 bg-green-700 hover:bg-green-300 text-white hover:text-green-950 rounded">Filter</button>
                </form>

                

                <form method="GET" action="{{route('tasks.index')}}">
                    <select name="frequency_id" onchange="this.form.submit()" class="border-transparent text-sm dark:bg-gray-400 rounded-lg">
                        {{-- onchange="this.form.submit()": Ini memungkinkan formulir dikirim secara otomatis ketika pengguna memilih opsi dari dropdown. --}}
                        <option>Repeat Frequency</option>
                        @foreach ($frequencies as $frequency)
                        <option value="{{$frequency->id}}">{{$frequency->name}}</option>
                        @endforeach
                    </select>
                </form>
                
                <form method="GET" action="{{route('tasks.index')}}">
                    <select name="calendar_id" onchange="this.form.submit()" class="border-transparent text-sm dark:bg-gray-400 rounded-lg">
                        <option value="">Calendar</option>
                        @foreach ($calendars as $calendar)
                        <option value="{{$calendar->id}}">{{$calendar->name}}</option>
                        @endforeach
                    </select>
                </form>
                
                <form method="GET" action="{{route('tasks.index')}}">
                    <select name="category_id" onchange="this.form.submit()" class="border-transparent text-sm dark:bg-gray-400 rounded-lg">
                        <option value="">Category</option>
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </form>
            
                <div class="flex items-end mb-4 gap-2">
                    <a href="{{route('task.form')}}">
                        <button class="rounded-md bg-primary-700 px-3 py-2 text-sm font-semibold text-white hover:text-primary-950 shadow-sm hover:bg-primary-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                              </svg>
                        </button>
                    </a>
                    <a href="{{route('tasks.index')}}" class="px-2 py-2 bg-primary-700 hover:bg-primary-300 text-white hover:text-primary-950 rounded">All Tasks</a>
                </div>
            </div>

            {{-- status --}}
            <div class="grid lg:grid-cols-3 gap-4 mb-4">
                @foreach ($statuses as $status)
                    <a href="{{ route('tasks.index', ['status_id' => $status->id]) }}" style="background-color: {{$status->color}}" class="p-4 rounded-lg">
                        <h2 class="text-lg font-semibold">{{$status->name}}</h2>
                        <div class="text-4xl font-bold">{{$taskCountByStatus[$status->id] ?? 0 }}</div>
                    </a>
                @endforeach
            </div>
            
            {{-- your tasks --}}
            <div class="lg:flex lg:justify-between grid gap-2">
                {{-- your tasks --}}
                <div class="lg:w-2/3 w-full">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-bold mb-2 text-primary-900 dark:text-primary-200 ">Your Schedule</h2>
                        <hr class="border"/>
                        @if($filteredTasks->isEmpty())
                           <p class="mt-2 text-red-600 dark:text-red-100">No tasks found</p>
                        @else
                        @foreach ($filteredTasks as $task)
                        <ul class="mt-2 mb-5">
                            <li class="post mb-2 flex items-start relative border-l-4 border-primary-100 pl-4 {{ $task->status_id == 3 ? 'line-through text-gray-500' : '' }}" data-task-id="{{$task->id}}">
                                <div class="flex-1">
                                    <div class="text-lg font-semibold mb-2 dark:text-gray-200">{{$task->title}}</div>
                                    <div class="text-sm dark:text-gray-200">{{$task->description}}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-200">{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y H:i') }}</div>
                                    <div class="text-green-600 dark:text-green-400">{{$task->calendar->name}} - {{$task->category->name}}</div>
                                    <div class="text-blue-600">{{$task->frequency->name}}</div>
                                    <div class="text-yellow-600">{{$task->status->name}}</div>
                                    <div class="flex items-center">
                                       <button data-task-id="{{$task->id}}" class="editTaskBtn">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-primary-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                          </svg>
                                        </button>

                                    
                                        <button data-task-id="{{$task->id}}" class="deleteTaskBtn">
                                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-red-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                          </svg>
                                        </button>
                                        <select class="change-status text-sm border-transparent p-1 rounded-lg dark:bg-gray-400" data-task-id="{{ $task->id }}">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $task->status_id == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                        </ul>
                        {{-- form edit --}}
                        <div data-task-id="{{$task->id}}" class="editTaskForm fixed inset-0 items-center justify-center bg-opacity-50 hidden z-50">
                            <div class="overlay">
                                <div class="bg-white dark:bg-primary-700 rounded-lg p-6 w-full max-w-md">
                                    <h3 class="font-semibold text-xl text-primary-900 dark:text-white mb-4">Edit Task</h3>
                                    <form method="POST" action="{{route('tasks.edit', $task->id)}}" class="grid grid-cols-2 gap-4">
                                        @csrf 
                                        @method('PUT')
                                        <!-- Title -->
                                        <div class="mb-4">
                                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-white">Title</label>
                                            <input type="text" id="title" name="title" required class="mt-1 block w-full p-2 dark:bg-gray-400 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" value="{{ old('title', $task->title ?? '') }}">
                                        </div>
                                          <!-- Description -->
                                          <div class="mb-4">
                                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-white">Description</label>
                                            <textarea id="description" name="description" rows="3" class="dark:bg-gray-400 mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" value="{{ old('description', $task->description ?? '') }}"placeholder="description if you want" ></textarea>
                                          </div>
                                        {{-- due_date --}}
                                        <div class="mb-4">
                                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-white">Due Date</label>
                                            <input type="datetime-local" id="due_date" name="due_date" class="dark:bg-gray-400 mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" value="{{old('due_date',$task->due_date ?? '')}}">
                                        </div>
                                          <!-- Frequency -->
                                        <div class="mb-4">
                                            <label for="frequency_id" class="block text-sm font-medium text-gray-700 dark:text-white">Repeat Frequency</label>
                                            <select id="frequency_id" name="frequency_id" class="dark:bg-gray-400 mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                              @foreach($frequencies as $frequency)
                                              <option value="{{ $frequency->id }}">{{ $frequency->name }}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                          <!-- Calendar -->
                                        <div class="mb-4">
                                            <label for="calendar_id" class="block text-sm font-medium text-gray-700 dark:text-white">Calendar</label>
                                            <select id="calendar_id" name="calendar_id" class="dark:bg-gray-400 mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                @foreach ($calendars as $calendar)
                                                <option value="{{$calendar->id}}">{{$calendar->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                          <!-- Category -->
                                        <div class="mb-4">
                                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-white">Category</label>
                                            <select id="category_id" name="category_id" class="dark:bg-gray-400 mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                              @foreach ($categories as $category)
                                              <option value="{{$category->id}}">{{$category->name}}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                        <!-- Status -->
                                        <div class="mb-4">
                                            <label for="status_id" class="block text-sm font-medium text-gray-700 dark:text-white">Status</label>
                                            <select id="status_id" name="status_id" class="dark:bg-gray-400 mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                              @foreach ($statuses as $status)
                                              <option value="{{$status->id}}">{{$status->name}}</option>
                                              @endforeach
                                            </select>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" class="cancelTaskBtn rounded-lg bg-gray-300 dark:bg-red-700 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-400" data-task-id="{{ $task->id }}">Cancel</button>
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">Update Task</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                {{-- calendar --}}
                <div class="lg:w-1/3 w-full">
                    <div class="bg-white dark:bg-red-900 p-4 rounded-lg shadow-lg mb-4">
                        <a href="{{ route('calendar.index') }}">
                            <h2 class="text-lg font-semibold mb-2 bg-red-100 hover:bg-red-500 p-2 items-center rounded">{{ $startOfMonth->format('F Y') }}</h2>
                        </a>
                        <div class="grid grid-cols-7 gap-1">
                            <div class="text-center font-semibold text-red-600">Su</div>
                            <div class="text-center font-semibold">Mo</div>
                            <div class="text-center font-semibold">Tu</div>
                            <div class="text-center font-semibold">We</div>
                            <div class="text-center font-semibold">Th</div>
                            <div class="text-center font-semibold">Fr</div>
                            <div class="text-center font-semibold">Sa</div>
                    
                            @php
                                $firstDayOfMonth = $startOfMonth->copy()->startOfMonth();
                                $daysInMonth = $startOfMonth->daysInMonth;
                                //daysInMonth adalah properti dari objek Carbon yang memberikan jumlah total hari dalam bulan tertentu (misalnya, 30 hari untuk September, 31 hari untuk Agustus).
                                $dayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
                            @endphp
                    
                            {{-- dayOfWeek menentukan berapa banyak slot kosong yang diperlukan sebelum hari pertama bulan itu ditampilkan. Misalnya, jika tanggal 1 jatuh pada hari Rabu (dayOfWeek = 3), maka tiga slot kosong (<div class="text-center"></div>) akan dibuat untuk Minggu, Senin, dan Selasa. --}}
                            @for ($i = 0; $i < $dayOfWeek; $i++)
                                <div class="text-center"></div>
                            @endfor
                    
                            {{-- Display all the days of the month --}}
                            @foreach ($dates as $date)
                                <div class="text-center {{ $date->isToday() ? 'bg-primary-200 rounded-full' : '' }}">
                                    {{ $date->day }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</x-layout>