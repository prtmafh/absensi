<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Gaji;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\Lembur;
use App\Models\Pengaturan;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tanggal = Carbon::today();
        $karyawan_id = $user->karyawan_id;
        $karyawan = Karyawan::where('id_karyawan', $karyawan_id)->first();

        $shift_start = Pengaturan::where('nama', 'shift_start')->value('nilai');
        $shift_end = Pengaturan::where('nama', 'shift_end')->value('nilai');

        $cek = Absen::with('karyawan')->where('karyawan_id', $karyawan_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        $namaKaryawan = $karyawan->nama ?? $user->username;

        return view('karyawan.dashboard.index', compact('cek', 'namaKaryawan', 'shift_start', 'shift_end', 'karyawan'));
    }

    public function absenKaryawan()
    {
        $user = Auth::user();
        $karyawan_id = $user->karyawan_id;

        $karyawan = Karyawan::where('id_karyawan', $karyawan_id)->first();

        $absen = Absen::with('karyawan')->where('karyawan_id', $karyawan_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('karyawan.absen.index', compact('absen', 'karyawan'));
    }

    public function gajiKaryawan()
    {
        $user = Auth::user();
        $karyawan_id = $user->karyawan_id;
        $karyawan = Karyawan::where('id_karyawan', $karyawan_id)->first();

        $gaji = Gaji::with('karyawan')->where('karyawan_id', $karyawan_id)->orderByDesc('periode_tahun')->orderByDesc('periode_bulan')->get();

        return view('karyawan.gaji.index', compact('gaji', 'karyawan'));
    }

    public function previewGaji($id_gaji)
    {
        $gaji = Gaji::with('karyawan.jenisGaji')->findOrFail($id_gaji);


        $service = new PayrollService();
        $calc = $service->calculateForOne($gaji->karyawan_id, $gaji->periode_bulan, $gaji->periode_tahun);

        return response()->json([
            'karyawan' => $gaji->karyawan->nama,
            'periode' => \Carbon\Carbon::create()->month($gaji->periode_bulan)->format('F') . ' ' . $gaji->periode_tahun,
            'upah_dasar' => number_format($calc['upah_dasar'], 0, ',', '.'),
            'total_hadir' => $calc['total_hadir'],
            'total_terlambat' => $calc['total_terlambat'],
            'late_minutes' => $calc['late_minutes'],
            'late_penalty' => number_format($calc['late_penalty'], 0, ',', '.'),
            'izin' => $calc['total_izin'],
            'tidak_hadir' => $calc['total_tidak_hadir'],
            'lembur' => number_format($calc['total_lembur_upah'], 0, ',', '.'),
            'potongan' => number_format($calc['potongan'], 0, ',', '.'),
            'total_gaji' => number_format($calc['total_gaji'], 0, ',', '.'),
        ]);
    }

    public function Lembur()
    {
        $user = Auth::user();
        $karyawan_id = $user->karyawan_id;
        $karyawan = Karyawan::where('id_karyawan', $karyawan_id)->first();


        $lembur = Lembur::with(['karyawan'])->where('karyawan_id', $karyawan_id)->orderBy('tanggal', 'desc')->get();
        return view('karyawan.absen.lembur', compact('lembur', 'karyawan'));
    }



    public function storeLembur(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jamMulai = Carbon::parse($request->jam_mulai);
        $jamSelesai = Carbon::parse($request->jam_selesai);

        if ($jamSelesai->lessThan($jamMulai)) {
            $jamSelesai->addDay();
        }

        $totalJam = $jamMulai->floatDiffInHours($jamSelesai);

        $upahPerJam = Pengaturan::getValue('tarif_lembur') ?? 15000;

        $totalUpah = $totalJam * $upahPerJam;

        Lembur::create([
            'karyawan_id' => Auth::user()->karyawan_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'total_jam' => $totalJam,
            'total_upah' => $totalUpah,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan lembur berhasil dikirim dan menunggu persetujuan.');
    }

    public function izinKaryawan()
    {
        $user = Auth::user();
        $karyawan_id = $user->karyawan_id;
        $karyawan = Karyawan::where('id_karyawan', $karyawan_id)->first();
        $tahun = date('Y');
        $kuota_total = $karyawan->kuota_izin ?? 12;
        $kuota_terpakai = Izin::where('karyawan_id', $karyawan_id)
            ->where('status', 'disetujui')
            ->whereYear('tanggal_izin', $tahun)
            ->count();
        $kuota_sisa = max(0, $kuota_total - $kuota_terpakai);

        $dipakai = Izin::where('karyawan_id', $karyawan_id)
            ->where('status', 'disetujui')
            ->whereYear('tanggal_izin', $tahun)
            ->count();

        $izin = Izin::with('karyawan')->where('karyawan_id', $karyawan_id)->orderBy('tanggal_izin', 'desc')->get();

        return view('karyawan.absen.izin', compact('izin', 'karyawan', 'kuota_total', 'kuota_terpakai', 'kuota_sisa'));
    }

    public function storeIzin(Request $request)
    {
        $user = Auth::user();
        $karyawan_id = $user->karyawan_id;

        // Hitung izin yang sudah dipakai tahun ini
        $tahun = date('Y');

        $dipakai = Izin::where('karyawan_id', $karyawan_id)
            ->where('status', 'disetujui')
            ->whereYear('tanggal_izin', $tahun)
            ->count();

        $kuota = $user->karyawan->kuota_izin; // default 12

        if ($dipakai >= $kuota) {
            return back()->with('error', 'Kuota izin tahunan Anda sudah habis');
        } else {


            $request->validate([
                'tanggal_izin' => 'required|date',
                'jenis_izin' => 'required|in:sakit,izin,cuti,lainnya',
                'keterangan' => 'nullable|string',
                'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $lampiranPath = $request->file('lampiran')->store('lampiran_izin', 'public');
            }

            Izin::create([
                'karyawan_id' => Auth::user()->karyawan_id,
                'tanggal_izin' => $request->tanggal_izin,
                'jenis_izin' => $request->jenis_izin,
                'keterangan' => $request->keterangan,
                'lampiran' => $lampiranPath,
                'status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.');
        }
    }
}
