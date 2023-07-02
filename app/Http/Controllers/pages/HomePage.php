<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomePage extends Controller
{
  public function index()
  {
    if (Auth::check()) {
      // The user is logged in...
      return view('content.pages.pages-home');
    }
    else
    {
      $c = DB::table('companies')->get();
      return view('content.authentications.auth-login-basic', [ 'companies' => $c]);
    }    
  }
}
