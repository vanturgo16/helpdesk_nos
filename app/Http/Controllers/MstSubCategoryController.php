<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstSubCategory;
use App\Models\MstCategory;

class MstSubCategoryController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $categories = MstCategory::get();
        $datas = MstSubCategory::select('mst_sub_category.*', 'mst_category.category')
            ->leftjoin('mst_category', 'mst_category.id', 'mst_sub_category.id_mst_category')
            ->orderBy('mst_sub_category.created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($categories) {
                    return view('subcategory.action', compact('data', 'categories'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master SubCategory');
        return view('subcategory.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mst_category' => 'required',
            'sub_category' => 'required',
            'sla' => 'required',
        ]);
        // Check Existing Data
        if (MstSubCategory::where('id_mst_category', $request->id_mst_category)->where('sub_category', $request->sub_category)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }

        DB::beginTransaction();
        try {
            $store = MstSubCategory::create([
                'id_mst_category' => $request->id_mst_category,
                'sub_category' => $request->sub_category,
                'sla' => $request->sla,
                'is_active' => 1
            ]);

            // Audit Log
            $this->auditLogs('Store New SubCategory ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_add'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_add')]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mst_category' => 'required',
            'sub_category' => 'required',
            'sla' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(MstSubCategory::where('id_mst_category', $request->id_mst_category)->where('sub_category', $request->sub_category)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => __('messages.fail_duplicate')]);
        }
        // Check With Data Before
        $dataBefore = MstSubCategory::where('id', $id)->first();
        $dataBefore->id_mst_category = $request->id_mst_category;
        $dataBefore->sub_category = $request->sub_category;
        $dataBefore->sla = $request->sla;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstSubCategory::where('id', $id)->update([
                    'id_mst_category' => $request->id_mst_category,
                    'sub_category' => $request->sub_category,
                    'sla' => $request->sla,
                ]);

                // Audit Log
                $this->auditLogs('Update Selected SubCategory ID: ' . $id);
                DB::commit();
                return redirect()->back()->with('success', __('messages.success_update'));
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => __('messages.fail_update')]);
            }
        } else {
            return redirect()->back()->with(['info' => __('messages.same_data')]);
        }
    }

    public function enable($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            MstSubCategory::where('id', $id)->update(['is_active' => 1]);
            $nameValue = MstSubCategory::where('id', $id)->first()->sub_category;

            // Audit Log
            $this->auditLogs('Enable Selected SubCategory ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_activate') . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_activate') . $nameValue . '!']);
        }
    }

    public function disable($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            MstSubCategory::where('id', $id)->update(['is_active' => 0]);
            $nameValue = MstSubCategory::where('id', $id)->first()->sub_category;

            // Audit Log
            $this->auditLogs('Disable Selected SubCategory ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.success_deactivate') . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => __('messages.fail_deactivate') . $nameValue . '!']);
        }
    }

    // AJAX
    public function getSubcategory($id)
    {
        $datas = MstSubCategory::where('id_mst_category', $id)->where('is_active', 1)->get();
        if ($datas) {
            return response()->json(['success' => true, 'data' => [$datas]]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    public function getSLA($id)
    {
        $datas = MstSubCategory::where('id', $id)->first();
        if ($datas) {
            return response()->json(['success' => true, 'data' => [$datas]]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
