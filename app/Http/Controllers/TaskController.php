<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;


class TaskController extends Controller
{
    public function addTask(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'task' => 'required|string|max:255',
        ]);

        $task = Task::create([
            "_token" => csrf_token(),
            'user_id' => $request->user_id,
            'task' => $request->task,
        ]);

        return response()->json(['task' => $task, 'status' => 1, 'message' => 'Successfully created a task']);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:pending,done',
        ]);

        $task = Task::find($request->task_id);
        $task->status = $request->status;
        $task->save();

        $message = $task->status === 'done' ? 'Marked task as done' : 'Marked task as pending';
        return response()->json(['task' => $task, 'status' => 1, 'message' => $message]);
    }
}
