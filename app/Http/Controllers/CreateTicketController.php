<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

// Mail
use App\Mail\NewTicketAssign;

// Traits
use App\Traits\AuditLogsTrait;
use App\Traits\TicketTrait;
use App\Traits\GenNoTicketTrait;

// Model
use App\Models\MstCategory;
use App\Models\MstRules;
use App\Models\User;
use App\Models\MstSubCategory;
use App\Models\Ticket;
use App\Models\Log;
use App\Models\LogTicket;

class CreateTicketController extends Controller
{
    use AuditLogsTrait;
    use TicketTrait;
    use GenNoTicketTrait;

    public function index(Request $request)
    {
        $noTicket = $this->showNoTicket();
        $categories = MstCategory::orderBy('created_at', 'desc')->where('is_active', 1)->get();

        //Audit Log
        $this->auditLogs('View Form Create Ticket');
        return view('create_ticket.index', compact('noTicket', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'priority' => 'required',
            'category' => 'required',
            'sub_category' => 'required',
            'report_date_option_val' => 'required',
            'report_date' => 'required',
            'target_date_option_val' => 'required',
            'target_solved_date' => 'required',
            'notes' => 'required|max:255',
            'file_1' => 'nullable|file|max:10240|mimes:jpg,png,pdf,docx,xls,xlsx',
        ]);

        //IF report_date_option_val now get date now (format H-m) for report date
        //Else get report_date val for report_date (Validate Less Than Now)
        $optionReport = $request->report_date_option_val;
        if ($optionReport == 'now') {
            $reportDate = now()->format('Y-m-d H:i'); // Set current date-time
        } else {
            $request->validate([
                'report_date' => 'required|date|before_or_equal:now',
            ]);
            $reportDate = $request->report_date;
        }
        //IF target_date_option_val sla_target find sla from sub_category for target_solved_date (Calculate date now H-m + sla)
        //ELSE get target_solved_date val for target_solved_date (Validate More Than Now)
        $optionTarget = $request->target_date_option_val;
        if ($optionTarget == 'sla_target') {
            $sla = MstSubCategory::where('sub_category', $request->sub_category)->value('sla'); // Fetch SLA in minutes
            $targetDate = now()->addMinutes((int) $sla)->format('Y-m-d H:i'); // Calculate SLA-based target date
        } else {
            $request->validate([
                'target_solved_date' => 'required|date|after:now',
            ]);
            $targetDate = $request->target_solved_date;
        }
        $assignToDept = 'IT';
        $url1 = null;
        $requestor = auth()->user()->email;

        // Initiate Email
        $dealerTypeHandle = MstRules::where('rule_name', 'Type Dealer Handle Ticket')->pluck('rule_value')->toArray();
        $roleHandle = MstRules::where('rule_name', 'Type Role Handle Ticket')->pluck('rule_value')->toArray();
        $emailAssigns = User::whereIn('dealer_type', $dealerTypeHandle)
            ->where('department', $assignToDept)->whereIn('role', $roleHandle)
            ->pluck('email')->toArray();
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $emailDev = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
        $toEmail = ($development == 1) ? $emailDev : $emailAssigns;
        $ccEmail = ($development == 1) ? $emailDev : $requestor;

        DB::beginTransaction();
        try {
            if ($request->hasFile('file_1')) {
                $path = $request->file('file_1');
                $url1 = $path->move('storage/attachmentTicket', $path->hashName());
            }
            // Store Data Ticket
            $dataTicket = Ticket::create([
                'no_ticket' => $this->genNoTicket(),
                'priority' => $request->priority,
                'category' => $request->category,
                'sub_category' => $request->sub_category,
                'report_date' => $reportDate,
                'target_solved_date' => $targetDate,
                'notes' => $request->notes,
                'file_1' => $url1,
                'created_by' => $requestor,
                'status' => 0,
            ]);
            // Activity Ticket Log
            $this->activityLog($dataTicket->id, 'Success Created Ticket', $request->notes, null);
            $logAssign = $this->activityLog($dataTicket->id, 'Success Assign Ticket', 'Assign To:' . $assignToDept, null);
            // Assign Ticket Log
            LogTicket::create([
                'id_ticket' => $dataTicket->id,
                'id_log' => $logAssign->id,
                'assign_by' => $requestor,
                'assign_to_dept' => $assignToDept,
                'assign_date' => now()->format('Y-m-d H:i'),
                'assign_status' => 1,
                'preclosed_status' => 0,
            ]);

            // Send Email
            $mailContent = new NewTicketAssign($dataTicket, $assignToDept, $requestor);
            Mail::to($toEmail)->cc($ccEmail)->send($mailContent);

            // Audit Log
            $this->auditLogs('Store New Ticket ID: ' . $dataTicket->id);
            DB::commit();
            return redirect()->route('ticket.index')->with('success', __('messages.create_ticket_success1'));
        } catch (Exception $e) {
            DB::rollBack();
            if ($url1 && file_exists(public_path($url1))) { unlink(public_path($url1)); }
            return redirect()->back()->with(['fail' => __('messages.create_ticket_fail1')]);
        }
    }
}
