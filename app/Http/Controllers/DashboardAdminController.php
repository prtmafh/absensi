<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $user = Auth::user();

        // Ambil data
        $totalKaryawan = Karyawan::count();

        $hadir = Absen::whereDate('tanggal', $today)
            ->where('status', 'hadir')
            ->count();

        $izin = Absen::whereDate('tanggal', $today)
            ->where('status', 'izin')
            ->count();

        $alpha = Absen::whereDate('tanggal', $today)
            ->where('status', 'tidak hadir')
            ->count();
        $terlambat = Absen::whereDate('tanggal', $today)
            ->where('status', 'terlambat')
            ->count();

        // Belum absen = total karyawan - (hadir + izin + alpha)
        $belumAbsen = max(0, $totalKaryawan - ($hadir + $izin + $alpha + $terlambat));

        // Data absensi hari ini
        $absensiHariIni = Absen::with('karyawan')
            ->whereDate('tanggal', $today)
            ->orderBy('jam_masuk', 'asc')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalKaryawan',
            'hadir',
            'izin',
            'alpha',
            'belumAbsen',
            'absensiHariIni',
            'terlambat',
            'user'
        ));
    }
}
