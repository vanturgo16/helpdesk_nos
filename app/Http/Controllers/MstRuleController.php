<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstRules;

class MstRuleController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstRules::orderBy('created_at')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('rule.action', compact('data'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Rule');

        return view('rule.index');
    }
    private function checkExisting($name, $value)
    {
        return MstRules::where('rule_name', $name)->where('rule_value', $value)->exists();
    }
    public function store(Request $request)
    {
        $request->validate([
            'rule_name' => 'required',
            'rule_value' => 'required',
        ]);

        // Check Existing Data
        if ($this->checkExisting($request->rule_name, $request->rule_value)) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }

        DB::beginTransaction();
        try {
            $store = MstRules::create([
                'rule_name' => $request->rule_name,
                'rule_value' => $request->rule_value
            ]);

            //Audit Log
            $this->auditLogs('Store New Rule ID: ' . $store->id);

            DB::commit();
            return redirect()->back()->with('success', 'Success, New Rule Added');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Add New Rule!']);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'rule_name' => 'required',
            'rule_value' => 'required',
        ]);

        $id = decrypt($id);
        // Check With Data Before
        $dataBefore = MstRules::where('id', $id)->first();
        $dataBefore->rule_name = $request->rule_name;
        $dataBefore->rule_value = $request->rule_value;

        if ($dataBefore->isDirty()) {
            // Check Existing Data
            if ($this->checkExisting($request->rule_name, $request->rule_value)) {
                return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
            }

            DB::beginTransaction();
            try {
                MstRules::where('id', $id)->update([
                    'rule_name' => $request->rule_name,
                    'rule_value' => $request->rule_value
                ]);

                //Audit Log
                $this->auditLogs('Update Selected Rule ID: ' . $id);

                DB::commit();
                return redirect()->back()->with('success', 'Success, Selected Rule Updated');
            } catch (Exception $e) {
                DB::rollBack();
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Selected Rule!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }
    public function delete($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            MstRules::where('id', $id)->delete();

            //Audit Log
            $this->auditLogs('Delete Selected Rule ID: ' . $id);

            DB::commit();
            return redirect()->back()->with('success', 'Success, Selected Rule Deleted');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Rule!']);
        }
    }
}
