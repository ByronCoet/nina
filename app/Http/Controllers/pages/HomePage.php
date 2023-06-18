<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomePage extends Controller
{
  public function index()
  {
    // return view('content.pages.pages-home');

    $c = DB::table('companies')->get();

    return view('content.authentications.auth-login-basic', [ 'companies' => $c]);
    
  }
}
