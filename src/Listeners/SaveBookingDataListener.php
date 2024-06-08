<?php

namespace Webkul\Xbooking\Listeners;

use Webkul\Xbooking\Models\Booking;
use Illuminate\Support\Facades\Log;
use Webkul\Xbooking\Repositories\BookingRepository;

class SaveBookingDataListener 
{
    public function __construct(protected BookingRepository $bookingRepository)
    {
    }

    public function handle($event)
    {
        Log::info(json_encode($event->items()->get()[0]['additional']) );
        $this->bookingRepository->create(['event' => $event]);
    }
}
