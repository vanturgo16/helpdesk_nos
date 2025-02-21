<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\Ticket;
use App\Models\MstPriorities;
use App\Models\Log;

class TicketController extends Controller
{
    use AuditLogsTrait;

    public function index()
    {
        $priorities = MstPriorities::orderBy('created_at', 'desc')->where('is_active', 1)->get();
        //Audit Log
        $this->auditLogs('View Index List Ticket');
        return view('ticket.index', compact('priorities'));
    }

    public function datas(Request $request)
    {
        if ($request->ajax()) {
            $datas = Ticket::select('tickets.*', 'tickets.created_at as created');
            if ($request->has('filterPriority') && $request->filterPriority != '') {
                $datas->where('tickets.priority', $request->filterPriority);
            }
            if ($request->has('filterStatus') && $request->filterStatus != '') {
                $datas->where('tickets.status', $request->filterStatus);
            }
            $datas = $datas->orderBy('created_at', 'desc')->get();
            
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('ticket.action', compact('data'));
                })->toJson();
        }
    }

    public function detail($id)
    {
        $id = decrypt($id);
        $data = Ticket::where('id', $id)->first();

        //Audit Log
        $this->auditLogs('View Detail Ticket ID (' . $id . ')');
        return view('ticket.detail', compact('id', 'data'));
    }

    public function logDatas(Request $request, $id)
    {
        $id = decrypt($id);
        if ($request->ajax()) {
            $datas = Log::select('logs.*', 'logs.created_at as created')
                ->where('id_ticket', $id)->orderBy('created_at', 'desc')->get();
            
            return DataTables::of($datas)
                ->addColumn('attachment', function ($data) {
                    return view('ticket.activity.attachment', compact('data'));
                })
                ->addColumn('action', function ($data) {
                    return view('ticket.activity.action', compact('data'));
                })->toJson();
        }
    }
}
