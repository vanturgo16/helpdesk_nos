<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

// Traits
use App\Traits\AuditLogsTrait;
use App\Traits\TicketTrait;

// Model
use App\Models\Ticket;
use App\Models\MstPriorities;
use App\Models\Log;
use App\Models\LogTicket;
use App\Models\User;
use App\Models\MstRules;

class TicketController extends Controller
{
    use AuditLogsTrait;
    use TicketTrait;

    public function index(Request $request)
    {
        $priorities = MstPriorities::orderBy('created_at', 'desc')->where('is_active', 1)->get();
        if (!$request->has('year') || $request->year == "") {
            $year = Carbon::now()->format('Y');
        } else {
            $year = $request->year;
        }
        //Audit Log
        $this->auditLogs('View Index List Ticket');
        return view('ticket.index', compact('priorities', 'year'));
    }

    public function datas(Request $request)
    {
        if ($request->ajax()) {
            $datas = Ticket::select('tickets.*', 'tickets.created_at as created', 'tickets.target_solved_date as targetDate', 'tickets.closed_date as closedDate',
                'log_tickets.assign_to_dept as lastAssign')
                ->leftJoin('log_tickets', function ($join) {
                    $join->on('log_tickets.id_ticket', 'tickets.id')
                        ->whereRaw('log_tickets.id = (SELECT MAX(id) FROM log_tickets WHERE log_tickets.id_ticket = tickets.id)');
                });
            if ($request->has('filterPriority') && $request->filterPriority != '') {
                $datas->where('tickets.priority', $request->filterPriority);
            }
            if ($request->has('filterStatus') && $request->filterStatus != '') {
                $datas->where('tickets.status', $request->filterStatus);
            }
            if ($request->has('year') && $request->year != '') {
                $datas->whereYear('tickets.created_at', $request->year);
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

        $emailUser = auth()->user()->email;
        $dealerTypeHandle = MstRules::where('rule_name', 'Type Dealer Handle Ticket')->pluck('rule_value')->toArray();
        $roleHandle = MstRules::where('rule_name', 'Type Role Handle Ticket')->pluck('rule_value')->toArray();
        $lastAssign = LogTicket::where('id_ticket', $id)->orderBy('created_at', 'desc')->first();

        $userRequest = $data->created_by;
        $userAssign = User::whereIn('dealer_type', $dealerTypeHandle)->where('department', $lastAssign->assign_to_dept)->whereIn('role', $roleHandle)->pluck('email')->toArray();

        //Audit Log
        $this->auditLogs('View Detail Ticket ID (' . $id . ')');
        return view('ticket.detail', compact('id', 'data', 'emailUser', 'lastAssign', 'userRequest', 'userAssign'));
    }

    public function assignDatas(Request $request, $id)
    {
        $id = decrypt($id);
        if ($request->ajax()) {
            $datas = LogTicket::select('log_tickets.*', 'users.name as assignBy', 'users.department as department',
                    'log_tickets.created_at as assignDate', 'log_tickets.accept_date as acceptDate',
                    'log_tickets.preclosed_date as preclosedDate')
                ->leftjoin('users', 'log_tickets.assign_by', 'users.email')
                ->leftjoin('logs', 'log_tickets.id_log', 'logs.id')
                ->where('log_tickets.id_ticket', $id)
                ->get();
            return DataTables::of($datas)
                ->addColumn('dateDetail', function ($data) {
                    return view('ticket.assign.date_detail', compact('data'));
                })->toJson();
        }
    }

    public function logDatas(Request $request, $id)
    {
        $id = decrypt($id);
        if ($request->ajax()) {
            $datas = Log::select('logs.*', 'logs.created_at as created')
                ->where('id_ticket', $id)->get();
            
            return DataTables::of($datas)
                ->addColumn('attachment', function ($data) {
                    return view('ticket.activity.attachment', compact('data'));
                })->toJson();
        }
    }

    public function accept($id)
    {
        $id = decrypt($id);
        $idTicket = LogTicket::where('id', $id)->first()->id_ticket;
        $ticket = Ticket::where('id', $idTicket)->first();

        DB::beginTransaction();
        try {
            if($ticket->status == 0){
                Ticket::where('id', $idTicket)->update(['status' => 1]);
            }
            LogTicket::where('id', $id)->update(['accept_date' => now()->format('Y-m-d H:i')]);

            // Activity Ticket Log
            $this->activityLog($idTicket, 'Success Accept Ticket', '-', null);
            // Audit Log
            $this->auditLogs('Accept Log Assign Ticket ID: ' . $id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.ticket_fail1'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.ticket_success1')]);
        }
    }

    public function addActivity(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|max:255',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,png,pdf,docx,xls,xlsx',
        ]);
        $id = decrypt($id);
        $url = null;

        DB::beginTransaction();
        try {
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment');
                $url = $path->move('storage/attachmentActivityTicket', $path->hashName());
            }
            // Activity Ticket Log
            $this->activityLog($id, 'Add Activity', $request->message, $url);
            // Audit Log
            $this->auditLogs('Add Activity Ticket ID: ' . $id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.ticket_success2'));
        } catch (Exception $e) {
            DB::rollBack();
            if ($url && file_exists(public_path($url))) { unlink(public_path($url)); }
            return redirect()->back()->with(['fail' => __('messages.ticket_fail2')]);
        }
    }
}
