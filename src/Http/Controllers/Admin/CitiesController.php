<?php

namespace Webkul\Xbooking\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Xbooking\Http\Controllers\Controller;
use Webkul\Xbooking\Models\City;
// use Illuminate\Validation\Validator;

class CitiesController extends Controller
{

    public function index()
    {
        $cities = City::all();
        return view('xbooking::admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('xbooking::admin.cities.create');
    }


    public function store(Request $request)
    {
        // Validate input
        $validator = \Validator::make($request->all(), [
            'source_city' => 'required',
            'destination_city' => 'required',
            'duration' => 'required|integer|min:1',
            'extra_cost' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create city
        City::create([
            'source_city' => $request->input('source_city'),
            'destination_city' => $request->input('destination_city'),
            'duration' => $request->input('duration'),
            'extra_cost' => $request->input('extra_cost'),
        ]);

        return redirect()->route('xbooking.cities.index')->with('success', 'City created successfully');
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);
        return view('xbooking::admin.cities.edit', compact('city'));
    }

    public function update(Request $request, $id)
    {
        // Validate input
        $validator = \Validator::make($request->all(), [
            'source_city' => 'required',
            'destination_city' => 'required',
            'duration' => 'required|integer|min:1',
            'extra_cost' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update city
        $city = City::findOrFail($id);
        $city->update([
            'source_city' => $request->input('source_city'),
            'destination_city' => $request->input('destination_city'),
            'duration' => $request->input('duration'),
            'extra_cost' => $request->input('extra_cost'),
        ]);

        return redirect()->route('xbooking.cities.index')->with('success', 'City updated successfully');
    }

    public function delete($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        

        return redirect()->route('xbooking.cities.index')->with('success', 'City deleted successfully');
    }
}
