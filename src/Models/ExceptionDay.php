<?php

namespace Webkul\Xbooking\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Xbooking\Contracts\ExceptionDay as ExceptionDayContract;

class ExceptionDay extends Model implements ExceptionDayContract
{
    protected $fillable = ['date'];
    protected $table ='exception_days';
}