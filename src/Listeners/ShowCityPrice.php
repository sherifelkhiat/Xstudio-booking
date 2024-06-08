<?php

namespace Webkul\Xbooking\Listeners;

use Illuminate\Support\Facades\Log;

class ShowCityPrice
{
    public function handle($event)
    {
        echo view('xbooking::xprice');
    }
}
