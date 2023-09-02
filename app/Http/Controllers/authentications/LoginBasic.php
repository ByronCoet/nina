<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    $c = DB::table('companies')->get();

    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs, 'companies' => $c]);
  }

  public function authenticate(Request $request)
  {
      //Log::info('authenticate in');
      $credentials = $request->validate([
          'email' => 'required|email',
          'password' => 'required',          
      ]);


      //Log::info('authenticate about to try auth');

      if(Auth::attempt($credentials))
      {
          // Log::info('authenticate ok');

          $user = Auth::user();

          

          if ($user->role === "superadmin" || $user->role === "companyadmin" || $user->role === "receptionist")
          {
            Log::info('valid role');
            $request->session()->regenerate();
            return redirect()->route('pages-page-2')->withSuccess('You have successfully logged in!');
          }
          else{
            Log::info('not alid role');

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'role' => 'Your role does not allow login',
            ])->onlyInput('role');

          }
      }

      //Log::info('authenticate error');

      return back()->withErrors([
          'email' => 'Your provided credentials do not match in our records.',
      ])->onlyInput('email');
  }

  public function logout(Request $request)
  {
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();
      return redirect()->route('pages-home')->withSuccess('You have logged out successfully!');
  }    

}
