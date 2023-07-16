<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Role;

class CompanyController extends Controller
{
    public function AllCompanies()
    {
        $companies = Company::all();      
        $roles = Role::all();      
        return response()->json(['companies' => $companies, 'roles' => $roles]);   
    }
}
