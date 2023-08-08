<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected $site_settings;

    public function __construct() 
    {

        $existingSetting = SiteSetting::where('id', 1)->get();

        if (empty($existingSetting)) {
        
            $setting = SiteSetting::updateOrCreate(
                ['id' => 1],
                [
                'campaign_id' => 0, 
                'campaign_name' => "No Campaign"
                ]
            );
        } 
        
        // Fetch the Site Settings object
        $this->site_settings = SiteSetting::first();

        View::share('site_settings', $this->site_settings);
    }

    
}
