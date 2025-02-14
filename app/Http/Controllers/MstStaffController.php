<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Model
use App\Models\User;
use App\Models\MstDropdowns;

class MstStaffController extends Controller
{
    public function index(Request $request)
    {
        $roleUsers = MstDropdowns::where('category', 'User Role')->get();
        $datas = User::orderBy('last_seen')->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($roleUsers) {
                    return view('users.action', compact('data'));
                })
                ->toJson();
        }

        return view('users.index', compact('roleUsers'));
    }
    public function store(Request $request)
    {
        dd('test');
    }
}
