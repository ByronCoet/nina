<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Point;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PointManagement extends Controller
{
  /**
   * Redirect to point-management view.
   *
   */
  public function PointManagement()
  {
    
    $points = Point::orderBy('id')->get();
    
    $pointCount = $points->count();
    Log::info('Point list called');        

    return view('content.laravel.point-management', [
      'totalPoint' => $pointCount,    
      'points' => $points
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
      2 => 'point_name', 
      3 => 'points'
    ];

    Log::info('Point index called');        

    $search = [];

    $totalData = Point::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    
    // Log::info('Order: ' . $order);
    $dir = $request->input('order.0.dir');

    // Log::info('Dir: ' . $dir);        

    if (empty($request->input('search.value'))) {
      if ($order == "point_name")
      { 
        $points = Point::offset($start)
          ->limit($limit)          
          ->orderBy('point_name', $dir)
          ->get();     
      }
      else{
        $points =Point::offset($start)
          ->limit($limit)
          ->orderBy($order, $dir)
          ->get();
      }
    } else {
      $search = $request->input('search.value');
      if ($order == "point")
        { 
          $points = Point::where('id', 'LIKE', "%{$search}%")          
          ->orWhere('point_name', 'LIKE', "%{$search}%")
          ->offset($start)
          ->limit($limit)          
          ->orderBy('point_name', $dir)          
          ->get();          
        }
        else
        {
          $points = Point::where('id', 'LIKE', "%{$search}%")          
            ->orWhere('point_name', 'LIKE', "%{$search}%")            
            ->offset($start)
            ->limit($limit)            
            ->orderBy($order, $dir)
            ->get();
        }

      $totalFiltered = Point::where('id', 'LIKE', "%{$search}%")
        ->orWhere('point_name', 'LIKE', "%{$search}%")        
        ->count();
    }

    $data = [];

    if (!empty($points)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($points as $c) {
        $nestedData['id'] = $c->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['point_name'] = $c->point_name;
        $nestedData['points'] = strval($c->points);
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

    Log::info('Store point called: ');


    $ID = $request->id;

    if ($ID) {
      // update the value
      Log::info('Update point called: ');
      $point = Point::updateOrCreate(
        ['id' => $ID],
        ['point_name' => $request->pointname],
        ['points' => $request->points]
      );

      // point updated
      return response()->json('Updated');
    } else {
      Log::info('Create point called: ');
      $point = Point::updateOrCreate(        
        ['point_name' => $request->pointname],
        ['points' => $request->points]
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
  {Log::info('edit point called: ');
    $where = ['id' => $id];    
    $points = Point::where($where)->first();
    return response()->json(['points' => $points]);
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
    $c = Point::where('id', $id)->delete();
  }
}
