<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{


    public function addTask(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'task' => 'required|string|max:255',
        ]);

        $task = Task::create([
            'user_id' => $validated['user_id'],
            'task' => $validated['task'],
            'status' => 'pending'
        ]);

        return response()->json([
            'task' => $task,
            'status' => 1,
            'message' => 'Successfully created a task'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:pending,done',
        ]);

        $task = Task::findOrFail($validated['task_id']);
        $task->status = $validated['status'];
        $task->save();

        $message = $validated['status'] === 'done' ? 'Marked task as done' : 'Marked task as pending';

        return response()->json([
            'task' => $task,
            'status' => 1,
            'message' => $message
        ]);
    }
}
