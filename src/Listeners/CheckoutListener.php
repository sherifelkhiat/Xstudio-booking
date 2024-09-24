<?php

namespace Webkul\Xbooking\Listeners;

use Illuminate\Support\Facades\Log;

class CheckoutListener
{
    public function handle($event)
    {
    
        foreach($event->items()->get() as $item) {
            Log::info("Inside checkout listener");
            if(isset($item['additional']) && data_get($item, 'additional.booking.city', 0)) {
                Log::info("Inside checkout listener:" . data_get($item, 'additional.booking.city', 0));
                $cityPrice = explode(':', data_get($item, 'additional.booking.city', 0))[1]; 
                $event->grand_total = $item->total + $cityPrice;
                $event->sub_total = $item->sub_total + $cityPrice; 
                $event->base_grand_total = $item->total + $cityPrice;
                $event->base_sub_total = $item->sub_total + $cityPrice; 
                $event->save();
                
            }
            
        }
    }
}
