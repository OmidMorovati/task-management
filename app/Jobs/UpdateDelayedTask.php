<?php

namespace App\Jobs;

use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDelayedTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(TaskRepositoryInterface $taskRepository): void
    {
        $taskRepository->updateDelayedTask();
    }
}
