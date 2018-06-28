<?php

namespace App\Core\Utils;

use Carbon\Carbon;
use SimpleXMLElement;

class DateHelpers
{
    public static function parse($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}
