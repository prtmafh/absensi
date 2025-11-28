<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::all();
        $user = Auth::user();
        return view('admin.dashboard.pengaturan', compact('pengaturan', 'user'));
    }

    public function autoUpdate(Request $request, $id)
    {
        $item = Pengaturan::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
        }

        $item->update(['nilai' => $request->nilai]);

        return response()->json(['success' => true, 'message' => 'Pengaturan diperbarui!']);
    }


    public function massUpdate(Request $request)
    {
        $ids = $request->id;

        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak ada data untuk diperbarui.');
        }

        foreach ($ids as $id) {
            $key = 'nilai_' . $id; // ambil nama field berdasarkan ID
            $value = $request->$key;

            if ($value !== null) {
                $item = Pengaturan::find($id);
                if ($item) {
                    $item->update(['nilai' => $value]);
                }
            }
        }

        return redirect()->back()->with('success', 'Semua pengaturan berhasil diperbarui!');
    }
}
