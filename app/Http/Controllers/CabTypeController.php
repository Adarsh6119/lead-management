<?php

namespace App\Http\Controllers;

use App\Models\CabType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabTypeController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Admin access required.');
        }

        $cabTypes = CabType::all();
        return view('cab_types.index', compact('cabTypes'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Admin access required.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:cab_types,name',
        ]);

        CabType::create([
            'name' => trim($request->name),
            'is_active' => true,
        ]);

        return back()->with('success', 'Cab type added successfully!');
    }

    public function toggle($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Admin access required.');
        }

        $cabType = CabType::findOrFail($id);
        $cabType->update(['is_active' => !$cabType->is_active]);

        return back()->with('success', 'Cab type status updated!');
    }
}
