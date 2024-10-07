<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Jobs\SendTaskReminder;
use App\Events\TaskUpdated;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $users = Auth::user();
    $title = 'Your Tasks, ' . $users->name;
    $tasks = Task::where('user_id', $users->id);
    // urutkan sesuai yang terbaru
    $tasks = $tasks->orderBy('due_date', 'desc');

    // Filter tasks berdasarkan tanggal kadaluarsa
    if ($request->has('due_date')) {
        $dueDate = $request->input('due_date'); // YYYY-MM-DD
        $tasks->whereDate('due_date', $dueDate);
    }
     // Filter berdasarkan frequency_id jika ada
     if ($request->has('frequency_id')) {
        $frequencyId = $request->input('frequency_id');
        $tasks->where('frequency_id', $frequencyId);
    }
    // filter calendar
    if ($request->has('calendar_id')){
        $calendarId= $request->input('calendar_id');
        $tasks->where('calendar_id', $calendarId);
    }
    //filter berdasarkan category
    if($request->has('category_id')){
        $categoryId= $request->input('category_id');
        $tasks->where('category_id', $categoryId);
    }
    //filter berdasarkan status
    if ($request->has('status_id')){
        $statusId = $request->input('status_id');
        $tasks->where('status_id', $statusId);
    }
    $filteredTasks = $tasks->get();


     //Looping untuk Menangani Pengingat (Reminder)
     foreach ($filteredTasks as $task) {
        if ($task->reminder_at) {
            $reminderDelay = now()->diffInSeconds($task->reminder_at, false);

            if ($reminderDelay > 0) {
                SendTaskReminder::dispatch($task)->delay($reminderDelay);
            }
        }
    }
    $notifications = Auth::user()->notifications;



    // Mengelompokkan dan menghitung jumlah task berdasarkan status_id
    $taskCountByStatus = $filteredTasks->groupBy('status_id')
                                       ->map(function ($tasks) {
                                           return $tasks->count();
                                       });

    // Ambil semua status dari tabel statuses
    $statuses = Status::all();




    // Membuat daftar tanggal bulan ini
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    $dates = collect();
    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
    $dates->push($date->copy());
    }
    //lte() less than or equal
    //addDay() menambahkan satu hari ke objek $date di setiap iterasi loop. Jadi, pada setiap iterasi, $date akan maju satu hari.
    //push() adalah metode dari Collection Laravel yang menambahkan item ke dalam koleksi $dates


    return view('tasks', compact('title','filteredTasks','taskCountByStatus', 'statuses', 'startOfMonth','endOfMonth', 'dates', 'notifications'));
}

        public function form(){
        $user = Auth::user();
        if(!$user){
            return redirect()->route('login');
        };
        return view('task', compact('user'));
    }




    public function add(Request $request){
        Log::info('Request data: ', $request->all());
        $request->validate([
            'title' =>'required|string|max:255',
            'description' =>'nullable|string|max:255',
            'due_date' => 'required|date_format:Y-m-d\TH:i', //Input datetime-local di HTML memerlukan format ini
            'frequency_id' => 'required|exists:frequencies,id',
            'category_id' => 'required|exists:categories,id',
            'status_id' => 'required|exists:statuses,id',
            'calendar_id' => 'required|exists:calendars,id'
        ]);
        // Hitung waktu pengingat 24 jam sebelum due_date
        $dueDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('due_date'));
        $reminderAt = $dueDate->copy()->subHours(24); // Gunakan copy() untuk menjaga $dueDate tidak terpengaruh

        $user = Auth::user();
        if(!$user){
            return redirect()->route('login');
        };

        // Buat task baru dengan waktu pengingat otomatis
        $task = Task::create([
        'user_id' => Auth::id(),
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'due_date' => $dueDate,
        'reminder_at' => $reminderAt,
        'frequency_id' => $request->input('frequency_id'),
        'category_id' => $request->input('category_id'),
        'status_id' => $request->input('status_id'),
        'calendar_id' => $request->input('calendar_id'),
    ]);
        Log::info('Task saved successfully');

         // Hitung delay
    $now = Carbon::now();
    $delay = $reminderAt->diffInSeconds($now);

    // Dispatch job dengan delay
    SendTaskReminder::dispatch($task)->delay(now()->addSeconds($delay));

         // Broadcast event
         broadcast(new TaskUpdated($task));

        return redirect()->route('tasks.index')->with('success','Task has been added successfully');
    }




    public function edit(Request $request,$id){
        Log::info('Request data: ', $request->all());
        $request->validate([
            'title' =>'required|string|max:255',
            'description' =>'nullable|string|max:255',
            'due_date' => 'required|date_format:Y-m-d\TH:i',
            'frequency_id' => 'required|exists:frequencies,id',
            'category_id' => 'required|exists:categories,id',
            'status_id' => 'required|exists:statuses,id',
            'calendar_id' => 'required|exists:calendars,id'
        ]);
        $dueDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('due_date'));
        $reminderAt = $dueDate->copy()->subHours(24);
        $user = Auth::user();
        if(!$user){
            return redirect()->route('login');
        };
        $task = Task::find($id);
        if(!$task || $task->user_id != $user->id){
            return redirect()->route('tasks.index')->with('error','Task not found');
        }
         // Perbarui task dengan data baru
        $task->update([
        'title' => $request->input('title'),
        'description' => $request->input('description'),
        'due_date' => $dueDate,
        'reminder_at' => $reminderAt,
        'frequency_id' => $request->input('frequency_id'),
        'category_id' => $request->input('category_id'),
        'status_id' => $request->input('status_id'),
        'calendar_id' => $request->input('calendar_id'),
        ]);
        Log::info('Task updated successfully');

         // Hitung delay untuk notification
        $now = Carbon::now();
        $delay = $reminderAt->diffInSeconds($now);

       // Dispatch job dengan delay
       SendTaskReminder::dispatch($task)->delay(now()->addSeconds($delay));

         // Broadcast event
         broadcast(new TaskUpdated($task));
        return redirect()->route('tasks.index')->with('success', 'Update Task successfully!');
    }




    public function delete($id){
        $user = Auth::user();
        if(!$user){
            return redirect()->route('login');
        };
        $task = Task::find($id);
        if(!$task){
            return response()->json(['success' => false]);
        }
        if($task->user_id !== Auth::id()){
            return response()->json(['success' => false]);
        }
        $task->delete();
        return response()->json(['success' => true]);
    }




    public function updateStatus(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task || $task->user_id !== Auth::id()) {
            return redirect()->route('tasks.index')->with('error', 'Task not found');
        }

        $request->validate([
            'status_id' => 'required|exists:statuses,id',
        ]);
    
        $task->status_id = $request->input('status_id');
        $task->user_id = Auth::id();
        $task->save();

         // Broadcast event
         broadcast(new TaskUpdated($task));

        return response()->json(['success' => true]);
    }
}

//Format Y-m-d\TH:i sesuai dengan format standar yang dihasilkan oleh elemen input datetime-local di HTML. Elemen ini menghasilkan nilai dengan format seperti 2024-08-09T13:45, di mana:

// Y: Tahun (4 digit, misalnya 2024)
// m: Bulan (2 digit, misalnya 08 untuk Agustus)
// d: Tanggal (2 digit, misalnya 09)
// T: Huruf "T" sebagai separator antara tanggal dan waktu
// H: Jam (2 digit, 24-jam, misalnya 13)
// i: Menit (2 digit, misalnya 45)