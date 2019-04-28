<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {
        $gagongTitle = "This is the index Title passed from controller";
        return view('custom_views.index', compact('gagongTitle'));
    }

    public function about() {
        $title = "This is the About Us passed from controller";
        return view('custom_views.about')->with('title', $title);
    }

    public function services() {
        $data = array(
            'title' => "Services passed from controller",
            'services' => ['Web Design', 'Programming', 'Laravel', 'SailsJS']
        );
        return view('custom_views.services')->with($data);
    }
}
