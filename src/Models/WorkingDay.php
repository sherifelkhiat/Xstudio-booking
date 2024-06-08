<?php

namespace Webkul\Xbooking\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Xbooking\Contracts\WorkingDay as WorkingDayContract;

class WorkingDay extends Model implements WorkingDayContract
{
    protected $fillable = ['days', 'from', 'to'];
    protected $table = 'working_days';
}