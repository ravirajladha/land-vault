<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class LogController extends Controller
{
    public function actionLogsIndex(Request $request)
    {
        // Fetch filters from the request
        $startDate = $request->get('start_due_date');
        $endDate = $request->get('end_due_date');
        $modelType = $request->get('model_type');
        $actionType = $request->get('action_type');
        
        // Query the logs table
        $logsQuery = DB::table('log_changes')
            ->leftJoin('users', 'log_changes.user_id', '=', 'users.id') // Perform a left join with the users table
            ->select('log_changes.*', 'users.name as user_name');
    
        // Apply filters if they exist
        if ($startDate) {
            $logsQuery->whereDate('log_changes.created_at', '>=', $startDate);
        }
    
        if ($endDate) {
            $logsQuery->whereDate('log_changes.created_at', '<=', $endDate);
        }
    
        if ($modelType) {
            $logsQuery->where('log_changes.model_type', $modelType);
        }
    
        if ($actionType) {
            $logsQuery->where('log_changes.action', $actionType);
        }
    
        // Order by created_at and id
        $logs = $logsQuery->orderBy('log_changes.created_at', 'desc')
            ->orderBy('log_changes.id', 'desc')
            ->paginate(10);
    

             // Fetch unique model types and actions for the dropdowns
    $uniqueModelTypes = DB::table('log_changes')->select('model_type')->distinct()->get()->pluck('model_type');
    $uniqueActionTypes = DB::table('log_changes')->select('action')->distinct()->get()->pluck('action');

    // Existing logic for filtering logs
    // ...

    return view('pages.logs.action-logs', compact('logs', 'uniqueModelTypes', 'uniqueActionTypes'));


        // Pass the logs and filters to the view
    }
    

public function httpRequestLogs(Request $request)
{
    // Fetch filters from the request
    $startDate = $request->get('start_due_date');
    $endDate = $request->get('end_due_date');
    $userId = $request->get('user_id');
    $method = $request->get('method');
    
    // Query the http_request_logs table
    $logsQuery = DB::table('http_request_logs')
        ->leftJoin('users', 'http_request_logs.user_id', '=', 'users.id') // Join with users table
        ->select('http_request_logs.*', 'users.name as user_name'); // Select logs and user name
    
    // Apply filters if they exist
    if ($startDate) {
        $logsQuery->whereDate('http_request_logs.created_at', '>=', $startDate);
    }

    if ($endDate) {
        $logsQuery->whereDate('http_request_logs.created_at', '<=', $endDate);
    }

    if ($userId) {
        $logsQuery->where('http_request_logs.user_id', $userId);
    }

    if ($method) {
        $logsQuery->where('http_request_logs.method', $method);
    }

    // Order by created_at and id
    $logs = $logsQuery->orderBy('http_request_logs.created_at', 'desc')
        ->orderBy('http_request_logs.id', 'desc')
        ->paginate(10);

    // Fetch unique methods for filtering
    $uniqueMethods = DB::table('http_request_logs')->select('method')->distinct()->get()->pluck('method');
    // Fetch users for the dropdowns
    $users = DB::table('users')->select('id', 'name')->get();
    // Pass the logs and filter data to the view
    return view('pages.logs.http-request-logs', compact('logs', 'uniqueMethods','users'));
}

}
