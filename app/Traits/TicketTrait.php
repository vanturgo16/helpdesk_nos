<?php

namespace App\Traits;

use App\Models\Log;

trait TicketTrait
{
    public function activityLog($idTicket, $desc, $message, $url)
    {
        return Log::create([
            'id_ticket' => $idTicket,
            'created_by' => auth()->user()->email,
            'description' => $desc,
            'message' => $message,
            'attachment_1' => $url
        ]);
    }
}
