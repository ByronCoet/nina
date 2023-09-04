<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class SiteSettingsController extends Controller
{
    public function setcampaign()
    {
        $user = Auth::user();

        if ($user->role == "receptionist" || $user->role == "companyadmin" )
        {
            $companies = Company::where(['id' => $user->company->id ])->get();
            $campaigns = Campaign::where(['company_id' => $user->company->id ])->get();
        }
        else
        {
            $companies = Company::all();
            $campaigns = Campaign::all();      
        }

        return view('content.pages.pages-settings', ['companies' => $companies, 'campaigns' => $campaigns,]);
    }

    public function storecampaign(Request $request)
    {
        $camp_id = $request->formValidationCampaign;
        $camp = Campaign::where('id',$camp_id)->first();

        $setting = SiteSetting::updateOrCreate(
            ['id' => 1],
            [
              'campaign_id' => $camp->id, 
              'campaign_name' => $camp->campaign_name
            ]
        );

        $this->site_settings = $setting;


        $companies = Company::all();      
        $campaigns = Campaign::all();      
        return view('content.pages.pages-settings-done', ['setting' => $setting]);
    }

    public function subcamp(Request $request)
    {
        $parent_id = $request->comp_id;

        $camps = Campaign::where('company_id',$parent_id)->get();

        return response()->json(['camps' => $camps]);
    
    }

}
