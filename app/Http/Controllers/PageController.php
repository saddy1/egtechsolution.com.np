<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }
}
