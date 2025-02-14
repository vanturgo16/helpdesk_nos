<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstDepartments;

class MstDepartmentController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstDepartments::orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('department.action', compact('data'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Department');

        return view('department.index');
    }
    public function store(Request $request)
    {
        $request->validate([
            'department' => 'required',
        ]);

        // Check Existing Data
        if (MstDepartments::where('department', $request->department)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }

        DB::beginTransaction();
        try {
            $store = MstDepartments::create([
                'department' => $request->department,
                'is_active' => 1
            ]);

            //Audit Log
            $this->auditLogs('Store New Department ID: ' . $store->id);

            DB::commit();
            return redirect()->back()->with('success', 'Success, New Department Added');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Add New Department!']);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'department' => 'required',
        ]);

        $id = decrypt($id);
        // Check With Data Before
        $dataBefore = MstDepartments::where('id', $id)->first();
        $dataBefore->department = $request->department;

        if ($dataBefore->isDirty()) {
            // Check Existing Data
            if (MstDepartments::where('department', $request->department)->exists()) {
                return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
            }

            DB::beginTransaction();
            try {
                MstDepartments::where('id', $id)->update(['department' => $request->department]);

                //Audit Log
                $this->auditLogs('Update Selected Department ID: ' . $id);

                DB::commit();
                return redirect()->back()->with('success', 'Success, Selected Department Updated');
            } catch (Exception $e) {
                DB::rollBack();
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Selected Department!']);
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
            MstDepartments::where('id', $id)->update(['is_active' => 1]);
            $name = MstDepartments::where('id', $id)->first()->department;

            //Audit Log
            $this->auditLogs('Enable Selected Department ID: ' . $id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate : ' . $name]);
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate : ' . $name . '!']);
        }
    }
    public function disable($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            MstDepartments::where('id', $id)->update(['is_active' => 0]);
            $name = MstDepartments::where('id', $id)->first()->department;

            //Audit Log
            $this->auditLogs('Disable Selected Department ID: ' . $id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate : ' . $name]);
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate : ' . $name . '!']);
        }
    }
}
