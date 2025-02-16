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
        $categories = MstCategory::where('is_active', 1)->get();
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
        ]);
        // Check Existing Data
        if (MstSubCategory::where('id_mst_category', $request->id_mst_category)->where('sub_category', $request->sub_category)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }

        DB::beginTransaction();
        try {
            $store = MstSubCategory::create([
                'id_mst_category' => $request->id_mst_category,
                'sub_category' => $request->sub_category,
                'is_active' => 1
            ]);

            // Audit Log
            $this->auditLogs('Store New SubCategory ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', 'Success, New SubCategory Added');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Add New SubCategory!']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_mst_category' => 'required',
            'sub_category' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(MstSubCategory::where('id_mst_category', $request->id_mst_category)->where('sub_category', $request->sub_category)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }
        // Check With Data Before
        $dataBefore = MstSubCategory::where('id', $id)->first();
        $dataBefore->id_mst_category = $request->id_mst_category;
        $dataBefore->sub_category = $request->sub_category;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstSubCategory::where('id', $id)->update([
                    'id_mst_category' => $request->id_mst_category,
                    'sub_category' => $request->sub_category,
                ]);

                // Audit Log
                $this->auditLogs('Update Selected SubCategory ID: ' . $id);
                DB::commit();
                return redirect()->back()->with('success', 'Success, Selected SubCategory Updated');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => 'Failed to Update SubCategory Dropdown!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function enable($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            MstSubCategory::where('id', $id)->update(['is_active' => 1]);
            $nameValue = MstSubCategory::where('id', $id)->first()->category;

            // Audit Log
            $this->auditLogs('Enable Selected SubCategory ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate : ' . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate : ' . $nameValue . '!']);
        }
    }

    public function disable($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            MstSubCategory::where('id', $id)->update(['is_active' => 0]);
            $nameValue = MstSubCategory::where('id', $id)->first()->category;

            // Audit Log
            $this->auditLogs('Disable Selected SubCategory ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate : ' . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate : ' . $nameValue . '!']);
        }
    }
}
