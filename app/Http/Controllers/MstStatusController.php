<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstStatus;

class MstStatusController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstStatus::orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('status.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Status');
        return view('status.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required',
        ]);
        // Check Existing Data
        if (MstStatus::where('status', $request->status)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }

        DB::beginTransaction();
        try {
            $store = MstStatus::create([
                'status' => $request->status,
                'is_active' => 1
            ]);

            // Audit Log
            $this->auditLogs('Store New Status ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', 'Success, New Status Added');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Add New Status!']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(MstStatus::where('status', $request->status)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }
        // Check With Data Before
        $dataBefore = MstStatus::where('id', $id)->first();
        $dataBefore->status = $request->status;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstStatus::where('id', $id)->update([
                    'status' => $request->status
                ]);

                // Audit Log
                $this->auditLogs('Update Selected Status ID: ' . $id);
                DB::commit();
                return redirect()->back()->with('success', 'Success, Selected Status Updated');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => 'Failed to Update Status Dropdown!']);
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
            MstStatus::where('id', $id)->update(['is_active' => 1]);
            $nameValue = MstStatus::where('id', $id)->first()->status;

            // Audit Log
            $this->auditLogs('Enable Selected Status ID: ' . $id);
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
            MstStatus::where('id', $id)->update(['is_active' => 0]);
            $nameValue = MstStatus::where('id', $id)->first()->status;

            // Audit Log
            $this->auditLogs('Disable Selected Status ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate : ' . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate : ' . $nameValue . '!']);
        }
    }
}
