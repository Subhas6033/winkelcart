<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactQueryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    private function ensureAdminAccess()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $roles = $user->roles->pluck('name');
        abort_unless($roles->contains('Admin') || $roles->contains('Super Admin'), 403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->ensureAdminAccess();

        $query = ContactSubmission::orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $queries = $query->paginate(25)->appends($request->query());

        return view('admin.contact_queries.index', compact('queries'));
    }

    public function updateStatus(Request $request, ContactSubmission $contactSubmission)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'status' => 'required|in:Pending,Solved',
        ]);

        $contactSubmission->status = $validated['status'];
        $contactSubmission->resolved_at = $validated['status'] === 'Solved' ? now() : null;
        $contactSubmission->save();

        return back()->with('success', 'Contact query status updated successfully.');
    }
}
