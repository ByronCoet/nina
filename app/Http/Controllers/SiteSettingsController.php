<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\Role;

class SiteSettingsController extends Controller
{
    public function setcampaign()
    {
        $companies = Company::all();      
        $campaigns = Campaign::all();      
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
