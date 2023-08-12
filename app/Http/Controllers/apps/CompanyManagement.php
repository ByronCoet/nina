<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CompanyManagement extends Controller
{
  /**
   * Redirect to company-management view.
   *
   */
  public function CompanyManagement()
  {
    
    $companies = Company::all();
    $roles = Role::all();
    $companyCount = $companies->count();
    Log::info('Company list called');        

    return view('content.laravel.company-management', [
      'totalCompany' => $companyCount,    
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
    ];

    Log::info('Company index called');        

    $search = [];

    $totalData = Company::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    
    // Log::info('Order: ' . $order);
    $dir = $request->input('order.0.dir');

    // Log::info('Dir: ' . $dir);        

    if (empty($request->input('search.value'))) {
      if ($order == "company")
      { 
        $companies = Company::offset($start)
          ->limit($limit)          
          ->orderBy('company_name', $dir)
          ->get();     
      }
      else{
        $companies =Company::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      }
    } else {
      $search = $request->input('search.value');
      if ($order == "company")
        { 
          $companies = Company::where('id', 'LIKE', "%{$search}%")          
          ->orWhere('company_name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)          
          ->orderBy('company_name', $dir)          
          ->get();          
        }
        else
        {
          $companies = Company::where('id', 'LIKE', "%{$search}%")          
            ->orWhere('company_name', 'LIKE', "%{$search}%")            
            ->offset($start)
            ->limit($limit)            
            ->orderBy($order, $dir)
            ->get();
        }

      $totalFiltered = Company::where('id', 'LIKE', "%{$search}%")
        ->orWhere('company_name', 'LIKE', "%{$search}%")        
        ->count();
    }

    $data = [];

    if (!empty($companies)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($companies as $c) {
        $nestedData['id'] = $c->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['company_name'] = $c->company_name;
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

    Log::info('Store company called: ');


    $ID = $request->id;

    if ($ID) {
      // update the value
      Log::info('Update company called: ');
      $company = Company::updateOrCreate(
        ['id' => $ID],
        ['company_name' => $request->company_name]
      );

      // company updated
      return response()->json('Updated');
    } else {
      Log::info('Create company called: ');
      $company = Company::updateOrCreate(        
        ['company_name' => $request->company_name]
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
  {Log::info('edit company called: ');
    $where = ['id' => $id];
    
    $companies = Company::where($where)->first();
    $roles = Role::all();

    return response()->json(['companies' => $companies, 'roles' => $roles]);
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
    $c = Company::where('id', $id)->delete();
  }
}
