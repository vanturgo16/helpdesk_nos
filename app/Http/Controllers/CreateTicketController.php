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
use App\Models\MstPriorities;
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
        $priorities = MstPriorities::orderBy('created_at', 'desc')->where('is_active', 1)->get();
        $categories = MstCategory::orderBy('created_at', 'desc')->where('is_active', 1)->get();

        //Audit Log
        $this->auditLogs('View Form Create Ticket');
        return view('create_ticket.index', compact('noTicket', 'priorities', 'categories'));
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
        $user = auth()->user()->email;

        // Initiate Email
        $dealerTypeHandle = MstRules::where('rule_name', 'Type Dealer Handle Ticket')->pluck('rule_value')->toArray();
        $roleHandle = MstRules::where('rule_name', 'Type Role Handle Ticket')->pluck('rule_value')->toArray();
        $emailAssigns = User::whereIn('dealer_type', $dealerTypeHandle)
            ->where('department', $assignToDept)->whereIn('role', $roleHandle)
            ->pluck('email')->toArray();
        $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
        $toEmail = ($development == 1) ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() : $emailAssigns;
        $ccEmail = ($development == 1) ? MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray() : $user;

        DB::beginTransaction();
        try {
            if ($request->hasFile('file_1')) {
                $path = $request->file('file_1');
                $url1 = $path->move('storage/attachmentTicket', $path->hashName());
            }
            // Store Ticket
            $store = Ticket::create([
                'no_ticket' => $this->genNoTicket(),
                'priority' => $request->priority,
                'category' => $request->category,
                'sub_category' => $request->sub_category,
                'report_date' => $reportDate,
                'target_solved_date' => $targetDate,
                'notes' => $request->notes,
                'file_1' => $url1,
                'created_by' => $user,
                'status' => 0,
            ]);
            // Activity Ticket Log
            $this->activityLog($store->id, 'Success Created Ticket', $request->notes, null);
            $logAssign = Log::create([
                'id_ticket' => $store->id, 'created_by' => $user,
                'description' => 'Success Assign Ticket',
                'message' => 'Assign To:' . $assignToDept
            ]);
            // Assign Ticket Log
            LogTicket::create([
                'id_ticket' => $store->id,
                'id_log' => $logAssign->id,
                'assign_by' => $user,
                'assign_to_dept' => $assignToDept,
                'assign_date' => now()->format('Y-m-d H:i'),
                'assign_status' => 1,
                'preclosed_status' => 0,
            ]);

            // Send Email
            $mailContent = new NewTicketAssign($store, $assignToDept, $user);
            Mail::to($toEmail)->cc($ccEmail)->send($mailContent);

            // Audit Log
            $this->auditLogs('Store New Ticket ID: ' . $store->id);
            DB::commit();
            return redirect()->route('ticket.index')->with('success', 'Success, New Ticket Requested');
        } catch (Exception $e) {
            DB::rollBack();
            if ($url1 && file_exists(public_path($url1))) { unlink(public_path($url1)); }
            return redirect()->back()->with(['fail' => 'Failed to Add New Ticket!']);
        }
    }
}
