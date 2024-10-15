<?php

namespace App\Http\Controllers;

use App\Models\Task;

class HomeController extends Controller
{
    public function index(){
        $title = 'Home';
       
        return view('home', compact('title'));
    }
}
// done
