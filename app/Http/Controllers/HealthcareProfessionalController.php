<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HealthcareProfessional;

class HealthcareProfessionalController extends Controller
{
    public function index()
    {
        $healthcareProfessionals = HealthcareProfessional::select('id', 'name', 'specialty')->get();
        return response()->json($healthcareProfessionals);
    }
    
}
