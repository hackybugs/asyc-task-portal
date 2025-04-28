<?php

namespace App\Jobs;

use App\Models\Task;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTaskJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $taskId;

    public function __construct(string $taskId,string $param)
    {
        $this->taskId = $taskId;
        $this->param = $param;
    }

    public function handle()
    {
        $task = Task::findOrFail($this->taskId);

        try {
            // Simulate computationally heavy task
            sleep(5); // or heavy processing logic here
            $sinput = inval($this->param) * 3;
            $result = " Task completed successfully output is $sinput at " . now();

            $task->update([
                'status' => 'completed',
                'result' => $result
            ]);
        } catch (Exception $e) {
            $task->update([
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);
        }
    }
}
