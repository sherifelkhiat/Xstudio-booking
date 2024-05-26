<?php

namespace Webkul\Xbooking\Listeners;

class XbookingListener
{
    public function handle($event)
    {
        echo view('xbooking::xproduct');
    }
}
