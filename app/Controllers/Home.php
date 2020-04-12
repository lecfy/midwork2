<?php namespace App\Controllers;

use System\Controller;

class Home extends Controller
{
    /*
     * homepage
     */
    public function index()
    {
        return view('home_index');
    }
}