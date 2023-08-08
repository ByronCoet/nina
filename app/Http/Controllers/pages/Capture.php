<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class Capture extends Controller
{
  public function index()
  {
    $companies = Company::all();
    return view('content.pages.pages-capture', ['companies' => $companies]);
  }
}
