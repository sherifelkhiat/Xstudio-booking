<?php

namespace Webkul\Xbooking\Listeners;

use Illuminate\Support\Facades\Log;

class XbookingListener
{
    public function handle($event)
    {
        echo view('xbooking::xproduct');
    }
}
