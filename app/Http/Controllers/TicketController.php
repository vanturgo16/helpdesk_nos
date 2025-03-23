<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

// Exports
use App\Exports\TicketExport;

// Mail
use App\Mail\PreCloseTicket;
use App\Mail\CloseTicket;
use App\Mail\ReAssignTicket;

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
use App\Models\MstDropdowns;

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
        
        $idUpdated = $request->get('idUpdated');
        // Get Page Number
        $page_number = 1;
        if ($idUpdated) {
            $page_size = 5;
            $datas = $this->datas($request);
            $item = $datas->firstWhere('id', $idUpdated);
            if ($item) {
                $index = $datas->search(function ($value) use ($idUpdated) {
                    return $value->id == $idUpdated;
                });
                $page_number = (int) ceil(($index + 1) / $page_size);
            } else {
                $page_number = 1;
            }
        }
    
        //Audit Log
        $this->auditLogs('View Index List Ticket');
        return view('ticket.index', compact('priorities', 'year', 'idUpdated', 'page_number'));
    }

    public function datas(Request $request)
    {
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $email = auth()->user()->email;

        $datas = Ticket::select(
            'tickets.*', 'tickets.created_at as created', 'tickets.target_solved_date as targetDate', 'tickets.closed_date as closedDate',
            'log_tickets.assign_to_dept as lastAssign',
            DB::raw('(SELECT JSON_ARRAYAGG(assign_to_dept) FROM log_tickets WHERE log_tickets.id_ticket = tickets.id) as assignToDeptHistory')
        )
        ->leftJoin('log_tickets', function ($join) {
            $join->on('log_tickets.id_ticket', 'tickets.id')->whereRaw('log_tickets.id = (SELECT MAX(id) FROM log_tickets WHERE log_tickets.id_ticket = tickets.id)');
        });
        // Filter
        if (!in_array($role, ['Super Admin', 'Admin'])) {
            $datas->where(function ($query) use ($department, $email) {
                $query->whereRaw(
                    "JSON_CONTAINS((SELECT JSON_ARRAYAGG(assign_to_dept) FROM log_tickets WHERE log_tickets.id_ticket = tickets.id), ?)", 
                    [json_encode($department)]
                )
                ->orWhere('tickets.created_by', $email);
            });
        }
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

        if ($request->ajax()) {
            return DataTables::of($datas)->addColumn('action', function ($data) {
                    return view('ticket.action', compact('data'));
                })->toJson();
        }

        return $datas;
    }

    public function detail($id)
    {
        $id = decrypt($id);
        $data = Ticket::select('tickets.*', 'users.name as requestorName')
            ->where('tickets.id', $id)
            ->leftjoin('users', 'tickets.created_by', 'users.email')
            ->first();

        $emailUser = auth()->user()->email;
        $dealerTypeHandle = MstRules::where('rule_name', 'Type Dealer Handle Ticket')->pluck('rule_value')->toArray();
        $roleHandle = MstRules::where('rule_name', 'Type Role Handle Ticket')->pluck('rule_value')->toArray();
        $lastAssign = LogTicket::where('id_ticket', $id)->orderBy('created_at', 'desc')->first();
        $departments = MstDropdowns::where('category', 'Department')->where('is_active', 1)->where('name_value', '!=', $lastAssign->assign_to_dept)->pluck('name_value');

        $userRequest = $data->created_by;
        $userAssign = User::whereIn('dealer_type', $dealerTypeHandle)->where('department', $lastAssign->assign_to_dept)->whereIn('role', $roleHandle)->pluck('email')->toArray();

        $deptEnv = LogTicket::where('id_ticket', $id)->pluck('assign_to_dept')->toArray();
        $userEnv = User::whereIn('department', $deptEnv)->whereIn('role', $roleHandle)->pluck('email')->toArray();
        $userIncluded = array_merge($userEnv, [$data->created_by]);
        
        //Audit Log
        $this->auditLogs('View Detail Ticket ID (' . $id . ')');
        return view('ticket.detail', compact('id', 'data', 'emailUser', 'lastAssign', 'departments', 'userRequest', 'userAssign', 'userEnv'));
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
                ->orderBy('log_tickets.created_at', 'desc')
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

    public function preClose(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|max:255',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,png,pdf,docx,xls,xlsx',
        ]);
        $id = decrypt($id);
        $precloseBy = auth()->user()->email;
        $logTicket = LogTicket::where('id', $id)->first();
        $dataTicket = Ticket::select('tickets.*', 'users.name as requestorName')
            ->where('tickets.id', $logTicket->id_ticket)
            ->leftjoin('users', 'tickets.created_by', 'users.email')
            ->first();
        $url = null;
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $emailDev = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
        $toEmail = ($development == 1) ? $emailDev : $dataTicket->created_by;
        $ccEmail = ($development == 1) ? $emailDev : $precloseBy;

        DB::beginTransaction();
        try {
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment');
                $url = $path->move('storage/attachmentActivityTicket', $path->hashName());
            }
            // Update Log Assign
            LogTicket::where('id', $id)->update([
                'assign_status' => 0,
                'preclosed_status' => 1,
                'preclosed_date' => now()->format('Y-m-d H:i'),
                'preclosed_message' => $request->message,
            ]);
            $dataAssign = LogTicket::where('id', $id)->first();

            // Send Email
            $mailContent = new PreCloseTicket($dataTicket, $dataAssign, $url, $precloseBy);
            Mail::to($toEmail)->cc($ccEmail)->send($mailContent);

            // Activity Ticket Log
            $this->activityLog($dataTicket->id, 'Pre-close Ticket', $request->message, $url);
            // Audit Log
            $this->auditLogs('Pre-close Ticket, ID Assign: ' . $id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.ticket_success3'));
        } catch (Exception $e) {
            DB::rollBack();
            if ($url && file_exists(public_path($url))) { unlink(public_path($url)); }
            return redirect()->back()->with(['fail' => __('messages.ticket_fail3')]);
        }
    }

    public function close(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|max:255',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,png,pdf,docx,xls,xlsx',
        ]);
        $id = decrypt($id);
        $closeBy = auth()->user()->email;
        $url = null;
        $dataTicket = Ticket::select('tickets.*', 'users.name as requestorName')
            ->where('tickets.id', $id)
            ->leftjoin('users', 'tickets.created_by', 'users.email')
            ->first();
        // Initiate Email
        $dealerTypeHandle = MstRules::where('rule_name', 'Type Dealer Handle Ticket')->pluck('rule_value')->toArray();
        $assignToDept = LogTicket::where('id_ticket', $id)->pluck('assign_to_dept')->toArray();
        $roleHandle = MstRules::where('rule_name', 'Type Role Handle Ticket')->pluck('rule_value')->toArray();
        $emailAssigns = User::whereIn('dealer_type', $dealerTypeHandle)
            ->whereIn('department', $assignToDept)->whereIn('role', $roleHandle)
            ->pluck('email')->toArray();
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $emailDev = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
        $toEmail = ($development == 1) ? $emailDev : $emailAssigns;
        $ccEmail = ($development == 1) ? $emailDev : $closeBy;
        // Get start duration
        $roleStartDuration = MstRules::where('rule_name', 'Start Calculate Duration (Report Date / Created Ticket)')->first()->rule_value;
        $startDuration = ($roleStartDuration == 'Report Date') ? $dataTicket->report_date : $dataTicket->created_at;
        // Convert to Carbon instance
        $startDuration = Carbon::parse($startDuration);
        $endDuration = Carbon::now(); // Current time
        // Calculate the difference
        $diffInMinutes = $startDuration->diffInMinutes($endDuration);
        $hours = floor($diffInMinutes / 60);
        $minutes = $diffInMinutes % 60;
        // Format the duration
        $duration = "{$hours}h, {$minutes}m";

        DB::beginTransaction();
        try {
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment');
                $url = $path->move('storage/attachmentActivityTicket', $path->hashName());
            }
            // Update Ticket
            Ticket::where('id', $id)->update([
                'closed_notes' => $request->message,
                'closed_date' => now()->format('Y-m-d H:i'),
                'duration' => $duration,
                'status' => 2,
            ]);
            // Re-Query To Get Updated Data
            $dataTicket = Ticket::where('id', $id)->first();

            // Send Email
            $mailContent = new CloseTicket($dataTicket, $assignToDept, $url, $closeBy);
            Mail::to($toEmail)->cc($ccEmail)->send($mailContent);

            // Activity Ticket Log
            $this->activityLog($id, 'Close Ticket', $request->message, $url);
            // Audit Log
            $this->auditLogs('Close Ticket, ID: ' . $id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.ticket_success4'));
        } catch (Exception $e) {
            DB::rollBack();
            if ($url && file_exists(public_path($url))) { unlink(public_path($url)); }
            return redirect()->back()->with(['fail' => __('messages.ticket_fail4')]);
        }
    }

    public function reAssign(Request $request, $id)
    {
        $request->validate([
            'department' => 'required',
            'message' => 'required|max:255',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,png,pdf,docx,xls,xlsx',
        ]);
        $id = decrypt($id);
        $assignBy = auth()->user()->email;
        $url = null;
        $dataTicket = Ticket::select('tickets.*', 'users.name as requestorName')
            ->where('tickets.id', $id)
            ->leftjoin('users', 'tickets.created_by', 'users.email')
            ->first();
        // Initiate Email
        $dealerTypeHandle = MstRules::where('rule_name', 'Type Dealer Handle Ticket')->pluck('rule_value')->toArray();
        $assignToDept = $request->department;
        $roleHandle = MstRules::where('rule_name', 'Type Role Handle Ticket')->pluck('rule_value')->toArray();
        $emailAssigns = User::whereIn('dealer_type', $dealerTypeHandle)
            ->where('department', $assignToDept)->whereIn('role', $roleHandle)
            ->pluck('email')->toArray();
        if (empty($emailAssigns)) {
            return redirect()->back()->with(['fail' => __('messages.ticket_fail6')]);
        }
        $emailAssigns = array_merge($emailAssigns, [(string) $dataTicket->created_by]);
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $emailDev = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
        $toEmail = ($development == 1) ? $emailDev : $emailAssigns;
        $ccEmail = ($development == 1) ? $emailDev : $assignBy;

        DB::beginTransaction();
        try {
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment');
                $url = $path->move('storage/attachmentActivityTicket', $path->hashName());
            }
            // Activity Ticket Log
            $this->activityLog($id, 'Success Re Assign Ticket', $request->message, $url);
            $logAssign = $this->activityLog($id, 'Success Assign Ticket', 'Assign To:' . $assignToDept, null);
            // Assign Ticket Log
            LogTicket::create([
                'id_ticket' => $id,
                'id_log' => $logAssign->id,
                'assign_by' => $assignBy,
                'assign_to_dept' => $assignToDept,
                'assign_date' => now()->format('Y-m-d H:i'),
                'assign_status' => 1,
                'preclosed_status' => 0,
            ]);

            // Send Email
            $mailContent = new ReAssignTicket($dataTicket, $assignToDept, $request->message, $assignBy);
            Mail::to($toEmail)->cc($ccEmail)->send($mailContent);

            // Audit Log
            $this->auditLogs('Re Assign Ticket, ID: ' . $id);
            DB::commit();
            return redirect()->back()->with('success', __('messages.ticket_success5'));
        } catch (Exception $e) {
            DB::rollBack();
            if ($url && file_exists(public_path($url))) { unlink(public_path($url)); }
            return redirect()->back()->with(['fail' => __('messages.ticket_fail5')]);
        }
    }
    
    public function export(Request $request)
    {
        $dataTicket = Ticket::select('tickets.*')->orderBy('tickets.created_at', 'desc');
        $dataLogAssign = LogTicket::select('tickets.no_ticket', 'log_tickets.*')
            ->leftjoin('tickets', 'log_tickets.id_ticket', 'tickets.id')
            ->orderBy('tickets.created_at', 'desc');

        // FILTER DATA
        if ($request->has('priority') && $request->priority != '') {
            $dataTicket->where('tickets.priority', $request->priority);
            $dataLogAssign->where('tickets.priority', $request->priority);
        }
        if ($request->has('status') && $request->status != '') {
            $dataTicket->where('tickets.status', $request->status);
            $dataLogAssign->where('tickets.status', $request->status);
        }
        if ($request->has('dateFrom') && $request->dateFrom != '' && $request->has('dateTo') && $request->dateTo != '') {
            $startDate = $request->dateFrom . ' 00:00:00';
            $endDate = $request->dateTo . ' 23:59:59';

            $dataTicket->whereBetween('tickets.created_at', [$startDate, $endDate]);
            $dataLogAssign->whereBetween('tickets.created_at', [$startDate, $endDate]);
        }

        $filename = 'Export_Ticket_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
        return Excel::download(new TicketExport($dataTicket->get(), $dataLogAssign->get(), $request), $filename);
    }
}
