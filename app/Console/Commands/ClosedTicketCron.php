<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

// Mail
use App\Mail\CloseTicket;

// Traits
use App\Traits\TicketTrait;

// Model
use App\Models\Ticket;
use App\Models\LogTicket;
use App\Models\User;
use App\Models\MstRules;

class ClosedTicketCron extends Command
{
    use TicketTrait;

    protected $signature = 'ClosedTicketCron';
    protected $description = 'Automatically Closed Ticket Where Last Assign Is Preclose';

    public function handle()
    {
        $today = Carbon::today();
        $ticketProgress = Ticket::where('status', 1)->get();

        foreach ($ticketProgress as $item) {
            // Get Latest Assign
            $lastAssign = LogTicket::where('id_ticket', $item->id)->orderBy('created_at', 'desc')->first();
            if($lastAssign->preclosed_status == 1){
                $closeBy = 'Scheduller'; $url = null;
                $dataTicket = Ticket::select('tickets.*', 'users.name as requestorName')
                    ->where('tickets.id', $item->id)
                    ->leftjoin('users', 'tickets.created_by', 'users.email')
                    ->first();
                // Initiate Email
                $dealerTypeHandle = MstRules::where('rule_name', 'Type Dealer Handle Ticket')->pluck('rule_value')->toArray();
                $assignToDept = LogTicket::where('id_ticket', $item->id)->pluck('assign_to_dept')->toArray();
                $roleHandle = MstRules::where('rule_name', 'Type Role Handle Ticket')->pluck('rule_value')->toArray();
                $emailAssigns = User::whereIn('dealer_type', $dealerTypeHandle)
                    ->whereIn('department', $assignToDept)->whereIn('role', $roleHandle)
                    ->pluck('email')->toArray();
                $development = MstRules::where('rule_name', 'Development')->first()->rule_value;
                $emailDev = MstRules::where('rule_name', 'Email Development')->pluck('rule_value')->toArray();
                $toEmail = ($development == 1) ? $emailDev : $emailAssigns;
                $ccEmail = ($development == 1) ? $emailDev : $dataTicket->created_by;
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
                    // Update Ticket
                    Ticket::where('id', $item->id)->update([
                        'closed_notes' => 'Closed By Scheduller',
                        'closed_date' => now()->format('Y-m-d H:i'),
                        'duration' => $duration,
                        'status' => 2,
                    ]);
                    // Re-Query To Get Updated Data
                    $dataTicket = Ticket::where('id', $item->id)->first();

                    // Send Email
                    $mailContent = new CloseTicket($dataTicket, $assignToDept, $url, $closeBy);
                    Mail::to($toEmail)->cc($ccEmail)->send($mailContent);

                    // Activity Ticket Log
                    $this->activityLog($item->id, 'Close Ticket', 'Closed By Scheduller', $url);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                }
            }
        }

        echo ('Success Running Command at ' . $today);
    }
}
