<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index()
    {
        $last = Log::latest()->first();

        $logs = DB::table('logs')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('MIN(CASE WHEN status = "ON" THEN created_at END) as jam_on'),
                DB::raw('MAX(CASE WHEN status = "OFF" THEN created_at END) as jam_off')
            )
            ->groupBy('tanggal')
            ->get()
            ->map(function ($log) {
                $jam_on = Carbon::parse($log->jam_on);
                $jam_off = Carbon::parse($log->jam_off);
                $log->durasi_operasional = $jam_on->diff($jam_off)->format('%H:%I:%S');
                return $log;
            });

        return view('welcome', compact('last', 'logs'));
    }

    public function log(string $status)
    {
        if ($status == 'ON') {
            $last = Log::create(['status' => 'OFF']);
        } else {
            $last = Log::create(['status' => 'ON']);
        }

        return redirect('/');
    }
}
