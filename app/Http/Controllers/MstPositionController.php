<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstPositions;
use App\Models\MstDepartments;

class MstPositionController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstPositions::select('mst_departments.department as department_name', 'mst_positions.*')
            ->leftjoin('mst_departments', 'mst_positions.department_id', 'mst_departments.id')
            ->orderBy('mst_departments.created_at', 'desc')
            ->orderBy('mst_positions.department_id')
            ->get();
        $departments = MstDepartments::where('is_active', 1)->orderBy('created_at', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($departments) {
                    return view('position.action', compact('data', 'departments'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Position');

        return view('position.index', compact('departments'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'position' => 'required',
        ]);

        // Check Existing Data
        if (MstPositions::where('department_id', $request->department_id)->where('position', $request->position)->exists()) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }

        DB::beginTransaction();
        try {
            $store = MstPositions::create([
                'department_id' => $request->department_id,
                'position' => $request->position,
                'is_active' => 1
            ]);

            //Audit Log
            $this->auditLogs('Store New Position ID: ' . $store->id);

            DB::commit();
            return redirect()->back()->with('success', 'Success, New Position Added');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Add New Position!']);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required',
            'position' => 'required',
        ]);

        $id = decrypt($id);
        // Check With Data Before
        $dataBefore = MstPositions::where('id', $id)->first();
        $dataBefore->department_id = $request->department_id;
        $dataBefore->position = $request->position;

        if ($dataBefore->isDirty()) {
            // Check Existing Data
            if (MstPositions::where('department_id', $request->department_id)->where('position', $request->position)->exists()) {
                return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
            }

            DB::beginTransaction();
            try {
                MstPositions::where('id', $id)->update([
                    'department_id' => $request->department_id,
                    'position' => $request->position,
                ]);

                //Audit Log
                $this->auditLogs('Update Selected Position ID: ' . $id);

                DB::commit();
                return redirect()->back()->with('success', 'Success, Selected Position Updated');
            } catch (Exception $e) {
                DB::rollBack();
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Selected Position!']);
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
            MstPositions::where('id', $id)->update(['is_active' => 1]);
            $name = MstPositions::where('id', $id)->first()->position;

            //Audit Log
            $this->auditLogs('Enable Selected Position ID: ' . $id);

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
            MstPositions::where('id', $id)->update(['is_active' => 0]);
            $name = MstPositions::where('id', $id)->first()->position;

            //Audit Log
            $this->auditLogs('Disable Selected Position ID: ' . $id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate : ' . $name]);
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate : ' . $name . '!']);
        }
    }
}
