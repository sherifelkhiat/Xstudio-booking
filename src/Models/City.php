<?php

namespace Webkul\Xbooking\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Xbooking\Contracts\City as CityContract;

class City extends Model implements CityContract
{
    protected $fillable = ['source_city', 'destination_city', 'duration', 'extra_cost'];
    protected $table ='cities';
}