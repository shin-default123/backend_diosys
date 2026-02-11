<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Baptism;
use App\Models\Confirmation;
use App\Models\Marriage;
use App\Models\Death;

class ParishRecordController extends Controller
{
    private function getModel($type) {
        return match($type) {
            'baptism' => new Baptism(),
            'confirmation' => new Confirmation(),
            'marriage' => new Marriage(),
            'death' => new Death(),
            default => null,
        };
    }

    public function index($type)
    {
        $model = $this->getModel($type);
        if (!$model) return response()->json(['error' => 'Invalid type'], 400);
        
        return response()->json($model->latest()->get());
    }

    public function store(Request $request, $type)
    {
        $model = $this->getModel($type);
        if (!$model) return response()->json(['error' => 'Invalid type'], 400);

        $record = $model->create($request->all());
        return response()->json($record, 201);
    }

    public function update(Request $request, $type, $id)
    {
        $model = $this->getModel($type);
        if (!$model) return response()->json(['error' => 'Invalid type'], 400);

        $record = $model->findOrFail($id);
        $record->update($request->all());
        return response()->json($record);
    }

    public function destroy($type, $id)
    {
        $model = $this->getModel($type);
        if (!$model) return response()->json(['error' => 'Invalid type'], 400);

        $model->findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}