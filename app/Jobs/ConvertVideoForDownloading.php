<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Video;
use Carbon;
use FFMpeg;
use FFMpeg\Format\Video\X264;

class ConvertVideoForDownloading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lowBitrateFormat = (new X264)->setKiloBitrate(500);

        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->path)
            ->addFilter(funtion($filters){
                $filters->resize(new Dimension(960, 540))
            })
            ->export()
            ->toDisk('downloadable_video')
            ->inFormat($lowBitrateFormat)
            ->save($this->video->id . '.mp4');
    }
}
