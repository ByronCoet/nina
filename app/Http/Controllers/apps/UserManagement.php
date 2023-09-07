<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Controller
{
  /**
   * Redirect to user-management view.
   *
   */
  public function UserManagement()
  {

    $user = Auth::user();
    if ($user->role == "receptionist" || $user->role == "companyadmin" )
    {
       $users = User::where(['company_id' => $user->company->id])->get();
    }
    else
    {
       $users = User::all();
    }

    $companies = Company::all();
    $roles = Role::all();
    $userCount = $users->count();
    $verified = User::whereNotNull('email_verified_at')->get()->count();
    $notVerified = User::whereNull('email_verified_at')->get()->count();
    $usersUnique = $users->unique(['email']);
    $userDuplicates = $users->diff($usersUnique)->count();

    return view('content.laravel.user-management', [
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
      6 => 'role',
    ];

    $search = [];

    $user = Auth::user();

    $totalData = User::where(['users.company_id' => $user->company->id])->count();

    $comp_search = $request->get('extra_search');

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
        $query = User::offset($start)
          ->limit($limit)
          ->join('companies', 'users.company_id', '=', 'companies.id')
          ->select('users.*', 'companies.company_name' )
          ->orderBy('companies.company_name', $dir);

        if (!empty($comp_search)) {
          $query->where('companies.company_name', 'LIKE', "%{$comp_search}%");
        }

        if ($user->role == "receptionist" || $user->role == "companyadmin" )
        {
          $query->where(['users.company_id' => $user->company->id ]);
        }

        if ($user->role == "receptionist" || $user->role == "companyadmin" )
        {
          $query->where(['users.company_id' => $user->company->id ]);
        }
        
        $users = $query->get();     
      }
      else{
        // Log::info('straight');        
        $query = User::offset($start)
          ->limit($limit)
          ->join('companies', 'users.company_id', '=', 'companies.id')
          ->select('users.*', 'companies.company_name' )
          ->orderBy($order, $dir);          

        if (!empty($comp_search)) {
          $query->where('companies.company_name', 'LIKE', "%{$comp_search}%");
        }
        
        if ($user->role == "receptionist" || $user->role == "companyadmin" )
        {
          $query->where(['users.company_id' => $user->company->id ]);
        }

        $users = $query->get();     
      }
    } else {
      $search = $request->input('search.value');
      if ($order == "company")
        { 
          $query = User::where('users.id', 'LIKE', "%{$search}%")
          ->orWhere('users.name', 'LIKE', "%{$search}%")
          ->orWhere('users.surname', 'LIKE', "%{$search}%")
          ->orWhere('users.email', 'LIKE', "%{$search}%")
          ->orWhere('companies.company_name', 'LIKE', "%{$search}%")
          ->orWhere('role', 'LIKE', "%{$search}%")
          ->orWhere('mobile', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)
          ->join('companies', 'users.company_id', '=', 'companies.id')
          ->select('users.*', 'companies.company_name' )
          ->orderBy('companies.company_name', $dir);

          if (!empty($comp_search)) {
            $query->where('companies.company_name', 'LIKE', "%{$comp_search}%");
          }

          if ($user->role == "receptionist" || $user->role == "companyadmin" )
          {
            $query->where(['users.company_id' => $user->company->id ]);
          }

          $users = $query->get();                    
        }
        else
        {

          $query = User::where('users.id', 'LIKE', "%{$search}%")          
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('surname', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('company_name', 'LIKE', "%{$search}%")
            ->orWhere('role', 'LIKE', "%{$search}%")
            ->orWhere('mobile', 'LIKE', "%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->join('companies', 'users.company_id', '=', 'companies.id')
            ->select('users.*', 'companies.company_name' )
            ->orderBy($order, $dir);

            if (!empty($comp_search)) {
              $query->where('companies.company_name', 'LIKE', "%{$comp_search}%");
            }

            if ($user->role == "receptionist" || $user->role == "companyadmin" )
            {
              $query->where(['users.company_id' => $user->company->id ]);
            }
  
            $users = $query->get();                    
        }

      $query = User::where('users.id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('surname', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->orWhere('role', 'LIKE', "%{$search}%")
        ->orWhere('companies.company_name', 'LIKE', "%{$search}%")
        ->orWhere('mobile', 'LIKE', "%{$search}%")
        ->join('companies', 'users.company_id', '=', 'companies.id');

        if (!empty($comp_search)) {
          $query->where('companies.company_name', 'LIKE', "%{$comp_search}%");
        }

        if ($user->role == "receptionist" || $user->role == "companyadmin" )
        {
          $query->where(['users.company_id' => $user->company->id ]);
        }

        $totalFiltered = $query->count();
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
        $nestedData['role'] = $user->role;
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
    Log::info('Store called: ' );

    $userID = $request->id;

    if ($userID) {
      // update the value
      $users = User::updateOrCreate(
        ['id' => $userID],
        [
          'name' => $request->name, 
          'surname' => $request->surname, 
          'email' => $request->email, 
          'mobile' => $request->mobile, 
          'role' => $request->role,
          'company_id' => $request->company_id]
      );

      // user updated
      return response()->json('Updated');
    } else {
      // create new one if email is unique
      $userEmail = User::where('email', $request->email)->first();

      if (empty($userEmail)) {

        $users = User::updateOrCreate(
          ['id' => $userID],
          ['name' => $request->name,
          'surname' => $request->surname,
          'company_id' => $request->company_id,
          'email' => $request->email,
          'mobile' => $request->mobile,
          'role' => $request->role,
          'password' => bcrypt(Str::random(10))]
        );

        // user created
        return response()->json('Created');
      } else {
        // user already exist
        return response()->json(['message' => "already exits"], 422);
      }
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
