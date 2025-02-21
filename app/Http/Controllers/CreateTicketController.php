<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Traits
use App\Traits\AuditLogsTrait;
use App\Traits\GenNoTicketTrait;

// Model
use App\Models\MstPriorities;
use App\Models\MstCategory;
use App\Models\Ticket;
use App\Models\Log;
use App\Models\LogTicket;

class CreateTicketController extends Controller
{
    use AuditLogsTrait;
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
            'report_date' => 'required',
            'notes' => 'required',
            'file_1' => 'nullable|file|max:10240|mimes:jpg,png,pdf,docx,xls,xlsx',
        ]);
        $assignTo = 'IT';
        DB::beginTransaction();
        try {
            $url = null;
            $user = auth()->user()->email;
            if ($request->hasFile('file_1')) {
                $path = $request->file('file_1');
                $url = $path->move('storage/attachmentTicket', $path->hashName());
            }
            // Store Ticket
            $store = Ticket::create([
                'no_ticket' => $this->genNoTicket(),
                'priority' => $request->priority,
                'category' => $request->category,
                'sub_category' => $request->sub_category,
                'report_date' => $request->report_date,
                'notes' => $request->notes,
                'file_1' => $url,
                'created_by' => $user,
                'target_solved_date' => $request->target_solved_date,
                'status' => 0,
            ]);
            // Store Log Ticket
            Log::create([
                'id_ticket' => $store->id,
                'created_by' => $user,
                'description' => 'Success Created Ticket',
                'message' => $request->notes
            ]);
            $logAssign = Log::create([
                'id_ticket' => $store->id,
                'created_by' => $user,
                'description' => 'Success Assign Ticket',
                'message' => 'Assign To:' . $assignTo
            ]);
            // Store Assign Log
            LogTicket::create([
                'id_ticket' => $store->id,
                'id_log' => $logAssign->id,
                'assign_by' => $user,
                'assign_to_dept' => $assignTo,
                'assign_date' => Carbon::now(),
                'assign_status' => 0,
                'preclosed_status' => 0,
            ]);

            // Audit Log
            $this->auditLogs('Store New Ticket ID: ' . $store->id);
            DB::commit();
            return redirect()->route('ticket.index')->with('success', 'Success, New Ticket Requested');
        } catch (Exception $e) {
            DB::rollBack();
            if ($url && file_exists(public_path($url))) { unlink(public_path($url)); }
            return redirect()->back()->with(['fail' => 'Failed to Add New Ticket!']);
        }
    }
}
