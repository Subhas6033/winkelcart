<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    private function ensureAdminAccess()
    {
        abort_unless(Auth::user() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Super Admin')), 403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->ensureAdminAccess();

        $query = AuditLog::with('user')->orderByDesc('id');

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('entity_id', 'like', '%' . $search . '%')
                    ->orWhere('old_values', 'like', '%' . $search . '%')
                    ->orWhere('new_values', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(40)->appends($request->query());
        $entityTypes = AuditLog::query()->select('entity_type')->distinct()->orderBy('entity_type')->pluck('entity_type');
        $actions = AuditLog::query()->select('action')->distinct()->orderBy('action')->pluck('action');

        return view('admin.audit_logs.index', compact('logs', 'entityTypes', 'actions'));
    }
}
