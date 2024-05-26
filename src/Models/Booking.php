<?php

namespace Webkul\Xbooking\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Xbooking\Contracts\Booking as BookingContract;

class Booking extends Model implements BookingContract
{
    protected $fillable = ['id', 'date', 'from', 'to', 'qty', 'order_id', 'order_item_id', 'product_id'];

    protected $table = 'booking';
}