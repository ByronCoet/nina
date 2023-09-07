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
use Illuminate\Support\Facades\Auth;

class CampaignManagement extends Controller
{
  /**
   * Redirect to campaign-management view.
   *
   */
  public function CampaignManagement()
  {

    $user = Auth::user();

    if ($user->role == "receptionist" || $user->role == "companyadmin" )
    {
       $campaigns = Campaign::where(['company_id' => $user->company->id])->get();
    }
    else
    {
       $campaigns = Campaign::all();
    }

    $companies = Company::all();
    
    $roles = Role::all();
    $campaignCount = $campaigns->count();
    Log::info('campaign list called');        

    return view('content.laravel.campaign-management', [
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
      4 => 'campaign_start', 
      5 => 'campaign_end', 
    ];

    $user = Auth::user();

    Log::info('campaign index called');        

    $search = [];

    $totalData =  Campaign::where(['company_id' => $user->company->id ])->count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];

    $comp_search = $request->get('extra_search');

    Log::info('EXTRAAAA: ' . $comp_search);     
    
    // Log::info('Order: ' . $order);
    $dir = $request->input('order.0.dir');

    Log::info('Dir: ' . $dir);        
    Log::info('search val: ' . $request->input('search.value')) ;        

    if (empty($request->input('search.value'))) {
      if ($order == "company_name")
      { 
        Log::info('2');
        $query = Campaign::offset($start)
          ->limit($limit)           
          ->join('companies', 'campaigns.company_id', '=', 'companies.id')             
          ->select('campaigns.*', 'companies.company_name' )
          ->orderBy('company_name', $dir);

        if (!empty($comp_search)) {
          $query->where('company_name', 'LIKE', "%{$comp_search}%");
        }

        if ($user->role == "receptionist" || $user->role == "companyadmin")
        {
          $query->where(['campaigns.company_id' => $user->company->id ]);
        }

        $campaigns = $query->get();     
      }
      else
      {
        Log::info('1');
        $query =Campaign::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir);

        if ($user->role == "receptionist" || $user->role == "companyadmin" )
        {
          $query->where(['campaigns.company_id' => $user->company->id ]);
        } 

        $campaigns = $query->get();
      }
    } else {
      $search = $request->input('search.value');
      if ($order == "campaign")
        { 
          Log::info('3');
          $query = Campaign::where(function ($q) use ($search) {
            $q->where('campaign_name', 'LIKE', "%{$search}%");
            $q->orWhere('company_name', 'LIKE', "%{$search}%");
            $q->orWhere('campaigns.id', 'LIKE', "%{$search}%");  
          })
          ->offset($start)
          ->limit($limit)          
          ->join('companies', 'campaigns.company_id', '=', 'companies.id')
          ->select('campaigns.*', 'companies.company_name' )
          ->orderBy('campaign_name', $dir);

          if ($user->role == "receptionist" || $user->role == "companyadmin" )
          {
            $query->where(['campaigns.company_id' => $user->company->id ]);
          } 

          if (!empty($comp_search)) {
            $query->where('company_name', 'LIKE', "%{$comp_search}%");
          }

          $campaigns = $query->get();          
        }
        else
        {
          Log::info('4');
          $query = Campaign::where(function ($q) use ($search) {
                $q->where('campaign_name', 'LIKE', "%{$search}%");
                $q->orWhere('company_name', 'LIKE', "%{$search}%");
                $q->orWhere('campaigns.id', 'LIKE', "%{$search}%");  
              })
            ->offset($start)
            ->limit($limit)
            ->join('companies', 'campaigns.company_id', '=', 'companies.id')
            ->select('campaigns.*', 'companies.company_name' )
            ->orderBy($order, $dir);

            if (!empty($comp_search)) {
              $query->where('company_name', '=', "{$comp_search}");
            }

            if ($user->role == "receptionist" || $user->role == "companyadmin" )
            {
              $query->where(['campaigns.company_id' => $user->company->id ]);
            } 

            $campaigns = $query->get();
        }

      Log::info('5');

      $query = Campaign::where(function ($q) use ($search) {
              $q->where('campaign_name', 'LIKE', "%{$search}%");
              $q->orWhere('company_name', 'LIKE', "%{$search}%");
              $q->orWhere('campaigns.id', 'LIKE', "%{$search}%");  
            })
            ->join('companies', 'campaigns.company_id', '=', 'companies.id')
            ->select('campaigns.*', 'companies.company_name' );

        if (!empty($comp_search)) {
          $query->where('company_name', '=', "{$comp_search}");
        }

        if ($user->role == "receptionist" || $user->role == "companyadmin" )
        {
          $query->where(['campaigns.company_id' => $user->company->id ]);
        } 

        $totalFiltered = $query->count();
    }

    $data = [];

    if (!empty($campaigns)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      Log::info('6');

      foreach ($campaigns as $c) {
        // Log::info('c id: ' . $c);
        $nestedData['comp_id'] = $c->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['company'] = $c->company->company_name;
        $nestedData['campaign_name'] = $c->campaign_name;
        $nestedData['campaign_start'] = $c->campaign_start;
        $nestedData['campaign_end'] = $c->campaign_end;
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

    $user = Auth::user();

    if ($user->role == "receptionist" || $user->role == "companyadmin")
    {
      $cid = $user->company_id;
    }
    else
    {
      // $cid = $request->company_id;
      $cid = $request->input('company_id');        
    }

    if ($ID) 
    {
      // update the campaign
      Log::info('Update campaign called: ');
      $campaign = Campaign::updateOrCreate(
        ['id' => $ID],
        ['campaign_name' => $request->campaign_name, 
         'company_id' => $cid,
         'campaign_start' => $request->campaign_start,  
         'campaign_end' => $request->campaign_end,  
         ]        
      );

      // campaign updated
      return response()->json('Updated');
    } 
    else 
    {
      Log::info('Create campaign called: ');
      //Log::info("$request");
      //Log::info($request);
      $campaign = Campaign::updateOrCreate(        
        ['campaign_name' => $request->campaign_name, 
        'company_id' => $cid,
        'campaign_start' => $request->campaign_start,  
        'campaign_end' => $request->campaign_end,  ]
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
  {
    Log::info('edit campaign called: ');
    $where = ['id' => $id];

    Log::info('edit campaign called with id: ' . $id);
    
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
