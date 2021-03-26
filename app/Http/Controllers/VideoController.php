<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\MOdels\Video;
use App\Http\Requests\StoreVideoRequest;
use App\Jobs\ConvertVideoForDownloading;
use App\Jobs\ConvertVideoForStreaming;

class VideoController extends Controller
{
    public function index()
    {
        //
    }

    public function store()
    {
        //
    }

    public function update()
    {
        //
    }

    public function  destroy()
    {
        //
    }
}
