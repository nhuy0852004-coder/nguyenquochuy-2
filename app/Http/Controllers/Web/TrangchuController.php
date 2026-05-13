<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrangchuController extends Controller
{
    public function index()
    {
        return view('web.trangchu.index');
    }
}
