<?php

namespace Webkul\Xbooking\Models;

use Webkul\Checkout\Cart as BaseCart;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart as MainCart;

class Cart extends BaseCart
{
    public function collectTotals(): self
    {
        parent::collectTotals();

        Log::info("Inside custom cart rule");
        $cart = MainCart::getCart();
        foreach($cart->items()->get() as $item) {
            Log::info("Inside checkout listener:" . json_encode($item));
            if(isset($item['additional']) && $item->type == 'xbooking') {
                Log::info("Inside checkout listener:" . data_get($item, 'additional.booking.city', 0));
                $cityPrice = explode(':', data_get($item, 'additional.booking.city', 0))[1]; 
                $cart->grand_total = $item->total + $cityPrice;
                $cart->sub_total = $item->sub_total + $cityPrice; 
                $cart->base_grand_total = $item->total + $cityPrice;
                $cart->base_sub_total = $item->sub_total + $cityPrice;  
                $cart->save();
            }
            
        }

        return $this;
    }
}

