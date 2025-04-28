<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTaskJob;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index(){
        return view('createTask');
    }

    public function submit(Request $request)
    {
        // Validate input if needed
        $request->validate([
            'param' => 'required|string',
        ]);

        $task = Task::create([
            'status' => 'pending'
        ]);
        dispatch(new ProcessTaskJob($task->id, $request->param));


        // // Dispatch job
        // ProcessTaskJob::dispatch($task->id,$request->param);

        return response()->json([
            'task_id' => $task->id,
            'status' => $task->status
        ], 202);
    }

    public function status(string $id)
    {
        $task = Task::findOrFail($id);

        return response()->json([
            'task_id' => $task->id,
            'status' => $task->status
        ]);
    }

    public function result(string $id)
    {
        $task = Task::findOrFail($id);

        if ($task->status === 'completed') {
            return response()->json([
                'task_id' => $task->id,
                'result' => $task->result
            ]);
        } elseif ($task->status === 'failed') {
            return response()->json([
                'task_id' => $task->id,
                'error' => $task->error
            ], 400);
        } else {
            return response()->json([
                'message' => 'Task is not yet completed.'
            ], 202);
        }
    }
}
