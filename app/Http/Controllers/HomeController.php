<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Merchandise;

class HomeController extends Controller
{
    public function index()
    {
        $featuredEvents = Event::where('status', 'active')
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(6)
            ->get();
            
        $featuredMerchandise = Merchandise::where('status', 'active')
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('home', compact('featuredEvents', 'featuredMerchandise'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
