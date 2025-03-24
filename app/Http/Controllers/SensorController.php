<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $sensorId = $request->query('sensor_id');

        Log::info('REQUEST: ', [
            'query' => $request->query()
        ]);

        $parameter = collect(['T', 'P', 'v'])->first(fn($p) => $request->has($p));
        $value = $parameter ? $request->query($parameter) : null;

        $validationData = [
            'sensor_id' => $sensorId,
            'parameter' => $parameter,
            'value' => $value,
        ];

        $validator = Validator::make($validationData, [
            'sensor_id' => 'required|integer',
            'parameter' => 'required|in:T,P,v',
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        SensorData::create([
            'sensor_id' => $sensorId,
            'parameter' => $parameter,
            'value' => $value,
        ]);

        return response()->json(['status' => 'success'],  201);
    }

    public function getData(Request $request): JsonResponse
    {
        $rules = [
            'sensor_id' => 'required|integer',
            'parameter' => 'required|in:T,P,v',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422); // 422 — Unprocessable Entity
        }

        $sensorId = $request->query('sensor_id');
        $parameter = $request->query('parameter');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $data = SensorData::where('sensor_id', $sensorId)
            ->where('parameter', $parameter)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($data);
    }
}
