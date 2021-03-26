<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lowBitrateFormat  = (new X264)->setKiloBitrate(500);
        $medBitrateFormat  = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(3000);
        
        FFMpeg::fromDisk($this->video->disk)
        ->open($this->video->path)
        ->exportForHls()
        ->toDisk('streamable_videos')
        ->addFormat($lowBitrateFormat)
        ->addFormat($medBitrateFormat)
        ->addFormat($highBitrateFormat)
        ->save($this->video->id . '.m3u8');
    }
}
