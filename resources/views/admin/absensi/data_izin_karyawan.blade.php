@extends('layouts.admin.index')

@section('title', 'Data Izin Karyawan')

@push('styles')

@endpush


@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <div class="card mb-5 mb-xl-8 shadow-sm">
                <div class="card-header border-0 pt-5 d-flex justify-content-between">
                    <h3 class="card-title fw-bolder fs-3 mb-0">Data Izin Karyawan</h3>
                </div>
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table id="kt_datatable_example_1"
                            class="table table-row-dashed table-row-gray-300 align-middle   g-4">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Tanggal</th>
                                    <th>Izin</th>
                                    <th>Keterangan</th>
                                    <th>Lampiran</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($izin as $a => $i)
                                <tr>
                                    <td>{{ $a + 1 }}</td>
                                    <td class="text-capitalize">{{ $i->karyawan->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($i->tanggal_izin)->format('d M Y') }}</td>
                                    <td>{{ $i->jenis_izin }}</td>
                                    <td>{{ $i->keterangan ?? '-'}}</td>
                                    <td>
                                        @if($i->lampiran)
                                        <a href="{{ asset('storage/' . $i->lampiran) }}" target="_blank"
                                            class="btn btn-sm btn-light-primary">
                                            Lihat
                                        </a>
                                        @else
                                        <span class="badge bg-secondary text-black">Tidak Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($i->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif($i->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                        @else
                                        <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-end d-flex justify-content-end gap-2">
                                        @if($i->status == 'pending')
                                        {{-- Setujui --}}
                                        <form action="{{ route('admin.izin.approve', $i->id_izin) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-icon btn-success btn-sm"
                                                title="Setujui">
                                                <span class="svg-icon svg-icon-2">
                                                    <!-- check-circle -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <circle cx="12" cy="12" r="10" fill="currentColor"
                                                            opacity=".25" />
                                                        <path d="M9.5 12.5l2 2 4-4" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </form>

                                        {{-- Tolak --}}
                                        <form action="{{ route('admin.izin.reject', $i->id_izin) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-icon btn-danger btn-sm" title="Tolak">
                                                <span class="svg-icon svg-icon-2">
                                                    <!-- x-circle -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <circle cx="12" cy="12" r="10" fill="currentColor"
                                                            opacity=".25" />
                                                        <path d="M9 9l6 6M15 9l-6 6" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn btn-icon btn-light btn-sm" disabled title="Status terkunci">
                                            <span class="svg-icon svg-icon-2">
                                                <!-- lock -->
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                                    <rect x="4" y="10" width="16" height="10" rx="2" fill="currentColor"
                                                        opacity=".25" />
                                                    <path d="M8 10V8a4 4 0 118 0v2" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" />
                                                </svg>
                                            </span>
                                        </button>
                                        @endif
                                    </td>
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