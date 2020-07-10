<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //

    public function index()
    {
        $title = "This is My Laravel Practice Project";
        return view('pages.index')->with('title', $title);
    }

    public function about()
    {
        $title = "About is";
        return view('pages.about')->with('title', $title);
    }

    public function services()
    {
        $data = array(
            'title' => 'Services',
            'services' => ['web Design', 'Programming', 'SEO']
        );
        return view('pages.services')->with($data);
    }
}
