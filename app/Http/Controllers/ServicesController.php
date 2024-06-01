<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddServicesRequest;
use App\Models\Services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function getServices()
    {
        $services = Services::all();
        return response([
            'status' => true,
            'message' => 'Services fetched successfully',
            'data' => $services
        ], 200);
    }

    public function addServices(AddServicesRequest $request)
    {
        $request->validated();

        $servicesData = [
            'name' => $request->name,
            'price' => $request->price
        ];

        $services = Services::create($servicesData);

        return response([
            'status' => true,
            'message' => 'Services added successfully',
            'data' => $services
        ], 200);
    }
}
