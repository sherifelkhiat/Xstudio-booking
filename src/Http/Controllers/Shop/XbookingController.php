<?php

namespace Webkul\Xbooking\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class XbookingController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('xbooking::shop.index');
    }

    public function getDates()
    {
        $dates = [
            '2024-06-01', '2024-06-02', '2024-06-03', '2024-06-04', '2024-06-05'
        ];

        return response()->json($dates);
    }

    public function getTimes($date)
    {
        $times = [
            '2024-06-01' => ['10:00', '11:00', '12:00'],
            '2024-06-02' => ['13:00', '14:00', '15:00'],
            '2024-06-03' => ['16:00', '17:00', '18:00'],
            '2024-06-04' => ['10:00', '11:00', '12:00'],
            '2024-06-05' => ['13:00', '14:00', '15:00']
        ];

        return response()->json($times[$date] ?? []);
    }
}
