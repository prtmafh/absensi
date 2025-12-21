<?php

namespace App\Http\Controllers;

use App\Models\Libur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiburController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $libur = Libur::orderBy('tanggal', 'desc')->get();
        return view('admin.absensi.libur', compact('libur', 'user'));
    }
    public function data()
    {
        $items = Libur::orderBy('tanggal', 'asc')->get()
            ->map(function ($x) {
                return [
                    'id' => $x->id_libur,
                    'tanggal' => $x->tanggal->format('Y-m-d'),
                    'nama' => $x->nama,
                    'keterangan' => $x->keterangan,
                ];
            });

        return response()->json(['status' => 'ok', 'data' => $items]);
    }
    public function storeAjax(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date', 'unique:libur_nasional,tanggal'],
            'nama' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $row = Libur::create($data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Libur berhasil ditambahkan.',
            'data' => [
                'id' => $row->id_libur,
                'tanggal' => $row->tanggal->format('Y-m-d'),
                'nama' => $row->nama,
                'keterangan' => $row->keterangan,
            ]
        ]);
    }
    public function updateAjax(Request $request, Libur $liburNasional)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date', 'unique:libur_nasional,tanggal,' . $liburNasional->id_libur . ',id_libur'],
            'nama' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $liburNasional->update($data);

        return response()->json([
            'status' => 'ok',
            'message' => 'Libur berhasil diperbarui.',
        ]);
    }
    public function destroyAjax(Libur $liburNasional)
    {
        $liburNasional->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Libur berhasil dihapus.',
        ]);
    }
}
