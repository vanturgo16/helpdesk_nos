<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\User;

class ProfileController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        //Audit Log
        $this->auditLogs('View Page Profile');
        return view('profile.index');
    }
    public function updatePhoto(Request $request)
    {
        DB::beginTransaction();
        try {
            $statusBefore = User::where('id', auth()->user()->id)->first()->is_darkmode;
            $status = ($statusBefore == 1) ? null : 1;
            User::where('id', auth()->user()->id)->update(['is_darkmode' => $status]);

            //Audit Log
            $this->auditLogs('Update Photo Profile');
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_update'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_update')]);
        }
    }
}
