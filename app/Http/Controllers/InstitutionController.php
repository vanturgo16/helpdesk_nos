<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstInstitution;

class InstitutionController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $data = MstInstitution::first();

        //Audit Log
        $this->auditLogs('View Institution Information');

        return view('institution.index', compact('data'));
    }
    public function store(Request $request)
    {
        dd('test');
    }
}
