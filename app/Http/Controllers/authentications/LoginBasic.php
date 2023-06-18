<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    $c = DB::table('companies')->get();

    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs, 'companies' => $c]);
  }
}
