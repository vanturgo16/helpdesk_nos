<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

// Mail
use App\Mail\SendEmailPassword;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\User;
use App\Models\MstDropdowns;
use App\Models\MstRules;
use App\Models\MstEmployees;

class MstUserController extends Controller
{
    use AuditLogsTrait;

    public function index()
    {
        $roleUsers = MstDropdowns::where('category', 'Role User')->where('is_active', 1)->get();
        $dealerTypes = MstDropdowns::where('category', 'Type Dealer')->where('is_active', 1)->get();
        $departments = MstDropdowns::where('category', 'Department')->where('is_active', 1)->get();

        //Audit Log
        $this->auditLogs('View List Manage User');
        return view('users.index', compact('roleUsers', 'dealerTypes', 'departments'));
    }

    public function datas(Request $request)
    {
        if ($request->ajax()) {
            $datas = User::orderBy('last_seen')->get();
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('users.action', compact('data'));
                })->toJson();
        }
    }

    private function generateRandomPassword($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }

    public function store(Request $request)
    {
        // Validate Request
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'dealer_type' => 'required',
            'dealer_name' => 'required',
            'department' => 'required',
            'role' => 'required',
        ]);
        //Prevent Create Role Super Admin, If Not Super Admin
        if (auth()->user()->role != 'Super Admin' && $request->role == 'Super Admin') {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, You Do Not Have Access to Add Role as Super Admin']);
        }
        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()->with('warning', 'Email Was Already Registered As User');
        }

        // Initiate Variable
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $toemail = ($development == 1) 
                ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
                : $request->email;
            
        DB::beginTransaction();
        try {
            $password = $this->generateRandomPassword(8);
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'dealer_type' => $request->dealer_type,
                'dealer_name' => $request->dealer_name,
                'department' => $request->department,
                'is_active' => 1,
                'role' => $request->role
            ]);

            // [ MAILING ]
            $mailContent = new SendEmailPassword('New', $request->name, $request->email, $password);
            Mail::to($toemail)->send($mailContent);

            // Audit Log
            $this->auditLogs('Create New User (' . $request->email . ')');
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New User']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New User!']);
        }
    }

    public function reset($id)
    {
        $id = decrypt($id);
        // Initiate Variable
        $data = User::where('id', $id)->first();
        $password = $this->generateRandomPassword(8);
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $toemail = ($development == 1) 
                ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() 
                : $data->email;

        DB::beginTransaction();
        try {
            User::where('id', $id)->update([
                'password' => Hash::make($password),
            ]);

            // [ MAILING ]
            $mailContent = new SendEmailPassword('Reset', $data->name, $data->email, $password);
            Mail::to($toemail)->send($mailContent);

            // Audit Log
            $this->auditLogs('Reset Password User (' . $data->email . ')');
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Reset Password User, New Password Has Been Send to Email: ' . $data->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Reset Password User ' . $data->email . '!']);
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = User::where('id', $id)->first();
        $roleUsers = MstDropdowns::where('category', 'Role User')->where('is_active', 1)->orWhere('name_value', $data->role)->get();
        $dealerTypes = MstDropdowns::where('category', 'Type Dealer')->where('is_active', 1)->orWhere('name_value', $data->dealer_type)->get();
        $departments = MstDropdowns::where('category', 'Department')->where('is_active', 1)->orWhere('name_value', $data->department)->get();

        //Audit Log
        $this->auditLogs('View Edit User ID (' . $id . ')');
        return view('users.edit', compact('data', 'roleUsers', 'dealerTypes', 'departments'));
    }

    public function update(Request $request, $id)
    {
        // Validate Request
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'dealer_type' => 'required',
            'dealer_name' => 'required',
            'department' => 'required',
            'role' => 'required',
        ]);
        //Prevent Create Role Super Admin, If Not Super Admin
        if (auth()->user()->role != 'Super Admin' && $request->role == 'Super Admin') {
            return redirect()->back()->withInput()->with(['fail' => 'Failed, You Do Not Have Access to Add Role as Super Admin']);
        }

        $iduser = decrypt($id);
        $dataBefore = User::where('id', $iduser)->first();
        $dataBefore->name = $request->name;
        $dataBefore->email = $request->email;
        $dataBefore->dealer_type = $request->dealer_type;
        $dataBefore->dealer_name = $request->dealer_name;
        $dataBefore->department = $request->department;
        $dataBefore->role = $request->role;

        if ($dataBefore->isDirty()) {
            DB::beginTransaction();
            try {
                User::where('id', $iduser)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'dealer_type' => $request->dealer_type,
                    'dealer_name' => $request->dealer_name,
                    'department' => $request->department,
                    'role' => $request->role
                ]);

                //Audit Log
                $this->auditLogs('Update User (' . $dataBefore->email . ')');
                DB::commit();
                return redirect()->route('user.index')->with(['success' => 'Success Update User']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update User!']);
            }
        } else {
            return redirect()->route('user.index')->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);
        DB::beginTransaction();
        try {
            $email = User::where('id', $id)->first()->email;
            User::where('id', $id)->delete();

            // Audit Log
            $this->auditLogs('Delete User (' . $email . ')');
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete User ' . $email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete User ' . $email . '!']);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);
        $data = User::where('id', $id)->first();
        DB::beginTransaction();
        try {
            User::where('id', $id)->update([ 'is_active' => 1 ]);

            // Audit Log
            $this->auditLogs('Activate User (' . $data->email . ')');
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate User ' . $data->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate User ' . $data->email . '!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);
        $data = User::where('id', $id)->first();
        DB::beginTransaction();
        try {
            User::where('id', $id)->update([ 'is_active' => 0 ]);

            // Audit Log
            $this->auditLogs('Deactivate User (' . $data->email . ')');
            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate User ' . $data->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate User ' . $data->email . '!']);
        }
    }

    public function check_email(Request $request)
    {
        $email = $request->input('email');
        $isEmailRegist = MstEmployees::where('email', $email)->first();
        if ($isEmailRegist || $email == null) {
            return response()->json(['status' => 'registered']);
        } else {
            return response()->json(['status' => 'notregistered']);
        }
    }
}
