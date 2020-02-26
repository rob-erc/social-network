<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CounterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $countUntil;

    public function __construct(int $countUntil)
    {
        $this->countUntil = $countUntil;
    }

    public function handle()
    {
        for ($i = 1; $i < $this->countUntil; $i++)
        {
            Log::info('Count: ' . $i);

            sleep(1);
        }
    }
}
