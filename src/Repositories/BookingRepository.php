<?php

namespace Webkul\Xbooking\Repositories;

use Webkul\Core\Eloquent\Repository;

class BookingRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Xbooking\Contracts\Booking';
    }
}