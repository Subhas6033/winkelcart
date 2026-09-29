<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminCmsPageController extends Controller
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

        $query = CmsPage::query()->orderByDesc('id');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $pages = $query->paginate(20)->appends($request->query());

        return view('admin.cms_pages.index', compact('pages'));
    }

    public function create()
    {
        $this->ensureAdminAccess();

        $page = new CmsPage();

        return view('admin.cms_pages.form', compact('page'));
    }

    public function store(Request $request)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'title' => 'required|string|max:190',
            'slug' => 'required|string|max:190|alpha_dash|unique:cms_pages,slug',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:190',
            'meta_description' => 'nullable|string|max:2000',
        ]);

        $page = CmsPage::create([
            'title' => $validated['title'],
            'slug' => strtolower($validated['slug']),
            'content' => $validated['content'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        AuditLog::record(
            'cms_page.created',
            'cms_page',
            $page->id,
            [],
            [
                'slug' => $page->slug,
                'title' => $page->title,
                'is_active' => $page->is_active,
            ]
        );

        return redirect()->route('admin.cms_pages.index')->with('success', 'CMS page created successfully.');
    }

    public function edit(CmsPage $cmsPage)
    {
        $this->ensureAdminAccess();

        $page = $cmsPage;

        return view('admin.cms_pages.form', compact('page'));
    }

    public function update(Request $request, CmsPage $cmsPage)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'title' => 'required|string|max:190',
            'slug' => [
                'required',
                'string',
                'max:190',
                'alpha_dash',
                Rule::unique('cms_pages', 'slug')->ignore($cmsPage->id),
            ],
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:190',
            'meta_description' => 'nullable|string|max:2000',
        ]);

        $oldValues = [
            'title' => $cmsPage->title,
            'slug' => $cmsPage->slug,
            'is_active' => $cmsPage->is_active,
        ];

        $cmsPage->update([
            'title' => $validated['title'],
            'slug' => strtolower($validated['slug']),
            'content' => $validated['content'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'is_active' => $request->has('is_active'),
            'updated_by' => Auth::id(),
        ]);

        AuditLog::record(
            'cms_page.updated',
            'cms_page',
            $cmsPage->id,
            $oldValues,
            [
                'title' => $cmsPage->title,
                'slug' => $cmsPage->slug,
                'is_active' => $cmsPage->is_active,
            ]
        );

        return redirect()->route('admin.cms_pages.index')->with('success', 'CMS page updated successfully.');
    }

    public function destroy(CmsPage $cmsPage)
    {
        $this->ensureAdminAccess();

        AuditLog::record(
            'cms_page.deleted',
            'cms_page',
            $cmsPage->id,
            [
                'title' => $cmsPage->title,
                'slug' => $cmsPage->slug,
                'is_active' => $cmsPage->is_active,
            ],
            []
        );

        $cmsPage->delete();

        return redirect()->route('admin.cms_pages.index')->with('success', 'CMS page deleted successfully.');
    }
}
