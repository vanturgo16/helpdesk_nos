<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Traits
use App\Traits\AuditLogsTrait;

// Model
use App\Models\User;
use App\Models\Ticket;

class DashboardController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $email = auth()->user()->email;

        $baseQuery = Ticket::select(
            'tickets.*', 'tickets.created_at as created', 'tickets.target_solved_date as targetDate', 'tickets.closed_date as closedDate',
            'log_tickets.assign_to_dept as lastAssign',
            DB::raw('(SELECT JSON_ARRAYAGG(assign_to_dept) FROM log_tickets WHERE log_tickets.id_ticket = tickets.id) as assignToDeptHistory')
        )
        ->leftJoin('log_tickets', function ($join) {
            $join->on('log_tickets.id_ticket', 'tickets.id')
                ->whereRaw('log_tickets.id = (SELECT MAX(id) FROM log_tickets WHERE log_tickets.id_ticket = tickets.id)');
        });

        // Apply filters if the user is not an admin
        if (!in_array($role, ['Super Admin', 'Admin'])) {
            $baseQuery->where(function ($query) use ($department, $email) {
                $query->whereRaw(
                    "JSON_CONTAINS((SELECT JSON_ARRAYAGG(assign_to_dept) FROM log_tickets WHERE log_tickets.id_ticket = tickets.id), ?)",
                    [json_encode($department)]
                )->orWhere('tickets.created_by', $email);
            });
        }

        // Clone the base query for each calculation to prevent filter overlap
        $totalReq = (clone $baseQuery)->where('tickets.status', 0)->count();
        $totalReqToday = (clone $baseQuery)->where('tickets.status', 0)->whereDate('tickets.created_at', today())->count();
        $totalInProgress = (clone $baseQuery)->where('tickets.status', 1)->count();
        $totalInProgressToday = (clone $baseQuery)->where('tickets.status', 1)->whereDate('tickets.created_at', today())->count();
        $totalClosed = (clone $baseQuery)->where('tickets.status', 2)->count();
        $totalClosedToday = (clone $baseQuery)->where('tickets.status', 2)->whereDate('tickets.created_at', today())->count();
        $total = (clone $baseQuery)->count();
        $totalToday = (clone $baseQuery)->whereDate('tickets.created_at', today())->count();

        return view('dashboard.index', compact('totalReq', 'totalReqToday', 'totalInProgress', 'totalInProgressToday', 'totalClosed', 'totalClosedToday', 'total', 'totalToday'));
    }
    public function switchTheme(Request $request)
    {
        DB::beginTransaction();
        try {
            $statusBefore = User::where('id', auth()->user()->id)->first()->is_darkmode;
            $status = ($statusBefore == 1) ? null : 1;
            User::where('id', auth()->user()->id)->update(['is_darkmode' => $status]);

            //Audit Log
            $this->auditLogs('Switch Theme');
            DB::commit();
            return redirect()->back()->with('success', __('messages.success_switch_theme'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['fail' => __('messages.fail_switch_theme')]);
        }
    }
}
