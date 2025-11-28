@extends('layouts.admin.index')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl">

        <!--begin::Card-->
        <div class="card shadow-sm mb-5">
            <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold fs-3 text-primary mb-0">
                    <i class="bi bi-gear-fill me-2 text-primary"></i> Pengaturan Sistem
                </h3>
                <a href="{{ route('pengaturan.index') }}" class="btn btn-light-primary btn-sm fw-bold">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                </a>
            </div>

            <div class="card-body py-5">
                <table class="table align-middle table-hover gy-4">
                    <thead class="bg-light fw-bold">
                        <tr class="text-center text-gray-800 fw-semibold">
                            <th style="width:5%">No</th>
                            <th>Pengaturan</th>
                            <th style="width:40%">Nilai</th>
                            {{-- <th style="width:10%">Tipe</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengaturan as $index => $item)
                        @php
                        $tooltip = match($item->nama) {
                        'shift_start' => 'Jam mulai kerja karyawan (format 24 jam)',
                        'shift_end' => 'Jam selesai kerja karyawan (format 24 jam)',
                        'tarif_lembur' => 'Nominal lembur yang dibayarkan per jam (Rp)',
                        'late_tolerance_minutes' => 'Batas toleransi keterlambatan tanpa denda (menit)',
                        'work_minutes_per_day' => 'Total menit kerja per hari (480 = 8 jam)',
                        'daily_divisor' => 'Jumlah hari kerja sebulan untuk hitung gaji harian',
                        'late_penalty_mode' => 'Mode denda: proportional atau flat',
                        'late_flat_per_minutes' => 'Jika flat, kelipatan menit per denda',
                        'late_flat_amount' => 'Jumlah denda per kelipatan menit (Rp)',
                        'deduct_izin_for_monthly' => 'Apakah izin memotong gaji bulanan?',
                        default => null
                        };
                        @endphp

                        <tr>
                            <td class="text-center fw-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-dark">{{ $item->label ?? $item->nama }}</span>
                                    @if($tooltip)
                                    <span class="ms-2" data-bs-toggle="tooltip" title="{{ $tooltip }}">
                                        <i class="bi bi-info-circle-fill text-primary fs-6"></i>
                                    </span>
                                    @endif
                                </div>
                                {{-- <small class="text-muted fst-italic">{{ $item->nama }}</small> --}}
                            </td>
                            <td>
                                @if($item->tipe == 'time')
                                <input type="time" data-id="{{ $item->id_pengaturan }}"
                                    class="form-control form-control-solid input-auto" value="{{ $item->nilai }}">
                                @elseif($item->tipe == 'integer')
                                <input type="number" data-id="{{ $item->id_pengaturan }}"
                                    class="form-control form-control-solid input-auto" value="{{ $item->nilai }}">
                                @elseif($item->tipe == 'boolean')
                                <select data-id="{{ $item->id_pengaturan }}"
                                    class="form-select form-select-solid input-auto">
                                    <option value="true" {{ $item->nilai == 'true' ? 'selected' : '' }}>Ya</option>
                                    <option value="false" {{ $item->nilai == 'false' ? 'selected' : '' }}>Tidak</option>
                                </select>
                                @else
                                <input type="text" data-id="{{ $item->id_pengaturan }}"
                                    class="form-control form-control-solid input-auto" value="{{ $item->nilai }}">
                                @endif
                                <div class="status-indicator mt-1" style="display:none;">
                                    <small class="text-muted">⏳ Menyimpan...</small>
                                </div>
                            </td>
                            {{-- <td class="text-center">
                                <span class="badge badge-light-info">{{ $item->tipe }}</span>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Tooltip aktif
    const tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipList.map(el => new bootstrap.Tooltip(el));

    // Deteksi perubahan otomatis
    document.querySelectorAll('.input-auto').forEach(input => {
        input.addEventListener('change', async function() {
            const id = this.dataset.id;
            const value = this.value;
            const row = this.closest('td');
            const status = row.querySelector('.status-indicator');

            if (!id) {
                Swal.fire('Error', 'ID pengaturan tidak ditemukan.', 'error');
                return;
            }

            status.style.display = 'block';
            status.innerHTML = '<small class="text-muted">⏳ Menyimpan...</small>';

            try {
                const res = await fetch(`/pengaturan/auto-update/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nilai: value })
                });

                const data = await res.json();

                if (data.success) {
                    status.innerHTML = '<small class="text-success">✅ Tersimpan</small>';
                    setTimeout(() => status.style.display = 'none', 1500);
                } else {
                    status.innerHTML = '<small class="text-danger">❌ Gagal menyimpan</small>';
                }
            } catch (error) {
                status.innerHTML = '<small class="text-danger">⚠️ Koneksi gagal</small>';
                Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error');
            }
        });
    });
});
</script>
@endpush
@endsection