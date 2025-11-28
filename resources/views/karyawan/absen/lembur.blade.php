@extends('layouts.karyawan.index')

@section('title', 'Data Lembur')

@push('styles')
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- ==========================
            CARD: FORM PENGAJUAN LEMBUR
            =========================== --}}
            <div class="card mb-5 shadow-sm">
                <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bolder fs-3 mb-0">Form Pengajuan Lembur</h3>
                </div>
                <div class="card-body py-4">
                    <form action="{{ route('lembur.store') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="jam_mulai" class="form-label fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="jam_selesai" class="form-label fw-semibold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-circle me-2"></i>Ajukan Lembur
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ==========================
            CARD: TABEL DATA LEMBUR
            =========================== --}}
            <div class="card mb-5 mb-xl-8 shadow-sm">
                <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bolder fs-3 mb-0">Data Lembur Karyawan</h3>
                </div>

                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table id="kt_datatable_example_1"
                            class="table table-row-dashed table-row-gray-300 align-middle   g-4">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Karyawan</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Total Jam</th>
                                    {{-- <th>Total Upah</th> --}}
                                    <th>Status</th>
                                    {{-- <th class="text-end">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lembur as $index => $l)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</td>
                                    <td class="text-capitalize">{{ $l->karyawan->nama }}</td>
                                    <td><span class="badge badge-light-dark">{{ $l->jam_mulai ?? '-' }}</span></td>
                                    <td><span class="badge badge-light-dark">{{ $l->jam_selesai ?? '-' }}</span></td>
                                    <td>{{ $l->total_jam }}</td>
                                    {{-- <td>Rp {{ number_format($l->total_upah, 0, ',', '.') }}</td> --}}
                                    <td>
                                        @if($l->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif($l->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                        @else
                                        <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    {{-- <td class="text-end">
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                                            title="Lihat Detail">
                                            <span class="svg-icon svg-icon-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M22 12C20 8 16 5 12 5S4 8 2 12c2 4 6 7 10 7s8-3 10-7Z"
                                                        fill="currentColor" />
                                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </a>
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush