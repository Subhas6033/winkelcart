<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class SubCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index']]);
        $this->middleware('permission:category-create|category-edit', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    /**
     * List all sub-categories grouped by main category.
     */
    public function index(Request $request)
    {
        // Sub-categories are managed from the Categories page (accordion view)
        return redirect()->route('categories.index');
    }

    /**
     * Show create form. Optionally pre-select a main category via ?category_id=X
     */
    public function create(Request $request)
    {
        $selectedCategoryId = $request->input('category_id');
        return view('subcategories.create', compact('selectedCategoryId'));
    }

    /**
     * Store or update a sub-category.
     */
    public function store(Request $request)
    {
        $subCategoryId = $request->input('sub_category_id');

        $rules = [
            'name'        => 'required|max:255',
            'category_id' => 'required|in:1,2,3,4,5',
        ];

        $messages = [
            'name.required'        => 'Sub-category name is required',
            'category_id.required' => 'Please select a main category',
            'category_id.in'       => 'Invalid main category',
        ];

        $this->validate($request, $rules, $messages);

        $loggedUserId = loggedin_admin('id');
        $input = $request->all();

        $data = [
            'name'        => $input['name'],
            'category_id' => $input['category_id'],
        ];

        if (empty($subCategoryId)) {
            $data['status']     = 0;
            $data['deleted']    = 0;
            $data['created_by'] = $loggedUserId;
            $data['created_at'] = current_datetime();
            SubCategory::create($data);
            $msg = 'created';
        } else {
            $data['updated_by'] = $loggedUserId;
            $data['updated_at'] = current_datetime();
            SubCategory::where('id', $subCategoryId)->update($data);
            $msg = 'updated';
        }

        return redirect()->route('sub_categories.index')
            ->with('success', 'Sub-category ' . $msg . ' successfully');
    }

    public function edit($id)
    {
        $subCategory     = SubCategory::findOrFail($id);
        $selectedCategoryId = $subCategory->category_id;
        return view('subcategories.create', compact('subCategory', 'selectedCategoryId'));
    }

    public function destroy($id)
    {
        SubCategory::where('id', $id)->update([
            'deleted'    => 1,
            'updated_by' => loggedin_admin('id'),
            'updated_at' => current_datetime(),
        ]);

        return redirect()->route('sub_categories.index')
            ->with('success', 'Sub-category deleted successfully');
    }

    /**
     * AJAX: return sub-categories for a given main category_id as JSON.
     */
    public function getByCategoryId($categoryId)
    {
        $subs = SubCategory::where('deleted', 0)
            ->where('status', 0)
            ->where('category_id', $categoryId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($subs);
    }

    /**
     * Toggle status via AJAX (mirrors category_status_change).
     */
    public function statusChange(Request $request)
    {
        if (!empty($_POST['fieldid'])) {
            $sub = SubCategory::find($_POST['fieldid']);
            if ($sub) {
                $newStatus = ($sub->status == 0) ? 1 : 0;
                SubCategory::where('id', $_POST['fieldid'])->update([
                    'status'     => $newStatus,
                    'updated_by' => loggedin_admin('id'),
                    'updated_at' => current_datetime(),
                ]);
                echo $newStatus;
            }
        }
    }
}
