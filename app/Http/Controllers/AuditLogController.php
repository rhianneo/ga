<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::latest()->paginate(20);

        return view('audit.index', compact('logs'));
    }
}
