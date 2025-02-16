<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstCategory;

class MstCategoryController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstCategory::orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('category.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Category');
        return view('category.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
        ]);
        // Check Existing Data
        if (MstCategory::where('category', $request->category)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }

        DB::beginTransaction();
        try {
            $store = MstCategory::create([
                'category' => $request->category,
                'is_active' => 1
            ]);

            // Audit Log
            $this->auditLogs('Store New Category ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', 'Success, New Category Added');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Add New Category!']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(MstCategory::where('category', $request->category)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }
        // Check With Data Before
        $dataBefore = MstCategory::where('id', $id)->first();
        $dataBefore->category = $request->category;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstCategory::where('id', $id)->update([
                    'category' => $request->category
                ]);

                // Audit Log
                $this->auditLogs('Update Selected Category ID: ' . $id);
                DB::commit();
                return redirect()->back()->with('success', 'Success, Selected Category Updated');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => 'Failed to Update Category Dropdown!']);
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
            MstCategory::where('id', $id)->update(['is_active' => 1]);
            $nameValue = MstCategory::where('id', $id)->first()->category;

            // Audit Log
            $this->auditLogs('Enable Selected Category ID: ' . $id);
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
            MstCategory::where('id', $id)->update(['is_active' => 0]);
            $nameValue = MstCategory::where('id', $id)->first()->category;

            // Audit Log
            $this->auditLogs('Disable Selected Category ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate : ' . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate : ' . $nameValue . '!']);
        }
    }
}
