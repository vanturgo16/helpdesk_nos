<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstPriorities;

class MstPrioritiesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstPriorities::orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('priority.action', compact('data'));
                })->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Priority');
        return view('priority.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'priority' => 'required',
        ]);
        // Check Existing Data
        if (MstPriorities::where('priority', $request->priority)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }

        DB::beginTransaction();
        try {
            $store = MstPriorities::create([
                'priority' => $request->priority,
                'is_active' => 1
            ]);

            // Audit Log
            $this->auditLogs('Store New Priority ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', 'Success, New Priority Added');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Add New Priority!']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'priority' => 'required',
        ]);

        $id = decrypt($id);
        // Check Existing Data
        if(MstPriorities::where('priority', $request->priority)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }
        // Check With Data Before
        $dataBefore = MstPriorities::where('id', $id)->first();
        $dataBefore->priority = $request->priority;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                MstPriorities::where('id', $id)->update([
                    'priority' => $request->priority
                ]);

                // Audit Log
                $this->auditLogs('Update Selected Priority ID: ' . $id);
                DB::commit();
                return redirect()->back()->with('success', 'Success, Selected Priority Updated');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect()->back()->with(['fail' => 'Failed to Update Priority!']);
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
            MstPriorities::where('id', $id)->update(['is_active' => 1]);
            $nameValue = MstPriorities::where('id', $id)->first()->priority;

            // Audit Log
            $this->auditLogs('Enable Selected Priority ID: ' . $id);
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
            MstPriorities::where('id', $id)->update(['is_active' => 0]);
            $nameValue = MstPriorities::where('id', $id)->first()->priority;

            // Audit Log
            $this->auditLogs('Disable Selected Priority ID: ' . $id);
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate : ' . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate : ' . $nameValue . '!']);
        }
    }
}
