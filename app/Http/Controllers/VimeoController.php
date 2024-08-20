<?php

namespace App\Http\Controllers;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Http\Request;
use Vimeo\Laravel\VimeoManager;
class VimeoController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request, VimeoManager $vimeo)
    {

        dd($request->video);

        $uri = $vimeo->upload($request->video, [
            'name' => $request->name,
        ]);


//        Vimeo::upload($request->video, [
//            'name' => $request->name,
//        ]);

        dd($uri);
    }
}
