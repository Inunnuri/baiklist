<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use App\Models\Status;
use App\Events\TaskUpdated;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Jobs\SendTaskReminder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
{
    $users = Auth::user();
    $title = 'Your Tasks, ' . $users->name;
    $tasks = Task::where('user_id', $users->id);
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
    // diffInSeconds($task->reminder_at, false): Menghitung selisih antara waktu saat ini dan waktu yang ditentukan dalam reminder_at dalam detik.
    // false berarti hasilnya akan berupa nilai negatif, yang menandakan bahwa pengingat seharusnya sudah dikirim sebelumnya.
    //if ($reminderDelay > 0): Memeriksa apakah nilai $reminderDelay lebih besar dari 0. Jika lebih besar dari 0, ini berarti waktu pengingat belum terlewat, dan pengingat dapat dijadwalkan untuk dikirim.
    //->delay($reminderDelay): Metode ini digunakan untuk menentukan bahwa pekerjaan ini harus dijadwalkan dengan penundaan waktu yang ditentukan dalam $reminderDelay.

    $notifications = Auth::user()->notifications;



    // Mengelompokkan dan menghitung jumlah task berdasarkan status_id
    $taskCountByStatus = $filteredTasks->groupBy('status_id')
                                       ->map(function ($tasks) {
                                           return $tasks->count();
                                       });

    //groupBy digunakan untuk mengelompokkan koleksi $filteredTasks berdasarkan nilai dari kolom status_id
    //map digunakan untuk memodifikasi koleksi yang dihasilkan dari groupBy.




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


    return view('tasks', compact('title','filteredTasks','taskCountByStatus', 'startOfMonth','endOfMonth', 'dates', 'notifications'));
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
            'due_date' => 'required|date_format:Y-m-d\TH:i', //Input datetime-local di HTML5 memerlukan format ini
            'frequency_id' => 'required|exists:frequencies,id',
            'category_id' => 'required|exists:categories,id',
            'status_id' => 'required|exists:statuses,id',
            'calendar_id' => 'required|exists:calendars,id'
        ]);
        
        //due_date dari request diubah menjadi objek Carbon (library untuk manipulasi tanggal di Laravel).
        $dueDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('due_date'));
        // Hitung waktu pengingat 24 jam sebelum due_date
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

    // menjadwalkan pengiriman notifikasi
    SendTaskReminder::dispatch($task);
    //pastikan di SendTaskReminder nya menggunakan trait Dispatchable

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

        // Simpan tanggal jatuh tempo lama
         // Ambil oldDueDate sebagai objek Carbon
        $oldDueDate = Carbon::parse($task->due_date);
        $newDueDate = $dueDate;

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

        // Jika due_date berubah, update notifikasi, isSameDay adalah cara yang tepat untuk membandingkan dua objek Carbon berdasarkan tanggal.
         // Jika due_date berubah, hapus notifikasi lama dan buat notifikasi baru
         if (!$oldDueDate->isSameDay($newDueDate)) {
            // Hapus notifikasi lama
            $deleted = Notification::where('data->task_id', $task->id)->delete();
            // Log::info('Notifications deleted: ' . $deleted);
            
            // Kirim notifikasi baru
            SendTaskReminder::dispatch($task);
        }
        

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

        // Hapus notifikasi yang terkait dengan task ini
        Notification::where('data->task_id', $task->id)->delete(); // Menghapus notifikasi berdasarkan data

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
        $task->save();

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

// done