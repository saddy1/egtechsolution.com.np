<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.about', [
            'meta_title' => 'About EG Tech Solution | Computer Shop in Dhangadhi',
            'meta_description' => 'Learn about EG Tech Solution, a local computer, laptop and electronics shop serving Dhangadhi, Kailali and Nepal.',
        ]);
    }

    public function contact()
    {
        return view('frontend.contact', [
            'meta_title' => 'Contact EG Tech Solution | Computer Shop in Dhangadhi',
            'meta_description' => 'Contact EG Tech Solution in Dhangadhi, Kailali for laptops, computers, accessories, quotations and technical support.',
        ]);
    }
}
