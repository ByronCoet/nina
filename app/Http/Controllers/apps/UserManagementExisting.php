<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserManagementExisting extends Controller
{
  /**
   * Redirect to user-management view.
   *
   */
  public function UserManagementExisting()
  {
    $users = User::all();
    $companies = Company::all();
    $roles = Role::all();
    $userCount = $users->count();
    $verified = User::whereNotNull('email_verified_at')->get()->count();
    $notVerified = User::whereNull('email_verified_at')->get()->count();
    $usersUnique = $users->unique(['email']);
    $userDuplicates = $users->diff($usersUnique)->count();

    return view('content.laravel.user-management-existing-capture', [
      'totalUser' => $userCount,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'userDuplicates' => $userDuplicates,
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
      2 => 'name',
      3 => 'surname',
      4 => 'company',
      5 => 'mobile',
      6 => 'email',
      
    ];

    $search = [];

    $totalData = User::count();

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
        $users = User::offset($start)
          ->limit($limit)
          ->join('companies', 'users.company_id', '=', 'companies.id')
          ->orderBy('companies.company_name', $dir)
          ->get();     
      }
      else{
        $users = User::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      }
    } else {
      $search = $request->input('search.value');
      if ($order == "company")
        { 
          $users = User::where('users.id', 'LIKE', "%{$search}%")
          ->orWhere('users.name', 'LIKE', "%{$search}%")
          ->orWhere('users.surname', 'LIKE', "%{$search}%")
          ->orWhere('users.email', 'LIKE', "%{$search}%")
          ->orWhere('companies.company_name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->join('companies', 'users.company_id', '=', 'companies.id')
          ->orderBy('companies.company_name', $dir)          
          ->get();          
        }
        else
        {

          $users = User::where('users.id', 'LIKE', "%{$search}%")          
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('surname', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('company_name', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->join('companies', 'users.company_id', '=', 'companies.id')
            ->orderBy($order, $dir)
            ->get();
        }

      $totalFiltered = User::where('users.id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('surname', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->orWhere('companies.company_name', 'LIKE', "%{$search}%")
        ->join('companies', 'users.company_id', '=', 'companies.id')
        ->count();
    }

    $data = [];

    if (!empty($users)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($users as $user) {
        $nestedData['id'] = $user->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $user->name;
        $nestedData['surname'] = $user->surname;
        $nestedData['company'] = $user->company->company_name;
        $nestedData['mobile'] = $user->mobile;        
        $nestedData['email'] = $user->email;        
        

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

    Log::info('Store donation called: ' );

    $userID = $request->id;

    $user = User::where('id', $userID)->first();

    Log::info('User name is: ' . $user->surname);

    $edate = $request->input('eventdate'); 
    $don = $request->input('donate'); 
    $conv = $request->input('convert'); 
    $supp = $request->input('support'); 


    $donation = \App\Models\Donation::create(
      [
          'user_id'        => $user->id,
          'campaign_id'    => $this->site_settings->campaign_id,
          'company_id'     => $user->company->id,
          'event_date'     => $edate,
          'donated'        => $don == 'on' ? 1 : 0,
          'converted'      => $conv == 'on' ? 1 : 0,
          'supported'      => $supp == 'on' ? 1 : 0,
      ]
    );
    
    return response()->json('Created');    
    //  return response()->json(['message' => "already exits"], 422);
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
    $where = ['id' => $id];

    $users = User::where($where)->first();

    $companies = Company::all();
    $roles = Role::all();

    return response()->json(['users' => $users, 'companies' => $companies, 'roles' => $roles]);
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
    $users = User::where('id', $id)->delete();
  }
}
