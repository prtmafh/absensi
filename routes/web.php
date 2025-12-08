<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DataKaryawanController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\RekapTahunanController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('admin.dashboard.index');
// });


Route::get('/', [AuthController::class, 'LoginForm'])->name('login-form');
Route::get('/login', [AuthController::class, 'LoginForm'])->name('login-form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth', 'admin')->group(function () {
    Route::get('/run-mark-absent', function (Request $request) {
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        // Jalankan command secara langsung
        Artisan::call('absensi:mark-absent-monthly', [
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data absensi berhasil diperbarui.']);
    })->name('run.mark.absent');

    // Route::get('/', [DashboardAdminController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    Route::get('/admin/daftar_karyawan', [DataKaryawanController::class, 'DaftarKaryawan'])->name('daftar_karyawan');
    Route::post('/admin/daftar_karyawan/store', [DataKaryawanController::class, 'storeDaftarKaryawan'])->name('karyawan.store');
    Route::put('/karyawan/{id}', [DataKaryawanController::class, 'updateDaftarKaryawan'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [DataKaryawanController::class, 'destroyDaftarKaryawan'])->name('karyawan.destroy');

    Route::get('/admin/daftar_admin', [DataKaryawanController::class, 'daftarAdmin'])->name('daftar_admin');

    Route::post('/admin/daftar_karyawan/users/store', [DataKaryawanController::class, 'storeUsers'])->name('users.store');
    Route::put('/users/{id}', [DataKaryawanController::class, 'updateUsers'])->name('users.update');

    Route::post('/admin/daftar_admin/users/store', [DataKaryawanController::class, 'storeUsers'])->name('admin.store');
    Route::put('/daftar_admin/{id}', [DataKaryawanController::class, 'updateUsers'])->name('admin.update');
    Route::delete('/admin/{id}', [DataKaryawanController::class, 'destroyAdmin'])->name('admin.destroy');

    Route::get('/admin/jabatan', [DataKaryawanController::class, 'jabatan'])->name('jabatan');
    Route::post('/admin/jabatan/store', [DataKaryawanController::class, 'storeJabatan'])->name('jabatan.store');
    Route::put('/admin/jabatan/{id}', [DataKaryawanController::class, 'updateJabatan'])->name('jabatan.update');
    Route::delete('/admin/jabatan/{id}', [DataKaryawanController::class, 'destroyJabatan'])->name('jabatan.destroy');

    Route::get('/admin/jenis_gaji', [GajiController::class, 'JenisGaji'])->name('jenis_gaji');
    Route::post('/admin/jenis_gaji/store', [GajiController::class, 'storeJenisGaji'])->name('jenis_gaji.store');
    Route::put('/admin/jenis_gaji/{id}', [GajiController::class, 'updateJenisGaji'])->name('jenis_gaji.update');
    Route::delete('/admin/jenis_gaji/{id}', [GajiController::class, 'deleteJenisGaji'])->name('jenis_gaji.destroy');

    Route::get('/admin/absensi', [AbsensiController::class, 'DataAbsen'])->name('data_absen');
    Route::get('/admin/rekap-tahunan', [RekapTahunanController::class, 'index'])->name('rekap.tahunan');
    Route::get('/admin/rekap-tahunan/{karyawan}', [RekapTahunanController::class, 'show'])->name('rekap.tahunan.show');

    Route::get('/admin/lembur', [AbsensiController::class, 'Lembur'])->name('data_lembur');
    Route::put('/admin/lembur/{id}/approve', [AbsensiController::class, 'approve'])->name('admin.lembur.approve');
    Route::put('/admin/lembur/{id}/reject', [AbsensiController::class, 'reject'])->name('admin.lembur.reject');

    Route::get('/admin/izin', [AbsensiController::class, 'Izin'])->name('data_izin');
    Route::put('/admin/izin/{id}/approve', [AbsensiController::class, 'approveIzin'])->name('admin.izin.approve');
    Route::put('/admin/izin/{id}/reject', [AbsensiController::class, 'rejectIzin'])->name('admin.izin.reject');

    Route::get('/admin/gaji', [GajiController::class, 'Gaji'])->name('gaji');
    Route::post('/generate/gaji', [GajiController::class, 'generate'])->name('gaji.generate');
    Route::get('/preview/gaji/{id_gaji}', [GajiController::class, 'preview'])->name('gaji.preview');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan/mass-update', [PengaturanController::class, 'massUpdate'])->name('pengaturan.massUpdate');
    Route::put('/pengaturan/{id}', [PengaturanController::class, 'autoUpdate'])->name('pengaturan.update');
    Route::put('/pengaturan/auto-update/{id_pengaturan}', [PengaturanController::class, 'autoUpdate'])->name('pengaturan.autoUpdate');
});

Route::middleware('auth')->group(function () {
    Route::get('/karyawan/dashboard', [KaryawanController::class, 'index'])->name('dashboard.karyawan');

    Route::post('/absen/masuk', [AbsensiController::class, 'absenMasuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [AbsensiController::class, 'absenPulang'])->name('absen.pulang');

    Route::get('/karyawan/absensi', [KaryawanController::class, 'absenKaryawan'])->name('absen.karyawan');

    Route::get('/karyawan/gaji', [KaryawanController::class, 'gajiKaryawan'])->name('gaji.karyawan');
    Route::get('/preview/gaji/karyawan/{id_gaji}', [KaryawanController::class, 'previewGaji'])->name('gaji.karyawan.preview');

    Route::get('/karyawan/lembur', [KaryawanController::class, 'Lembur'])->name('lembur.karyawan');
    Route::post('/lembur', [KaryawanController::class, 'storeLembur'])->name('lembur.store');

    Route::get('/karyawan/izin', [KaryawanController::class, 'izinKaryawan'])->name('izin.karyawan');
    Route::post('/izin', [KaryawanController::class, 'storeIzin'])->name('izin.store');
});
