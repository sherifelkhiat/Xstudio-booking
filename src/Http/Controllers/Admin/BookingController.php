<?php

namespace Webkul\Xbooking\Http\Controllers\Admin;

use Webkul\Xbooking\Http\Controllers\Controller;
use Webkul\Xbooking\Models\Booking; // Adjust namespace if needed

class BookingController extends Controller
{
    // Index page showing all bookings
    public function index()
    {
        $bookings = Booking::all();
        return view('xbooking::admin.booking.index', compact('bookings'));
    }
}
