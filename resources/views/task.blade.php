{{-- form new task --}}

<header class="relative bg-headerCalendar bg-center bg-cover min-h-[100px] flex items-center justify-center">
  <div class="absolute inset-0 bg-black opacity-50">
  </div>
</header>

<x-layout>
  <section class="mx-auto max-w-lg m-5">
    <div class=" bg-white dark:bg-primary-600 p-8 rounded-lg shadow">
      <h1 class="text-2xl font-semibold mb-4">Add New Task</h1>
      <form action="{{route('tasks.add')}}" method="POST">
          @csrf

          <!-- Title -->
          <div class="mb-4">
              <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-900">Title</label>
              <input type="text" id="title" name="title" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-400 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
          </div>

          <!-- Description -->
          <div class="mb-4">
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-900">Description</label>
              <textarea id="description" name="description" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-400 focus:ring-primary-500 focus:border-primary-500" placeholder="description if you want"></textarea>
          </div>

          {{-- due_date --}}
          <div class="mb-4">
            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-900">Due Date</label>
            <input type="datetime-local" id="due_date" name="due_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-400 focus:ring-primary-500 focus:border-primary-500">
          </div>
          
          <!-- Frequency -->
          <div class="mb-4">
            <label for="frequency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-900">Repeat Frequency</label>
            <select id="frequency_id" name="frequency_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-400  focus:ring-indigo-500 focus:border-indigo-500">
              @foreach($frequencies as $frequency)
              <option value="{{ $frequency->id }}">{{ $frequency->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Calendar -->
          <div class="mb-4">
            <label for="calendar_id" class="block text-sm font-medium text-gray-700 dark:text-gray-900">Calendar</label>
            <select id="calendar_id" name="calendar_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-400  focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                @foreach ($calendars as $calendar)
                <option value="{{$calendar->id}}">{{$calendar->name}}</option>
                @endforeach
            </select>
        </div>

          <!-- Category -->
          <div class="mb-4">
              <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-900">Category</label>
              <select id="category_id" name="category_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-400 focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                @foreach ($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
              </select>
          </div>

          <!-- Status -->
          <div class="mb-4">
              <label for="status_id" class="block text-sm font-medium text-gray-700 dark:text-gray-900">Status</label>
              <select id="status_id" name="status_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm dark:bg-gray-400  focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                @foreach ($statuses as $status)
                <option value="{{$status->id}}">{{$status->name}}</option>
                @endforeach
              </select>
          </div>

          <div class="flex justify-end">
              <button type="submit" class="px-4 py-2 bg-primary-600 dark:bg-primary-800 dark:hover:bg-primary-300 text-white rounded hover:bg-primary-700 focus:ring-4 focus:ring-primary-500 focus:ring-opacity-50">Add Task</button>
          </div>
      </form>
    </div>
  </section>
</x-layout>