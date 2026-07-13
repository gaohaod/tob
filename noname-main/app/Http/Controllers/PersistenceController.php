<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persistence;

class PersistenceController extends Controller
{
    public function getSortedValues(Request $request)
    {

        $request->validate([
            'key' => 'required|string',
            'placeId' => 'required|integer',
            'scope' => 'required|string',
            'pageSize' => 'sometimes|integer',
        ]);

        $key = $request->query('key');
        $placeId = $request->query('placeId');
        $scope = $request->query('scope');
        $limit = $request->query('pageSize', 0);

        $query = Persistence::where('type', 'sorted')
            ->where('placeId', $placeId)
            ->where('key', $key)
            ->where('scope', $scope);

        if ($limit > 0) {
            $query->limit($limit);
        }

        $results = $query->get();

        $entries = [];
        foreach ($results as $data) {
            $entries[] = [
                'Target' => $data->target,
                'Value' => $data->value,
            ];
        }

        return response()->json([
            'data' => [
                'Entries' => $entries,
            ]
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function getV2(Request $request)
    {

        $request->validate([
            'placeId' => 'required|integer',
            'scope' => 'required|string',
            'type' => 'required|string',
        ]);

        $placeId = $request->input('placeId');
        $scope = $request->input('scope');
        $type = $request->input('type');

        $input = $request->getContent();
        parse_str($input, $qkeys); 

        if (isset($qkeys['qkeys'][0]['key']) && isset($qkeys['qkeys'][0]['target'])) {
            $key = urldecode($qkeys['qkeys'][0]['key']);
            $target = urldecode($qkeys['qkeys'][0]['target']);

            $results = Persistence::where('placeId', $placeId)
                ->where('scope', $scope)
                ->where('type', $type)
                ->where('key', $key)
                ->where('target', $target)
                ->get(['value', 'scope', 'key', 'target']); 

            $values = $results->map(function ($data) {
                return [
                    'Value' => $data->value,
                    'Scope' => $data->scope,
                    'Key' => $data->key,
                    'Target' => $data->target,
                ];
            });

            return response()->json(['data' => $values], 200);
        }

        return response()->json(['error' => "a"], 400);
    }

    public function set(Request $request)
    {

        $request->validate([
            'value' => 'required|string',
            'key' => 'required|string',
            'placeId' => 'required|integer',
            'scope' => 'required|string',
            'type' => 'required|string',
            'target' => 'required|string',
        ]);

        $key = $request->query('key');
        $placeId = $request->query('placeId');
        $scope = $request->query('scope');
        $type = $request->query('type');
        $target = $request->query('target');
        $value = $request->input('value');

        $persistence = Persistence::where('placeId', $placeId)
            ->where('scope', $scope)
            ->where('type', $type)
            ->where('key', $key)
            ->where('target', $target)
            ->first();

        if ($persistence) {

            $persistence->value = $value;
            $persistence->save();
        } else {

            Persistence::create([
                'key' => $key,
                'placeId' => $placeId,
                'type' => $type,
                'scope' => $scope,
                'target' => $target,
                'value' => $value,
            ]);
        }

        return response()->json([
            'data' => [
                'Value' => $value,
                'Scope' => $scope,
                'Key' => $key,
                'Target' => $target,
            ]
        ], 200, [], JSON_NUMERIC_CHECK);
    }
}