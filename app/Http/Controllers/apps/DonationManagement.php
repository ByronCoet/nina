<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DonationManagement extends Controller
{

  /**
   * Redirect to donation-management view.
   *
   */
  public function DonationManagement()
  {
    $companies = Company::all();
    $donations = Donation::all();
    $campaigns = Donation::all();
    $roles = Role::all();
    $donationsCount = $donations->count();
    Log::info('campaign list called');        

    return view('content.laravel.donation-management', [
      'totalDonations' => $donationsCount,    
      'donations' => $donations,
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
      4 => 'user_name',
      5 => 'user_surname',
      6 => 'donated',
      7 => 'converted',
      8 => 'supported',
      9 => 'edate',
    ];

    Log::info('donation index called');        

    $search = [];

    $totalData = Donation::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    Log::info('Order: ' . $order);     

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
        
        $query = Donation::offset($start)
          ->limit($limit)           
          ->join('companies', 'donations.company_id', '=', 'companies.id')
          ->join('campaigns', 'donations.campaign_id', '=', 'campaigns.id')
          ->join('users', 'donations.user_id', '=', 'users.id')
          ->select('donations.*', 'companies.company_name', 'campaigns.campaign_name' )
          ->orderBy('company_name', $dir);

          if (!empty($comp_search)) {
            $query->where('company_name', 'LIKE', "%{$comp_search}%");
          }
        $donations = $query->get();     
      }
      else{
        Log::info('1');
        $donations =Donation::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      }
    } else {
      $search = $request->input('search.value');
      if ($order == "donation")
        { 
          Log::info('3');
          $query = Donation::where(function ($q) use ($search) {
            $q->where('donation_name', 'LIKE', "%{$search}%");
            $q->orWhere('company_name', 'LIKE', "%{$search}%");
            $q->orWhere('donations.id', 'LIKE', "%{$search}%");  
          })
          ->offset($start)
          ->limit($limit)          
          ->join('companies', 'donations.company_id', '=', 'companies.id')
          ->select('donations.*', 'companies.company_name' )
          ->orderBy('donation_name', $dir);

          if (!empty($comp_search)) {
            $query->where('company_name', 'LIKE', "%{$comp_search}%");
          }

          $donations = $query->get();          
        }
        else
        {
          Log::info('4');
          $query = Donation::where(function ($q) use ($search) {
                $q->where('donation_name', 'LIKE', "%{$search}%");
                $q->orWhere('company_name', 'LIKE', "%{$search}%");
                $q->orWhere('donations.id', 'LIKE', "%{$search}%");  
              })
            ->offset($start)
            ->limit($limit)
            ->join('companies', 'donations.company_id', '=', 'companies.id')
            ->select('donations.*', 'companies.company_name' )
            ->orderBy($order, $dir);

            if (!empty($comp_search)) {
              $query->where('company_name', '=', "{$comp_search}");
            }

            $donations = $query->get();
        }

      Log::info('5');

      $query = Donation::where(function ($q) use ($search) {
              $q->where('donation_name', 'LIKE', "%{$search}%");
              $q->orWhere('company_name', 'LIKE', "%{$search}%");
              $q->orWhere('donations.id', 'LIKE', "%{$search}%");  
            })
            ->join('companies', 'donations.company_id', '=', 'companies.id')
            ->select('donations.*', 'companies.company_name' );
            if (!empty($comp_search)) {
              $query->where('company_name', '=', "{$comp_search}");
            }

        $totalFiltered = $query->count();
    }

    $data = [];

    if (!empty($donations)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      Log::info('6');

      foreach ($donations as $d) {
        // Log::info('c id: ' . $c);
        $nestedData['donation_id'] = $d->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['company_name'] = $d->company->company_name;
        $nestedData['campaign_name'] = $d->campaign->campaign_name;
        $nestedData['user_name'] = $d->user->name;
        $nestedData['user_surname'] = $d->user->surname;
        $nestedData['donated'] = $d->donated;
        $nestedData['converted'] = $d->converted;
        $nestedData['supported'] = $d->supported;
        $nestedData['edate'] = $d->event_date;
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

    Log::info('Store donation called: ');

    $ID = $request->id;

    if ($ID) 
    {
      // update the value
      Log::info('Update donation called: ');

      $edate = $request->input('event_date'); 
      $don = $request->input('donate'); 
      $conv = $request->input('convert'); 
      $supp = $request->input('support'); 

      $donation = Donation::find($ID);      
      $donation->update(
        [
         'donated'        => $don == 'on' ? 1 : 0,
         'converted'      => $conv == 'on' ? 1 : 0,
         'supported'      => $supp == 'on' ? 1 : 0,
         'event_date'     => $edate
         ]        
      );

      // donation updated
      return response()->json('Updated');
    } 
    else 
    {
      Log::info('Create donation called: ');


      $donation = Donation::updateOrCreate(        
        ['donation_name' => $request->donation_name, 
        'company_id' => $request->company_id,
        
        'donated'        => $don == 'on' ? 1 : 0,
        'converted'      => $conv == 'on' ? 1 : 0,
        'supported'      => $supp == 'on' ? 1 : 0,
        'event_date'     => $edate
        ]
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
    Log::info('edit donation called: ');
    $where = ['id' => $id];

    Log::info('edit donation called with id: ' . $id);
    
    $donations = Donation::where($where)->first();
    $roles = Role::all();
    $companies = Company::all();

    return response()->json(['donations' => $donations, 'companies' => $companies, 'roles' => $roles]);
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
    $c = Donation::where('id', $id)->delete();
  }
}
