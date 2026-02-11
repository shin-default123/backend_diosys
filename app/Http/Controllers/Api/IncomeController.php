<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use Illuminate\Support\Facades\DB; 

class IncomeController extends Controller
{
    public function index()
    {
        // Return latest incomes first
        return response()->json(Income::orderBy('date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'source' => 'required',
            'amount' => 'required|numeric',
        ]);

        $income = Income::create($request->all());
        return response()->json($income, 201);
    }

    public function destroy($id)
    {
        Income::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    // Get Total Income
    public function total()
    {
        $total = Income::sum('amount');
        return response()->json(['total' => $total]);
    }
}