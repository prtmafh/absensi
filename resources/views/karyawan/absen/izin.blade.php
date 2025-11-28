@extends('layouts.karyawan.index')

@section('title', 'Data Izin Karyawan')

@push('styles')
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            {{-- ==========================
            CARD: KUOTA IZIN
            =========================== --}}
            <div class="card mb-5 shadow-sm">
                <div class="card-body py-5">

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-45px me-4">
                                <span class="symbol-label bg-primary bg-opacity-10">
                                    <i class="bi bi-calendar-check text-primary fs-2"></i>
                                </span>
                            </div>
                            <div>
                                <h3 class="fw-bolder fs-3 mb-1">Kuota Izin Tahunan</h3>
                                <div class="text-muted fs-7">
                                    Maksimal <strong>{{ $kuota_total }}</strong> izin setiap tahun.
                                </div>
                            </div>
                        </div>

                        {{-- Badge status --}}
                        <div>
                            @if($kuota_sisa <= 0) <span class="badge bg-danger fs-7">Kuota Habis</span>
                                @elseif($kuota_sisa <= 4) <span class="badge bg-warning fs-7">Hampir Habis</span>
                                    @else
                                    <span class="badge bg-success fs-7">Masih Tersedia</span>
                                    @endif
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    @php
                    $persen = ($kuota_terpakai / $kuota_total) * 100;
                    $progressColor = $kuota_sisa <= 0 ? 'bg-danger' : ($kuota_sisa <=4 ? 'bg-warning' : 'bg-success' );
                        @endphp <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-semibold">Progress Penggunaan Izin</span>
                            <span class="fw-bold">{{ number_format($persen, 0) }}%</span>
                        </div>

                        <div class="progress h-7px">
                            <div class="progress-bar {{ $progressColor }} progress-bar-striped progress-bar-animated"
                                role="progressbar" style="width: {{ $persen }}%"></div>
                        </div>
                </div>

                {{-- Detail Kuota --}}
                <div class="row g-5 mt-3 mb-3">

                    {{-- Total --}}
                    <div class="col-md-4">
                        <div class="p-4 rounded shadow-sm" style="background:#ffffff;">
                            <span class="fs-7 text-muted">Kuota Total</span>
                            <h2 class="fw-bold mt-1">{{ $kuota_total }} Hari</h2>
                        </div>
                    </div>

                    {{-- Terpakai --}}
                    <div class="col-md-4">
                        <div class="p-4 rounded shadow-sm bg-light-primary">
                            <span class="fs-7 text-muted">Terpakai</span>
                            <h2 class="fw-bold text-primary mt-1">{{ $kuota_terpakai }} Hari</h2>
                        </div>
                    </div>

                    {{-- Sisa --}}
                    <div class="col-md-4">
                        <div class="p-4 rounded shadow-sm @if($kuota_sisa <= 0) bg-light-danger 
                                        @elseif($kuota_sisa <= 4) bg-light-warning 
                                        @else bg-light-success 
                                        @endif
                                        ">
                            <span class="fs-7 text-muted">Sisa Kuota</span>
                            <h2 class="fw-bold mt-1 
                                        @if($kuota_sisa <= 0) text-danger 
                                        @elseif($kuota_sisa <= 4) text-warning 
                                        @else text-success 
                                        @endif
                                    ">
                                {{ $kuota_sisa }} Hari
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ==========================
        CARD: FORM PENGAJUAN IZIN
        =========================== --}}
        <div class="card mb-5 shadow-sm">
            <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bolder fs-3 mb-0">Form Pengajuan Izin</h3>
            </div>

            <div class="card-body py-4">
                <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf

                    {{-- Tanggal Izin --}}
                    <div class="col-md-4">
                        <label for="tanggal_izin" class="form-label fw-semibold">Tanggal Izin</label>
                        <input type="date" name="tanggal_izin" id="tanggal_izin" class="form-control" required>
                    </div>

                    {{-- Jenis Izin --}}
                    <div class="col-md-4">
                        <label for="jenis_izin" class="form-label fw-semibold">Jenis Izin</label>
                        <select name="jenis_izin" id="jenis_izin" class="form-control" required>
                            <option value="sakit">Sakit</option>
                            <option value="izin">Izin</option>
                            <option value="cuti">Cuti</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    {{-- Lampiran --}}
                    <div class="col-md-4">
                        <label for="lampiran" class="form-label fw-semibold">Lampiran (Opsional)</label>
                        <input type="file" name="lampiran" id="lampiran" class="form-control">
                    </div>


                    {{-- Keterangan --}}
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control"
                            placeholder="Misal: sakit demam tinggi, butuh istirahat"></textarea>
                    </div>
                    <div class="row g-3 ">

                        {{-- Button Submit --}}
                        <div class="col-md-3 d-flex align-items-end ">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle me-2"></i>Ajukan Izin
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==========================
        CARD: TABEL DATA IZIN
        =========================== --}}
        <div class="card mb-5 mb-xl-8 shadow-sm">
            <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bolder fs-3 mb-0">Data Izin Karyawan</h3>
            </div>

            <div class="card-body py-3">
                <div class="table-responsive">
                    <table id="kt_datatable_example_1"
                        class="table table-row-dashed table-row-gray-300 align-middle g-4">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jenis Izin</th>
                                <th>Keterangan</th>
                                <th>Lampiran</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($izin as $index => $i)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($i->tanggal_izin)->format('d M Y') }}</td>
                                <td class="text-capitalize">{{ $i->jenis_izin }}</td>
                                <td>{{ $i->keterangan ?? '-' }}</td>

                                {{-- Lampiran --}}
                                <td>
                                    @if($i->lampiran)
                                    <a href="{{ asset('storage/' . $i->lampiran) }}" target="_blank"
                                        data-bs-toggle="modal" data-bs-target="#kt_modal_lihat_foto"
                                        data-id="{{ $i->id_izin }}"
                                        data-foto="{{ $i->lampiran ? asset('storage/'.$i->lampiran) : '' }}"
                                        class="btn btn-sm btn-light-primary">
                                        Lihat
                                    </a>
                                    @else
                                    <span class="badge bg-secondary text-black">Tidak Ada</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($i->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($i->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                    @else
                                    <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Modal Lihat Foto --}}
            <div class="modal fade" id="kt_modal_lihat_foto" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content rounded">
                        <div class="modal-header pb-0 border-0 justify-content-end">
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                            transform="rotate(-45 6 17.3137)" fill="black" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                            transform="rotate(45 7.41422 6)" fill="black" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                            <form id="kt_modal_edit_karyawan_form" class="form" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-13 text-center">
                                    <h1 class="mb-3">Lampiran</h1>
                                </div>

                                <!-- Foto -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    {{-- <label class="fs-6 fw-bold mb-2">Foto</label> --}}

                                    <div id="foto_preview" class="mt-3 text-center" style="display:none;">
                                        <img src="" alt="Foto Karyawan" class="rounded shadow-sm" style="
                                                            width: 100%;
                                                            max-width: 350px;
                                                            height: auto;
                                                            border-radius: 10px;
                                                            object-fit: cover;
                                                            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                                         ">
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="text-center">
                                    <button type="button" class="btn btn-light me-3"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('kt_modal_lihat_foto');
    
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        
        // Get ID from button
        const id = button.getAttribute('data-id');
        
        // Handle photo preview
        const fotoUrl = button.getAttribute('data-foto');
        const fotoPreview = document.getElementById('foto_preview');
        console.log('Preview element:', fotoPreview);
        if (fotoUrl) {
            fotoPreview.style.display = 'block';
            fotoPreview.querySelector('img').src = fotoUrl;
        } else {
            fotoPreview.style.display = 'none';
        }
    });
});
</script>
@endpush