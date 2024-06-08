<?php

namespace Webkul\Xbooking\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Xbooking\Http\Controllers\Controller;
use Webkul\Xbooking\Models\ExceptionDay;
use Illuminate\Support\Facades\Validator;

class ExceptionDayController extends Controller
{
    public function index()
    {
        $days = ExceptionDay::all();
        return view('xbooking::admin.exception_day.index', compact('days'));
    }

    public function create()
    {
        return view('xbooking::admin.exception_day.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ExceptionDay::create([
            'date' => $request->input('date'),
        ]);

        return redirect()->route('xbooking.exception_days.index')->with('success', 'Exception day created successfully');
    }

    public function edit($id)
    {
        $day = ExceptionDay::findOrFail($id);
        return view('xbooking::admin.exception_day.edit', compact('day'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $day = ExceptionDay::findOrFail($id);
        $day->update([
            'date' => $request->input('date'),
        ]);

        return redirect()->route('xbooking.exception_days.index')->with('success', 'Exception day updated successfully');
    }

    public function delete($id)
    {
        $day = ExceptionDay::findOrFail($id);
        $day->delete();

        return redirect()->route('xbooking.exception_days.index')->with('success', 'Exception day deleted successfully');
    }
}
