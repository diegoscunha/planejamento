<?php

namespace App\Http\Controllers;

use Log;
use App\Enum\OperationLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Show logs list.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $logs = \LogToDB::model()->orderBy('created_at', 'desc')->get();
        $breadcrumb = [
              'Home' => route('adm'),
              'Logs' => ''
        ];
        //dd($logs[0]->context);
        return view('adm.log.list-logs', ['breadcrumb' => $breadcrumb, 'logs' => $logs]);
    }
}
