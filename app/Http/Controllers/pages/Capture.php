<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class Capture extends Controller
{
  public function capturenew()
  {
    $companies = Company::all();
    return view('content.pages.pages-capture-new', ['companies' => $companies]);
  }


  public function NewDonation(Request $request)
  { 
    Log::info('new donation');

    $name = $request->input('formValidationFirstname'); 
    $sur = $request->input('formValidationSurname'); 
    $mob = $request->input('formValidationMobile'); 
    $comp = $request->input('formValidationCompany'); 
    $consent = $request->input('consent'); 
    $edate = $request->input('eventdate'); 
    $don = $request->input('donate'); 
    $conv = $request->input('convert'); 
    $supp = $request->input('support'); 

    Log::info($don);
    Log::info($conv);
    Log::info($supp);

    $user = \App\Models\User::create(
      [
          'company_id'        => $comp,
          'name'              => $name,
          'surname'           => $sur,
          'mobile'            => $mob,
          'role'              => 'donor',
          'email'             => uniqid() . '@dummy.com',
          'consent'           => $consent == 'on' ? 1 : 0,
          'password'          => Hash::make('No0neKnows'),
          'email_verified_at' => Carbon::now()->toDateTimeString(),
          'created_at'        => Carbon::now()->toDateTimeString(),
          'updated_at'        => Carbon::now()->toDateTimeString()
      ]
    );

    $donation = \App\Models\Donation::create(
      [
          'user_id'        => $user->id,
          'campaign_id'    => $this->site_settings->campaign_id,
          'company_id'     => $comp,
          'event_date'     => $edate,
          'donated'        => $don == 'on' ? 1 : 0,
          'converted'      => $conv == 'on' ? 1 : 0,
          'supported'      => $supp == 'on' ? 1 : 0,
      ]
    );

    // Byron - add a try except around the above lot with suitable json error.
    $companies = Company::all();
    return view('content.pages.pages-capture-new', ['companies' => $companies]);
    
    return response()->json([
      'code' => 200,
    ]);

  }

  public function captureexisting()
  {
    $companies = Company::all();
    return view('content.pages.pages-capture-existing', ['companies' => $companies]);
  }
}
