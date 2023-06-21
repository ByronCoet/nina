<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

  public function store(Request $request)
  {
      Log::info('register store');
    
      $this->validate(request(), [          
          'name' => 'required',
          'email' => 'required|email',
          'company_id' => 'required',
          'password' => 'required'
      ]);

      User::create([
        'name' => $request->name,
        'email' => $request->email,
        'company_id' => $request->company_id,
        'password' => Hash::make($request->password)
    ]);

    $credentials = $request->only('email', 'password');

    Auth::attempt($credentials);

    $request->session()->regenerate();

    return redirect()->route('pages-page-2')->withSuccess('You have successfully registered & logged in!');
      
      // $user = User::create(request(['name', 'email', 'company_id', 'password']));
      
      // auth()->login($user);
      
      // return redirect()->to('/page-2');
  }
}
