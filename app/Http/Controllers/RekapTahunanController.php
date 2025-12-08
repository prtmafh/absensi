<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapTahunanController extends Controller
{
    /**
     * @var AttendanceService
     */
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Tampilkan rekap kehadiran tahunan untuk semua karyawan.
     *
     * URL contoh:
     * GET /admin/rekap-tahunan?tahun=2025
     */
    public function index(Request $request)
    {
        // Default ke tahun sekarang kalau tidak ada parameter
        $user = Auth::user();
        $tahun = (int) $request->input('tahun', Carbon::now()->year);

        // Ambil data rekap dari service
        $rekap = $this->attendanceService->getYearlyRecap($tahun);

        // Kirim ke view
        return view('admin.absensi.rekap_absen_tahunan', [
            'rekap' => $rekap,
            'tahun' => $tahun,
            'user'  => $user,
        ]);
    }

    /**
     * Tampilkan rekap tahunan untuk satu karyawan.
     *
     * URL contoh:
     * GET /admin/rekap-tahunan/5?tahun=2025
     */
    public function show(Request $request, $karyawanId)
    {
        $user = Auth::user();
        $tahun = (int) $request->input('tahun', Carbon::now()->year);

        $karyawan = Karyawan::findOrFail($karyawanId);

        $detail = $this->attendanceService->getYearlyRecapForKaryawan($tahun, $karyawan->id_karyawan);

        return view('admin.absensi.detail_rekap', [
            'karyawan' => $karyawan,
            'detail'   => $detail,
            'tahun'    => $tahun,
            'user'     => $user,
        ]);
    }
}
