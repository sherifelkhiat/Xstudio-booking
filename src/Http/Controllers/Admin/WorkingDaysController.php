<?php

namespace Webkul\Xbooking\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Xbooking\Http\Controllers\Controller;
use Webkul\Xbooking\Models\WorkingDay;

class WorkingDaysController extends Controller
{

    public function index()
    {
        $workingDays = WorkingDay::all();
        return view('xbooking::admin.working_days.index', compact('workingDays'));
    }

    public function alldata(){
        return WorkingDay::all();
    }

    public function create()
    {
        return view('xbooking::admin.working_days.create');
    }


    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'days' => 'required',
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from',
        ]);

        // Create new working day
        WorkingDay::create($validatedData);

        return redirect()->route('xbooking.working_days.index')
            ->with('success', 'Working days created successfully');
    }

    public function edit($id)
    {
        $workingDay = WorkingDay::findOrFail($id);
        return view('xbooking::admin.working_days.edit', compact('workingDay'));
    }

    public function update(Request $request, $id)
    {
        // Validate input
        // $validatedData = $request->validate([
        //     'days' => 'required|array',
        //     'from' => 'required|date_format:H:i',
        //     'to' => 'required|date_format:H:i|after:from',
        // ]);

        // Find the working day by ID
        $workingDay = WorkingDay::findOrFail($id);

        // Update the working day
        $workingDay->update($request->all());

        return redirect()->route('xbooking.working_days.index')
            ->with('success', 'Working days updated successfully');
    }

    public function delete($id)
    {
        $workingDay = WorkingDay::findOrFail($id);
        $workingDay->delete();
        return redirect()->route('xbooking.working_days.index')
            ->with('success', 'Working day deleted successfully');
    }
}
