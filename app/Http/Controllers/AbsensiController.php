<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Izin;
use App\Models\Lembur;
use App\Models\Pengaturan;
use Carbon\Carbon;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function DataAbsen()
    {
        $absen = Absen::with(['karyawan'])->orderBy('tanggal', 'desc')->get();
        $user = Auth::user();
        return view('admin.absensi.data_absen', compact('absen', 'user'));
    }

    public function Lembur()
    {
        $lembur = Lembur::with(['karyawan'])->orderBy('tanggal', 'desc')->get();

        return view('admin.absensi.data_lembur', compact('lembur', 'user'));
    }
    public function approve($id)
    {
        $lembur = Lembur::findOrFail($id);
        $lembur->update(['status' => 'disetujui']);

        return back()->with('success', 'Pengajuan lembur disetujui.');
    }

    public function reject($id)
    {
        $lembur = Lembur::findOrFail($id);
        $lembur->update(['status' => 'ditolak']);

        return back()->with('success', 'Pengajuan lembur ditolak.');
    }

    public function absenMasuk(Request $request)
    {
        $user = Auth::user();
        $karyawan_id = $user->karyawan_id;
        $tanggal = Carbon::today();

        $cek = Absen::where('karyawan_id', $karyawan_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($cek) {
            return response()->json(['status' => 'error', 'message' => 'Anda sudah absen hari ini.']);
        }

        $shiftStart = Pengaturan::where('nama', 'shift_start')->value('nilai') ?? '08:00';
        $toleransi = (int) Pengaturan::where('nama', 'late_tolerance_minutes')->value('nilai') ?? 10;

        $jamMasukSekarang = Carbon::now()->format('H:i');
        $batasTerlambat = Carbon::createFromFormat('H:i', $shiftStart)
            ->addMinutes($toleransi)
            ->format('H:i');

        $status = 'hadir';
        if ($jamMasukSekarang > $batasTerlambat) {
            $status = 'terlambat';
        }

        $fotoPath = null;
        if ($request->foto) {
            $image = str_replace('data:image/jpeg;base64,', '', $request->foto);
            $image = str_replace(' ', '+', $image);
            $fotoName = 'masuk_' . $karyawan_id . '_' . time() . '.jpg';
            Storage::disk('public')->put('absen/' . $fotoName, base64_decode($image));
            $fotoPath = 'absen/' . $fotoName;
        }

        $absen = new Absen();
        $absen->karyawan_id = $karyawan_id;
        $absen->tanggal = $tanggal;
        $absen->jam_masuk = Carbon::now()->format('H:i:s');
        $absen->latitude = $request->latitude;
        $absen->longitude = $request->longitude;
        $absen->status = $status;
        $absen->foto = $fotoPath;
        $absen->save();

        $pesan = $status === 'terlambat' ? 'Anda terlambat masuk kerja hari ini.' : 'Absen masuk berhasil disimpan!';
        return response()->json(['status' => 'success', 'message' => $pesan, 'status_absen' => $status]);
    }

    public function absenPulang(Request $request)
    {
        $user = Auth::user();
        $karyawan_id = $user->karyawan_id;
        $tanggal = Carbon::today();

        $absen = Absen::where('karyawan_id', $karyawan_id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if (!$absen) {
            return response()->json(['status' => 'error', 'message' => 'Anda belum absen masuk hari ini.']);
        }

        if ($absen->jam_pulang) {
            return response()->json(['status' => 'error', 'message' => 'Anda sudah absen pulang.']);
        }

        $absen->jam_pulang = Carbon::now()->format('H:i:s');
        $absen->save();

        return response()->json(['status' => 'success', 'message' => 'Absen pulang berhasil disimpan!']);
    }

    public function Izin()
    {
        $izin = Izin::with(['karyawan'])->orderBy('tanggal_izin', 'desc')->get();
        $user = Auth::user();
        return view('admin.absensi.data_izin_karyawan', compact('izin', 'user'));
    }
    public function approveIzin($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->update(['status' => 'disetujui']);

        return back()->with('success', 'Pengajuan izin disetujui.');
    }

    public function rejectIzin($id)
    {
        $izin = Izin::findOrFail($id);
        $izin->update(['status' => 'ditolak']);

        return back()->with('success', 'Pengajuan izin ditolak.');
    }
}
