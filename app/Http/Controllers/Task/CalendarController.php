<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;


class CalendarController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        if(!$user){
          return redirect()->route('login');
        };
        $title = 'Your Calendar, ' . $user->name;
        

         // Ambil tanggal dari query string
    $date = $request->query('due_date');

    // Mengambil tasks dari database sesuai dengan tanggal jika tersedia
    if ($date) {
        // Filter tugas berdasarkan tanggal
        $tasks = Task::where('user_id', $user->id)
            ->whereDate('due_date', $date)
            ->orderBy('due_date')
            ->get();
    } else {
        // Ambil semua tugas jika tidak ada tanggal
        $tasks = Task::where('user_id', $user->id)
            ->orderBy('due_date')
            ->get();
    }

        // Format data untuk kalender
        $calendarData = $tasks->map(function($task) {
         return [
            'title' => $task->title,
            'start' => $task->due_date,
            'classNames' => $task->status_id == 3 ? ['task-completed'] : [],
         ];
        });


        // Debugging: Output tasks data
        // Log::info('Tasks:', $tasks->toArray());
        
        if ($request->ajax()) {
            return response()->json($calendarData);
        }
        return view('calendar', compact('title', 'calendarData', 'date'));
    }

}
