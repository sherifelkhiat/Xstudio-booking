<?php

namespace Webkul\Xbooking\Listeners;

use Webkul\Xbooking\Models\Booking;
use Illuminate\Support\Facades\Log;
use Webkul\Xbooking\Repositories\BookingRepository;
use Webkul\Product\Repositories\ProductRepository;

class SaveBookingDataListener 
{
    public function __construct(
        protected BookingRepository $bookingRepository, 
        protected ProductRepository $productRepository
        )
    {
    }

    public function handle($event)
    {
        Log::info(json_encode($event));
        foreach($event->items()->get() as $item) {
            Log::info(json_encode($item->price));
            if(isset($item['additional'])) {
                Log::info(json_encode($this->productRepository->findOrFail($item->product_id)->duration));
                $product = $this->productRepository->findOrFail($item->product_id);
                $productDuration = ($product)? $product->duration : 0;
                $cityData = explode(':', data_get($item, 'additional.booking.city', 0));
                $cityId = (isset($cityData[0]))? $cityData[0]: null;
                 
                $cityDuration = (isset($cityData[2]))? $cityData[2]: 0;
                $stringDateFrom = data_get($item, 'additional.booking.from', 0);
                $stringDate = data_get($item, 'additional.booking.date', 0);

                $totalDuration = (int) $cityDuration + (int) $productDuration;
                $totalDuration = $totalDuration * 60;

                $startAvailableTimestamp = strtotime($stringDate .' '. $stringDateFrom );

                $to = $startAvailableTimestamp + $totalDuration;

                $to = date("H:i:s", $to);

                $this->bookingRepository->create([
                    'day' => $stringDate,
                    'from' => data_get($item, 'additional.booking.from', 0),
                    'to' => $to,
                    'city' => $cityId,
                    'product_id' => $item->product_id
                ]);   
            }
        }
    }
}
