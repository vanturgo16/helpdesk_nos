<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\MstPriorities;
use App\Models\MstCategory;

class MstCreateTicketController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $priorities = MstPriorities::orderBy('created_at', 'desc')->where('is_active', 1)->get();
        $categories = MstCategory::orderBy('created_at', 'desc')->where('is_active', 1)->get();

        //Audit Log
        $this->auditLogs('View Form Create Ticket');
        return view('create_ticket.index', compact('priorities', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $store = [];

            // Audit Log
            $this->auditLogs('Store New Ticket ID: ' . $store->id);
            DB::commit();
            return redirect()->back()->with('success', 'Success, New Ticket Added');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => 'Failed to Add New Ticket!']);
        }
    }
}
