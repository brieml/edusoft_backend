<?php

namespace App\Http\Controllers\dashboard\city;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Department;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cities = City::with('department')->get();

        return response()->json([
            'data' => $cities,
            'message' => 'Cities successfully recovered'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($departmentId)
    {
        $cities = City::select('cities.id', 'cities.name', 'departments.name as department_name')
            ->join('departments', 'cities.department_id', '=', 'departments.id')
            ->where('cities.department_id', '=', $departmentId)->get();

        return response()->json([
            'data' => $cities,
            'message' => 'City successfully recovered'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
