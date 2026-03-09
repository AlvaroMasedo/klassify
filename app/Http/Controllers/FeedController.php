<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\View\View;

class FeedController extends Controller{
    public function index(): View{
        $courses = Course::with('subjects')
            ->orderBy('name')
            ->get();
        return view('feed.index', [
            'courses' => $courses
        ]);
    }
}