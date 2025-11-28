<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\JenisGaji;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataKaryawanController extends Controller
{
    public function DaftarKaryawan()
    {
        $jabatan = Jabatan::all();
        $jenis_gaji = JenisGaji::all();
        $karyawan = Karyawan::with(['jabatan', 'jenisGaji', 'user'])->orderBy('tgl_masuk', 'desc')->get();
        $user = Auth::user();
        // $karyawan = Karyawan::whereHas('user', function ($query) {
        //     $query->where('role_user', '!=', 'admin');
        // })->with(['jabatan', 'jenisGaji', 'user'])->orderBy('tgl_masuk', 'desc')->get();

        return view('admin.data_karyawan.daftar_karyawan', compact('jabatan', 'jenis_gaji', 'karyawan', 'user'));
    }


    public function storeDaftarKaryawan(Request $request)
    {
        $validated = $request->validate([
            'jabatan_id'     => 'required|exists:jabatan,id_jabatan',
            'nama'           => 'required|string|max:100',
            'alamat'         => 'required|string',
            'no_hp'          => 'nullable|string|max:20',
            'tgl_masuk'      => 'required|date',
            'jenis_gaji_id'  => 'required|exists:jenis_gaji,id_jenis_gaji',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_karyawan', 'public');
            $validated['foto'] = $fotoPath;
        }

        $karyawan = Karyawan::create($validated);

        $user = User::where('karyawan_id', $karyawan->id_karyawan)->first();
        if ($user) {
            $karyawan->update(['status' => $user->status]);
        }

        return redirect()->back()->with('success', 'Data karyawan berhasil ditambahkan.');
    }


    public function updateDaftarKaryawan(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'jabatan_id'     => 'required|exists:jabatan,id_jabatan',
            'nama'           => 'required|string|max:100',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:20',
            'tgl_masuk'      => 'required|date',
            'jenis_gaji_id'  => 'required|exists:jenis_gaji,id_jenis_gaji',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }

            $fotoPath = $request->file('foto')->store('foto_karyawan', 'public');
            $validated['foto'] = $fotoPath;
        }

        $karyawan->update($validated);

        $user = User::where('karyawan_id', $karyawan->id_karyawan)->first();

        if ($user) {
            $karyawan->update(['status' => $user->status]);
        } else {
            $karyawan->update(['status' => 'nonaktif']);
        }

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroyDaftarKaryawan($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->delete();

        return redirect()->back()->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function storeUsers(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6',
            'role_user' => 'required|in:admin,karyawan',
            'karyawan_id' => 'nullable|exists:karyawan,id_karyawan',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role_user' => $request->role_user,
            'karyawan_id' => $request->karyawan_id,
            'status' => $request->status,
        ]);

        if ($user->karyawan_id) {
            $karyawan = Karyawan::find($user->karyawan_id);

            if ($karyawan) {
                $karyawan->update(['status' => $user->status]);
            }
        }

        return redirect()->back()->with('success', 'User akses berhasil ditambahkan');
    }


    public function updateUsers(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id_user . ',id_user',
            'role_user' => 'required|in:admin,karyawan',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->only(['username', 'role_user', 'status']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        optional($user->karyawan)->update(['status' => $user->status]);


        return redirect()->back()->with('success', 'User akses berhasil diperbarui!');
    }


    public function jabatan()
    {
        $jabatan = Jabatan::all();
        $user = Auth::user();

        return view('admin.data_karyawan.jabatan', compact('jabatan', 'user'));
    }

    public function storeJabatan(Request $request)
    {
        $validated = $request->validate([

            'nama_jabatan' => 'required|string|max:100',

        ]);

        Jabatan::create($validated);

        return redirect()->back()->with('success', 'Data Jabatan berhasil ditambahkan.');
    }

    public function updateJabatan(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $validated = $request->validate([
            'nama_jabatan'   => 'required|string|max:100',
        ]);

        $jabatan->update($validated);

        return redirect()->back()->with('success', 'Data Jabatan berhasil diperbarui.');
    }

    public function destroyJabatan($id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $jabatan->delete();

        return redirect()->back()->with('success', 'Data Jabatan berhasil dihapus.');
    }

    public function daftarAdmin()
    {
        $jabatan = Jabatan::all();
        $jenis_gaji = JenisGaji::all();
        // $karyawan = Karyawan::whereHas('user', function ($query) {
        //     $query->where('role_user', '=', 'admin');
        // })->with(['jabatan', 'jenisGaji', 'user'])->orderBy('tgl_masuk', 'desc')->get();
        $user = Auth::user();
        $users = User::where('role_user', 'admin')->get();

        return view('admin.data_karyawan.daftar_admin', compact('jabatan', 'jenis_gaji', 'user', 'users'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6',
            'role_user' => 'required|in:admin,karyawan',
            'karyawan_id' => 'nullable|exists:karyawan,id_karyawan',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role_user' => $request->role_user,
            'karyawan_id' => $request->karyawan_id,
            'status' => $request->status,
        ]);

        if ($user->karyawan_id) {
            $karyawan = Karyawan::find($user->karyawan_id);

            if ($karyawan) {
                $karyawan->update(['status' => $user->status]);
            }
        }

        return redirect()->back()->with('success', 'Data admin berhasil ditambahkan');
    }


    public function updateAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id_user . ',id_user',
            'role_user' => 'required|in:admin,karyawan',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->only(['username', 'role_user', 'status']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        optional($user->karyawan)->update(['status' => $user->status]);


        return redirect()->back()->with('success', 'Data admin berhasil diperbarui!');
    }

    public function destroyAdmin($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->back()->with('success', 'Data admin berhasil dihapus.');
    }
}
