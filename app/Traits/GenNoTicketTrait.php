<?php

namespace App\Traits;

use App\Models\NoTicketGen;
use Carbon\Carbon;

trait GenNoTicketTrait
{
    public function getOrCreateNoTicket($create = false)
    {
        $year = Carbon::now()->format('y');
        $month = Carbon::now()->format('m');
        // Find the latest record for this year and month (Lock the row to prevent race conditions)
        $record = NoTicketGen::where('year', $year)->where('month', $month)->orderByDesc('no_urut')->lockForUpdate()->first();
        $noUrut = $record ? $record->no_urut + 1 : 1;
        if ($create) {
            NoTicketGen::create([
                'year' => $year,
                'month' => $month,
                'no_urut' => $noUrut
            ]);
        }
        // Format no_urut to 4 digits
        $noUrutFormatted = str_pad($noUrut, 4, '0', STR_PAD_LEFT);
        return "T/{$year}{$month}/{$noUrutFormatted}";
    }
    public function showNoTicket()
    {
        return $this->getOrCreateNoTicket(false);
    }
    public function genNoTicket()
    {
        return $this->getOrCreateNoTicket(true);
    }
}
