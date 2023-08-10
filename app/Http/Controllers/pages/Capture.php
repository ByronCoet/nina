<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class Capture extends Controller
{
  public function capturenew()
  {
    $companies = Company::all();
    return view('content.pages.pages-capture-new', ['companies' => $companies]);
  }

  public function captureexisting()
  {
    $companies = Company::all();
    return view('content.pages.pages-capture-existing', ['companies' => $companies]);
  }
}
