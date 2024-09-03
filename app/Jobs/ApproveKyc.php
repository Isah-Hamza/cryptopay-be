<?php

namespace App\Jobs;

use App\Models\Kyc;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ApproveKyc implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $kyc;

    public function __construct($kyc)
    {
        $this->kyc = $kyc;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $new_kyc = Kyc::find($this->kyc->id);
        $new_kyc->status = 2;
        $new_kyc->save();
    }
}
