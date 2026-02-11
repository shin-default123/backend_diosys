<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Baptism;
use App\Models\Confirmation;
use App\Models\Marriage;
use App\Models\Death;

class PublicSearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'year' => 'required|integer|digits:4', 
            'location' => 'nullable|string',
            
        ]);

        $type = $request->type;
        $year = $request->year;
        $location = $request->location;

        $query = null;

        switch ($type) {
            case 'baptism':
            case 'confirmation':
            case 'death':
                // Validate Individual Name Inputs
                if (!$request->last_name || !$request->first_name) {
                    return response()->json(['message' => 'First and Last name are required.'], 422);
                }

                $model = $type === 'baptism' ? Baptism::query() : 
                        ($type === 'confirmation' ? Confirmation::query() : Death::query());
                
                $query = $model;

                $query->where('name', 'like', "%{$request->last_name}%")
                      ->where('name', 'like', "%{$request->first_name}%");
                break;

            case 'marriage':
                if (!$request->groom_last || !$request->bride_last) {
                    return response()->json(['message' => 'Groom and Bride surnames are required.'], 422);
                }

                $query = Marriage::query();

                $query->where('husband_name', 'like', "%{$request->groom_last}%");
                if($request->groom_first) {
                    $query->where('husband_name', 'like', "%{$request->groom_first}%");
                }

                $query->where('wife_name', 'like', "%{$request->bride_last}%");
                if($request->bride_first) {
                    $query->where('wife_name', 'like', "%{$request->bride_first}%");
                }
                break;

            default:
                return response()->json([], 200);
        }

        $query->whereYear('date', $year);

        if ($location) {
            $query->where('place', 'like', "%{$location}%");
        }

        $results = $query->select('id', 'date', 'place')->get();
        
        $formattedResults = $results->map(function ($record) use ($type) {
            $display_name = "";
            
            if ($type === 'marriage') {
                $fullRecord = Marriage::find($record->id);
                $display_name = $fullRecord->husband_name . " & " . $fullRecord->wife_name;
            } elseif ($type === 'baptism' || $type === 'confirmation' || $type === 'death') {
                $modelClass = $type === 'baptism' ? Baptism::class : 
                              ($type === 'confirmation' ? Confirmation::class : Death::class);
                $fullRecord = $modelClass::find($record->id);
                $display_name = $fullRecord->name;
            }

            return [
                'id' => $record->id,
                'date' => $record->date,
                'display_name' => $display_name,
                'place' => $record->place,
                'type' => ucfirst($type),
                'status' => 'Verified Available'
            ];
        });

        return response()->json($formattedResults);
    }
}