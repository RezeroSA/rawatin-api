<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterOfficerRequest;
use Illuminate\Http\Request;
use App\Models\Officer;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
    public function getAllOfficers()
    {
        $officers = Officer::all();

        if ($officers) {
            return response([
                'status' => true,
                'message' => 'Officers fetched successfully',
                'data' => $officers
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Officers not found',
                'data' => null
            ], 200);
        }
    }

    public function registerOfficer(RegisterOfficerRequest $request)
    {
        $request->validated();

        $officer = Officer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'bday' => $request->bday,
            'pin' => Hash::make($request->pin),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($officer) {
            return response([
                'status' => true,
                'message' => 'Officer created successfully',
                'data' => $officer
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Officer not created successfully',
                'data' => null
            ], 200);
        }
    }
}
