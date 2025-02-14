<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstDropdowns;

class MstDropdownController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $datas = MstDropdowns::orderBy('category')->orderBy('created_at')->get();
        $categories = MstDropdowns::get()->unique('category');

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($categories) {
                    return view('dropdown.action', compact('data', 'categories'));
                })
                ->toJson();
        }

        //Audit Log
        $this->auditLogs('View List Master Dropdown');

        return view('dropdown.index', compact('categories'));
    }
    private function checkExisting($cat, $name)
    {
        return MstDropdowns::where('category', $cat)->where('name_value', $name)->exists();
    }
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'name_value' => 'required',
        ]);
        $category = $request->category === 'NewCat' ? $request->addcategory : $request->category;

        // Check Existing Data
        if ($this->checkExisting($category, $request->name_value)) {
            return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
        }

        DB::beginTransaction();
        try {
            $store = MstDropdowns::create([
                'category' => $category,
                'name_value' => $request->name_value,
                'code_format' => $request->code_format,
                'is_active' => 1
            ]);

            //Audit Log
            $this->auditLogs('Store New Dropdown ID: ' . $store->id);

            DB::commit();
            return redirect()->back()->with('success', 'Success, New Dropdown Added');
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Add New Dropdown!']);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required',
            'name_value' => 'required',
        ]);
        $category = $request->category === 'NewCat' ? $request->addcategory : $request->category;

        $id = decrypt($id);
        // Check With Data Before
        $dataBefore = MstDropdowns::where('id', $id)->first();
        $dataBefore->category = $category;
        $dataBefore->name_value = $request->name_value;
        $dataBefore->code_format = $request->code_format;

        if ($dataBefore->isDirty()) {
            // Check Existing Data
            if ($this->checkExisting($category, $request->name_value)) {
                return redirect()->back()->with(['fail' => 'Duplicate data entry is not allowed!']);
            }

            DB::beginTransaction();
            try {
                MstDropdowns::where('id', $id)->update([
                    'category' => $category,
                    'name_value' => $request->name_value,
                    'code_format' => $request->code_format
                ]);

                //Audit Log
                $this->auditLogs('Update Selected Dropdown ID: ' . $id);

                DB::commit();
                return redirect()->back()->with('success', 'Success, Selected Dropdown Updated');
            } catch (Exception $e) {
                DB::rollBack();
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Selected Dropdown!']);
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
            MstDropdowns::where('id', $id)->update(['is_active' => 1]);
            $nameValue = MstDropdowns::where('id', $id)->first()->name_value;

            //Audit Log
            $this->auditLogs('Enable Selected Dropdown ID: ' . $id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate : ' . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate : ' . $nameValue . '!']);
        }
    }
    public function disable($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            MstDropdowns::where('id', $id)->update(['is_active' => 0]);
            $nameValue = MstDropdowns::where('id', $id)->first()->name_value;

            //Audit Log
            $this->auditLogs('Disable Selected Dropdown ID: ' . $id);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate : ' . $nameValue]);
        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate : ' . $nameValue . '!']);
        }
    }
}
