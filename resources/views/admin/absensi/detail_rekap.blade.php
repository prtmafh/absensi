@extends('layouts.admin.index')

@section('title', 'Detail Rekap Kehadiran Tahunan')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Toolbar/Header --}}
            <div class="d-flex flex-wrap flex-stack mb-6">
                <div class="d-flex flex-column">
                    <h1 class="d-flex align-items-center text-gray-900 fw-bold my-1 fs-3">
                        Detail Rekap Kehadiran Tahun {{ $tahun }}
                        {{-- <span class="text-gray-900 fw-bold ms-3 fs-6"></span> --}}
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('rekap.tahunan', ['tahun' => $tahun]) }}"
                                class="text-muted text-hover-primary">
                                Rekap Tahunan
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-gray-700 text-capitalize">{{ $karyawan->nama }}</li>
                    </ul>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('rekap.tahunan', ['tahun' => $tahun]) }}"
                        class="btn btn-sm btn-light btn-active-light-primary">
                        <i class="bi bi-arrow-left fs-5"></i>
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card card-flush g-5 g-xl-8 mb-5 mb-xl-8">
                {{-- Summary Cards --}}
                <div class="row p-5">
                    {{-- Total Hari --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-flush h-xl-100">
                            <div class="card-body bg-light-primary px-6 py-8 rounded-2">
                                <div class="text-primary fw-bold fs-6 mb-2">Total Hari</div>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="fs-2 fw-bolder text-primary me-2">{{ $detail['total_hari'] }}</span>
                                    <span class="text-gray-500 fs-4 fw-semibold">/ {{ $detail['total_hari_tahun']
                                        }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hadir --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-flush h-xl-100">
                            <div class="card-body bg-light-success px-6 py-8 rounded-2">
                                <div class="text-success fw-bold fs-6 mb-2">Total Hadir</div>
                                <div class="fs-2 fw-bolder text-success mb-3">{{ $detail['hari_hadir'] }}</div>
                                <div class="d-flex gap-2">
                                    <span class="badge badge-light fs-8">
                                        Hadir: {{ $detail['total_hadir'] }}
                                    </span>
                                    <span class="badge badge-light fs-8">
                                        Terlambat: {{ $detail['total_terlambat'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Izin --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-flush h-xl-100">
                            <div class="card-body bg-light-warning px-6 py-8 rounded-2">
                                <div class="text-warning fw-bold fs-6 mb-2">Izin Disetujui</div>
                                <div class="fs-2 fw-bolder text-warning mb-3">{{ $detail['total_izin'] }}</div>
                                @if(!empty($detail['izin_by_jenis']))
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($detail['izin_by_jenis'] as $jenis => $total)
                                    <span class="badge badge-light fs-8">
                                        {{ ucfirst($jenis) }}: {{ $total }}
                                    </span>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-gray-500 fs-8">Tidak ada izin</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tidak Hadir --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-flush h-xl-100">
                            <div class="card-body bg-light-danger px-6 py-8 rounded-2">
                                <div class="text-danger fw-bold fs-6 mb-2">Tidak Hadir</div>
                                <div class="fs-2 fw-bolder text-danger mb-3">{{ $detail['total_tidak_hadir_status'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Alpha / Tidak hadir cards --}}
            <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
                <div class="col-xl-6">
                    <div class="card card-flush h-xl-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label bg-light">
                                    <!--begin::Svg Icon | path: assets/media/icons/duotune/general/gen040.svg-->
                                    <span class="svg-icon svg-icon-muted svg-icon-2hx"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                                                fill="black" />
                                            <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                                transform="rotate(-45 7 15.3137)" fill="black" />
                                            <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                                transform="rotate(45 8.41422 7)" fill="black" />
                                        </svg></span>
                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-gray-700 fw-semibold fs-7 mb-1">Alpha (Tanpa Keterangan)</div>
                                <div class="fs-2hx fw-bold text-gray-900">{{ $detail['total_alpha_tanpa_keterangan'] }}
                                </div>
                                <div class="text-gray-500 fs-8 mt-1">
                                    {{-- Hari berjalan - (hadir+terlambat) - izin --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card card-flush h-xl-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label bg-light">
                                    <!--begin::Svg Icon | path: assets/media/icons/duotune/graphs/gra001.svg-->
                                    <span class="svg-icon svg-icon-muted svg-icon-2hx"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M14 3V21H10V3C10 2.4 10.4 2 11 2H13C13.6 2 14 2.4 14 3ZM7 14H5C4.4 14 4 14.4 4 15V21H8V15C8 14.4 7.6 14 7 14Z"
                                                fill="black" />
                                            <path
                                                d="M21 20H20V8C20 7.4 19.6 7 19 7H17C16.4 7 16 7.4 16 8V20H3C2.4 20 2 20.4 2 21C2 21.6 2.4 22 3 22H21C21.6 22 22 21.6 22 21C22 20.4 21.6 20 21 20Z"
                                                fill="black" />
                                        </svg></span>
                                    <!--end::Svg Icon-->
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-gray-700 fw-semibold fs-7 mb-1">Persentase Kehadiran</div>
                                <div class="fs-2hx fw-bold text-gray-900 mb-2">{{ $detail['persentase'] }}%</div>
                                <div class="progress h-6px w-100 bg-light-primary">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                        role="progressbar" style="width: {{ $detail['persentase'] }}%;"
                                        aria-valuenow="{{ $detail['persentase'] }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Izin Table --}}
            <div class="card card-flush mb-5 mb-xl-8">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-0">Daftar Izin (Disetujui)</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-7">Total {{ count($detail['izin_list'] ?? []) }}
                            izin</span>
                    </h3>
                </div>

                <div class="card-body pt-0">
                    @if(!empty($detail['izin_list']))
                    <div class="table-responsive">
                        <table id="kt_datatable_example_1"
                            class="table table-row-dashed table-row-gray-300 align-middle   g-4">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-120px">Tanggal</th>
                                    <th class="min-w-100px">Jenis</th>
                                    <th class="min-w-250px">Keterangan</th>
                                    <th class="min-w-100px">Status</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach($detail['izin_list'] as $izin)
                                <tr>
                                    <td>
                                        <span class="text-gray-800 fw-bold">{{ $izin['tanggal_izin'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-dark fw-bold">{{
                                            ucfirst($izin['jenis_izin'])
                                            }}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-700">{{ $izin['keterangan'] ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-success fw-bold">{{ ucfirst($izin['status'])
                                            }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="d-flex flex-column flex-center py-10">
                        <span class="symbol symbol-100px mb-5">
                            <span class="symbol-label bg-light">
                                <i class="bi bi-clipboard-check text-gray-400 fs-1"></i>
                            </span>
                        </span>
                        <div class="text-gray-600 fw-semibold fs-6">Tidak ada izin disetujui pada periode ini</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection