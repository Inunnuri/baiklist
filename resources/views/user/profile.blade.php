<header class="relative bg-primary-900 min-h-[100px] flex items-center justify-center">
  <div class="absolute inset-0 bg-black opacity-50">
  </div>
</header>

<x-layout>
  <div class="bg-wallpaper bg-center bg-cover min-h-[550px] shadow-xl rounded-lg mt-2">
    <div class="container px-20 py-5">
      <div class="flex flex-col items-start">
        <img src="{{$profile->profile_photo ? asset('storage/' . $profile->profile_photo) : asset('storage/default.jpg') }}" alt="photo profile" class="w-48 h-48 object-cover rounded-full">
        <h2 class="text-medium font-semibold mt-2 mb-5">{{$user->username}}</h2>
      </div>
      <hr class="border">
      <div class="inline-flex mt-4 mb-2">
        <strong class="w-48">Name:</strong>
        <span>{{$profile->name}}</span>
      </div>
      <div class="flex mb-2">
        <strong class="w-48">Email:</strong>
        <span>{{$user->email}}</span>
      </div>
      <div class="flex mb-2">
        <strong class="w-48">Phone Number:</strong>
        <span>{{$profile->phone_number}}</span>
      </div>
      <div class="flex mb-2">
        <strong class="w-48">Address:</strong>
        <span>{{$profile->address}}</span>
      </div>
      <div class="flex">
        <strong class="w-48">Region:</strong>
        <span>{{$profile->region}}</span>
      </div>
    </div>
    {{-- modal form --}}
    <div class="px-20">
      <button id="updateProfileBtn" class="rounded-md bg-primary-700 px-3 py-2 text-sm font-semibold text-white hover:text-primary-950 shadow-sm hover:bg-primary-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">Update Profile</button>
      {{-- form --}}
      <div id="updateProfileForm" class="modal {{$errors->any() ? 'flex' : 'hidden'}} fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-primary-800 m-2 p-2 rounded-lg">
          <div>
            <h1 class="font-semibold dark:text-white text-xl mb-2 mt-6">Update Profile</h1>
            <form method="POST" action="{{route('profile.update')}}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <div class="space-y-2">
              <div class="w-full">
                <label for="name" class="font-medium dark:text-white">Name</label>
                <input type="text" id="name" class="@error ('name') is-invalid @enderror w-full px-3 py-2 text-sm dark:bg-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-600 focus:border-primary-600" name="name" value="{{ auth()->user()->name }}">
                @error('name')
                  <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror 
              </div>
              <div class="w-full">
                <label for="email" class="font-medium dark:text-white">Email</label>
                <p class="text-sm text-gray-400">kamu akan mengganti email untuk login</p>
                <input type="email" id="email" class="@error ('email') is-invalid @enderror w-full px-3 py-2 text-sm dark:bg-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-600 focus:border-primary-600" name="email" value="{{ auth()->user()->email }}">
                @error('email')
                <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
              </div>
              <div class="w-full">
                <label for="phone_number" class="font-medium dark:text-white">Phone Number</label>
                <input type="text" id="phone_number" class="@error ('phone_number') is-invalid @enderror w-full px-3 py-2 text-sm dark:bg-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-600 focus:border-primary-600" name="phone_number" value="{{ $profile->phone_number ?? ''}}">
                @error('phone_number')
                <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
              </div>
              <div class="w-full">
                <label for="address" class="font-medium dark:text-white">Address</label>
                <input type="text" id="address" class="@error ('address') is-invalid @enderror w-full px-3 py-2 text-sm dark:bg-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-600 focus:border-primary-600" name="address" value="{{ $profile->address ?? ''}}">
                @error('address')
                <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
              </div>
              <div class="w-full">
                <label for="region" class="font-medium dark:text-white">Region</label>
                <input type="text" id="region" class="@error ('region') is-invalid @enderror w-full px-3 py-2 text-sm dark:bg-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-600 focus:border-primary-600" name="region" value="{{ $profile->region ?? ''}}">
                @error('region')
                <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                </svg>
                <input type="file" name="profile_photo" id="profile_photo" class="@error ('profile_photo') is-invalid @enderror block w-full text-sm dark:bg-gray-400 text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                @error('profile_photo')
                <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
              </div>
              <div class="inline-flex space-x-4 pb-10">
                <button type="button" id="profileCancelBtn" class="rounded-md bg-gray-400 dark:bg-black px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 dark:hover:bg-gray-700 justify-end">Cancel</button>
                <button type="submit" class="rounded-md bg-primary-700 dark:bg-red-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-300 dark:hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">Save</button>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("updateProfileForm");
    const updateProfileBtn = document.getElementById("updateProfileBtn");
    const cancelProfileBtn = document.getElementById("profileCancelBtn");

    updateProfileBtn.addEventListener("click", function () {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    });

    cancelProfileBtn.addEventListener("click", function () {
        modal.classList.remove("flex");
        modal.classList.add("hidden");
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.classList.remove("flex");
            modal.classList.add("hidden");
        }
    });
  });

</script>
</x-layout>