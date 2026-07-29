<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $scrapedContents = DB::table("scraped_contents")->paginate(20);

        return view('dashboard', compact('scrapedContents'));
    }
}
