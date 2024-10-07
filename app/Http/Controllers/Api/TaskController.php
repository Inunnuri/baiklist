<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Events\TaskUpdated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Events\MessageSent;

class TaskController extends Controller
{
    //
    public function apiIndex(Request $request)
    {
        $user = $request->user(); // Ini akan mendapatkan pengguna yang terautentikasi
        $tasks = Task::where('user_id', $user->id)->get();
        // return response()->json($tasks);
        return TaskResource::collection($tasks);
    }



    public function add(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $task = Task::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'task'=>$task], 201);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $task = Task::findOrFail($id);
        $task->update($request->all());

        // Kirim event broadcast
    event(new TaskUpdated($task));

        return response()->json([
            'message' => 'Task updated successfully',
            'task' => $task
        ]);
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['success' => true]);
    }

    // public function updateStatus(Request $request, $id)
    // {
    //     $request->validate([
    //         'status_id' => 'required|exists:statuses,id',
    //     ]);

    //     $task = Task::findOrFail($id);
    //     $task->status_id = $request->input('status_id');
    //     $task->save();

    //     return response()->json($task);
    // }
}

