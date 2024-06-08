<?php

namespace Webkul\Xbooking\Listeners;

use Illuminate\Support\Facades\Log;

class CheckoutListener
{
    public function handle($event)
    {
        Log::info(json_encode($event));
        foreach($event->items()->get() as $item) {
            Log::info(json_encode($item->price));
            if(isset($item['additional'])) {
                Log::info(json_encode(data_get($item, 'additional.booking.city', 0)));
                $cityPrice = explode(':', data_get($item, 'additional.booking.city', 0))[1]; 
                $event->grand_total = $item->total + $cityPrice;
                $event->sub_total = $item->sub_total + $cityPrice; 
                $event->base_grand_total = $item->total + $cityPrice;
                $event->base_sub_total = $item->sub_total + $cityPrice; 
                $event->save();
                Log::info(json_encode($item->total));
            }
            
        }
    }
}
