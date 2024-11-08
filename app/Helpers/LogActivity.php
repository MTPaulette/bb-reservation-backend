<?php

namespace App\Helpers;

use Request;
use App\Models\Activity_log as LogActivityModel;


class LogActivity
{
    public static function addToLog($description)
    {
        $log = [];
        $log['description'] = $description;
        $log['url'] = Request::fullUrl();
        $log['method'] = Request::method();
        $log['ip'] = Request::ip();
        $log['agent'] = Request::header('user-agent');
        $log['user_id'] = auth()->user()? auth()->user()->id: null;
        LogActivityModel::create($log);
    }

}