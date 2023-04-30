<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmphasisController extends Controller
{
    public function index() 
    {
        $emphasis = Emphasis::all();
    }
}
