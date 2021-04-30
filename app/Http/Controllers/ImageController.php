<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    public function galery()
    {
        $image = Image::find(1);
        return response()->json($image->getMedia('galery'));
    }

    public function store(Request $request)
    {
        $image = Image::create();
        $media = $image->addMediaFromRequest('file')
                ->preservingOriginal()
                ->toMediaCollection();
        $url = $media ->getUrl();

        return response()->json([
            'image' => $image,
            'url'   => $url
        ]);
        
    }

    public function uploadImage(Request $request)
    {
        //
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
