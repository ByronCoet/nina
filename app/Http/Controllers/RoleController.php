<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function AllRoles()
    {

        $r = Role::all();      

        return response()->json(['roles' => $role]);
   
        /*
        if ($data) {
            return response()->json([                
                'code' => 200,
                'data' => $data,
            ]);
        }

        */
    }
}
