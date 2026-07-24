<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $escaped = $search ? str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) : null;
        $actionFilter = $request->input('action');

        $logs = ActivityLog::with('user')
            ->when($escaped, function ($query, $escaped) {
                $query->where('description', 'like', "%{$escaped}%")
                    ->orWhere('model_type', 'like', "%{$escaped}%")
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$escaped}%"));
            })
            ->when($actionFilter, fn ($query, $actionFilter) => $query->where('action', $actionFilter))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('activity_logs.index', compact('logs', 'search', 'actionFilter'));
    }
}
