@extends('layouts.admin.index')

@section('title', 'Rekap Kehadiran Tahunan')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <div class="card mb-5 mb-xl-8 shadow-sm">
                <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title fw-bolder fs-3 mb-0">Rekap Kehadiran Tahunan</h3>
                        <div class="text-muted mt-1">
                            Tahun: <strong>{{ $tahun }}</strong>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('rekap.tahunan') }}" class="d-flex align-items-center gap-2">
                        <label class="me-2 mb-0 fw-semibold">Pilih Tahun:</label>
                        <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}"
                            style="width: 110px;">
                        <button type="submit" class="btn btn-sm btn-primary">
                            Terapkan
                        </button>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="kt_datatable_example_1"
                            class="table table-row-dashed table-row-gray-300 align-middle   g-4">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="">No</th>
                                    <th class="min-w-200px text-start">Nama Karyawan</th>
                                    <th class="min-w-200px">Persentase Kehadiran</th>
                                    <th class="min-w-80px text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekap as $index => $row)
                                <tr>
                                    <td class="">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="fw-semibold text-start text-capitalize">
                                        {{ $row['nama'] }}
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="fw-bold me-2">
                                                    {{ $row['persentase'] }}%
                                                </span>
                                            </div>
                                            <div class="progress h-6px w-100" style="max-width: 300px;">
                                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                    role="progressbar" style="width: {{ $row['persentase'] }}%;"
                                                    aria-valuenow="{{ $row['persentase'] }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('rekap.tahunan.show', $row['karyawan_id']) }}?tahun={{ $tahun }}"
                                            class="btn btn-sm btn-light-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        Belum ada data rekap kehadiran untuk tahun ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection