<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class RegisterBasic extends Controller
{
  public function index()
  {
    Log::info('register index');
    $c = DB::table('companies')->get();
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-register-basic', ['pageConfigs' => $pageConfigs, 'companies' => $c]);
  }

  public function store()
  {
      Log::info('register store');
    
      $this->validate(request(), [
          'name' => 'required',
          'email' => 'required|email',
          'company_id' => 'required',
          'password' => 'required'
      ]);
      
      $user = User::create(request(['name', 'email', 'company_id', 'password']));
      
      auth()->login($user);
      
      return redirect()->to('/page-2');
  }
}
