<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function community(): View
    {
        return view('pages.community');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }
}