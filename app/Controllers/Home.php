<?php namespace App\Controllers;

use System\Midwork;

class Home extends Midwork
{
    /*
     * homepage
     */
    public function index()
    {
        return view('home_index');
    }
}