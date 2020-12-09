<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopController extends Controller
{
    /**
     * Landingページを表示する
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('top.index');
    }
}
