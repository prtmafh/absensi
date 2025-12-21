<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Gaji;
use App\Models\JenisGaji;
use App\Services\PayrollService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GajiController extends Controller
{
    public function JenisGaji()
    {
        $jenis_gaji = JenisGaji::all();
        $user = Auth::user();

        return view('admin.penggajian.jenis_gaji', compact('jenis_gaji', 'user'));
    }

    public function storeJenisGaji(Request $request)
    {
        $validated = $request->validate([
            'sistem_gaji' => 'required|string|max:100',
            'upah' => 'required|numeric',
        ]);

        JenisGaji::create($validated);

        return redirect()->back()->with('success', 'Data gaji berhasil ditambahkan.');
    }
    public function updateJenisGaji(Request $request, $id)
    {
        $validated = $request->validate([
            'sistem_gaji' => 'required|string|max:100',
            'upah' => 'required|numeric',
        ]);

        JenisGaji::where('id_jenis_gaji', $id)->update($validated);

        return redirect()->back()->with('success', 'Data gaji berhasil diperbarui.');
    }
    public function deleteJenisGaji($id)
    {
        JenisGaji::where('id_jenis_gaji', $id)->delete();

        return redirect()->back()->with('success', 'Data gaji berhasil dihapus.');
    }


    public function Gaji()
    {
        $gaji = Gaji::with('karyawan')->orderByDesc('periode_tahun')->orderByDesc('periode_bulan')->get();
        $user = Auth::user();
        return view('admin.penggajian.gaji', compact('gaji', 'user'));
    }


    public function generate(Request $request, PayrollService $payroll)
    {
        $request->validate([
            'periode_bulan' => 'required|integer|min:1|max:12',
            'periode_tahun' => 'required|integer|min:2000',
        ]);

        // Jalankan proses generate gaji
        $summary = $payroll->generateForAll(
            (int) $request->periode_bulan,
            (int) $request->periode_tahun
        );

        // Redirect kembali dengan notifikasi hasil
        return back()->with(
            'success',
            "Generate gaji selesai. Dibuat: {$summary['created']}, Dilewati: {$summary['skipped']}."
        );
    }

    /**
     * Preview slip gaji karyawan secara AJAX (JSON).
     */
    public function preview($id_gaji)
    {
        $gaji = Gaji::with('karyawan.jenisGaji')->findOrFail($id_gaji);

        $service = new PayrollService();
        $calc = $service->calculateForOne(
            $gaji->karyawan_id,
            $gaji->periode_bulan,
            $gaji->periode_tahun
        );

        $periode = Carbon::create()
            ->month($gaji->periode_bulan)
            ->year($gaji->periode_tahun)
            ->locale('id') // agar nama bulan Indonesia (opsional)
            ->translatedFormat('F Y');

        return response()->json([
            'karyawan' => $gaji->karyawan->nama,
            'periode' => $periode,
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
            'jenis_gaji' => $gaji->karyawan->jenisGaji->sistem_gaji,
        ]);
    }

    public function pdfSlip($id_gaji)
    {
        $gaji = Gaji::with('karyawan.jenisGaji')->findOrFail($id_gaji);

        $service = new PayrollService();
        $calc = $service->calculateForOne(
            $gaji->karyawan_id,
            $gaji->periode_bulan,
            $gaji->periode_tahun
        );

        $periode = Carbon::create()
            ->month($gaji->periode_bulan)
            ->year($gaji->periode_tahun)
            ->locale('id')
            ->translatedFormat('F Y');

        $data = [
            'karyawan' => $gaji->karyawan->nama,
            'periode' => $periode,
            'jenis_gaji' => $gaji->karyawan->jenisGaji->sistem_gaji ?? '-',

            'upah_dasar' => number_format($calc['upah_dasar'] ?? 0, 0, ',', '.'),
            'total_hadir' => $calc['total_hadir'] ?? 0,
            'total_terlambat' => $calc['total_terlambat'] ?? 0,
            'late_minutes' => $calc['late_minutes'] ?? 0,
            'late_penalty' => number_format($calc['late_penalty'] ?? 0, 0, ',', '.'),

            'izin' => $calc['total_izin'] ?? 0,
            'tidak_hadir' => $calc['total_tidak_hadir'] ?? 0,

            'lembur' => number_format($calc['total_lembur_upah'] ?? 0, 0, ',', '.'),
            'potongan' => number_format($calc['potongan'] ?? 0, 0, ',', '.'),
            'total_gaji' => number_format($calc['total_gaji'] ?? 0, 0, ',', '.'),

            'id_gaji' => $gaji->id_gaji,
            'tanggal_cetak' => now()->locale('id')->translatedFormat('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.penggajian.cetak-slip', $data)
            ->setPaper('A4', 'portrait');

        //review in browser:
        // return $pdf->stream("slip-gaji-{$gaji->id_gaji}.pdf");

        //download:
        return $pdf->download("slip-gaji-{$gaji->id_gaji}.pdf");
    }
}
