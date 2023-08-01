<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CampaignManagement extends Controller
{
  /**
   * Redirect to campaign-management view.
   *
   */
  public function CampaignManagement()
  {
    $companies = Company::all();
    $campaigns = Campaign::all();
    $roles = Role::all();
    $campaignCount = $campaigns->count();
    Log::info('campaign list called');        

    return view('content.laravel-example.campaign-management', [
      'totalCampaign' => $campaignCount,    
      'campaigns' => $campaigns,
      'companies' => $companies,
      'roles' => $roles,
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'company_name',
      3 => 'campaign_name', 
    ];

    Log::info('campaign index called');        

    $search = [];

    $totalData = Campaign::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    
    // Log::info('Order: ' . $order);
    $dir = $request->input('order.0.dir');

    // Log::info('Dir: ' . $dir);        

    if (empty($request->input('search.value'))) {
      if ($order == "company_name")
      { 
        Log::info('2');
        $campaigns = Campaign::offset($start)
          ->limit($limit) 
          ->join('companies', 'campaigns.company_id', '=', 'companies.id')         
          ->orderBy('company_name', $dir)
          ->get();     
      }
      else{
        Log::info('1');
        $campaigns =Campaign::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      }
    } else {
      $search = $request->input('search.value');
      if ($order == "campaign")
        { 
          Log::info('3');
          $campaigns = Campaign::where('campaigns.id', 'LIKE', "%{$search}%")          
          ->orWhere('campaign_name', 'LIKE', "%{$search}%")
          ->orWhere('companies.company_name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)          
          ->join('companies', 'campaigns.company_id', '=', 'companies.id')
          ->orderBy('campaign_name', $dir)          
          ->get();          
        }
        else
        {
          Log::info('4');
          $campaigns = Campaign::where('campaigns.id', 'LIKE', "%{$search}%")          
            ->orWhere('campaign_name', 'LIKE', "%{$search}%")
            ->orWhere('company_name', 'LIKE', "%{$search}%")            
            ->offset($start)
            ->limit($limit)
            ->join('companies', 'campaigns.company_id', '=', 'companies.id')
            ->orderBy($order, $dir)
            ->get();
        }

      Log::info('5');

      $totalFiltered = Campaign::where('campaigns.id', 'LIKE', "%{$search}%")
        ->join('companies', 'campaigns.company_id', '=', 'companies.id')
        ->orWhere('campaign_name', 'LIKE', "%{$search}%")
        ->orWhere('companies.company_name', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($campaigns)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      Log::info('6');

      foreach ($campaigns as $c) {
        $nestedData['id'] = $c->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['campaign_name'] = $c->campaign_name;
        $nestedData['company'] = $c->company->company_name;
        $data[] = $nestedData;
      }
    }

    if ($data) {
      return response()->json([
        'draw' => intval($request->input('draw')),
        'recordsTotal' => intval($totalData),
        'recordsFiltered' => intval($totalFiltered),
        'code' => 200,
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Internal Server Error',
        'code' => 500,
        'data' => [],
      ]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    Log::info('Store campaign called: ');

    $ID = $request->id;

    if ($ID) 
    {
      // update the value
      Log::info('Update campaign called: ');
      $campaign = Campaign::updateOrCreate(
        ['id' => $ID],
        ['campaign_name' => $request->campaign_name, 'company_id' => $request->company_id]        
      );

      // campaign updated
      return response()->json('Updated');
    } 
    else 
    {
      Log::info('Create campaign called: ');
      $campaign = Campaign::updateOrCreate(        
        ['campaign_name' => $request->campaign_name, 'company_id' => $request->company_id,]
      );
      return response()->json('Created');      
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {Log::info('edit campaign called: ');
    $where = ['id' => $id];
    
    $campaigns = Campaign::where($where)->first();
    $roles = Role::all();
    $companies = Company::all();

    return response()->json(['campaigns' => $campaigns, 'companies' => $companies, 'roles' => $roles]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $c = Campaign::where('id', $id)->delete();
  }
}
