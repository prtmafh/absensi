@extends('layouts.admin.index')

@section('title', 'Dashboard Absensi')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <!--begin::Row - Statistik Utama-->
            <div class="row gy-5 g-xl-8">
                <div class="col-xxl-12">
                    <div class="card card-xxl-stretch">
                        <div class="card-header border-0 bg-primary py-5">
                            <h3 class="card-title fw-bolder text-white">Statistik Kehadiran Karyawan</h3>
                        </div>

                        <div class="card-body p-0">
                            <div class="mixed-widget-2-chart card-rounded-bottom bg-primary" data-kt-color="primary"
                                style="height: 200px"></div>

                            <div class="card-p mt-n20 position-relative">
                                <!--begin::Row-->
                                <div class="row g-0">
                                    <!--begin::Col-->
                                    <div
                                        class="col bg-light-primary px-6 py-8 rounded-2 me-7 mb-7 d-flex align-items-center justify-content-between">
                                        <div>
                                            <!--begin::Svg Icon | Icon: User -->
                                            <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                                        fill="black" />
                                                    <path opacity="0.3"
                                                        d="M21 20H3C2.4 20 2 19.6 2 19C2 16.2 4.2 14 7 14H17C19.8 14 22 16.2 22 19C22 19.6 21.6 20 21 20Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <a href="{{route('daftar_karyawan')}}"
                                                class="text-primary fw-bold fs-6">Total Karyawan</a>
                                        </div>
                                        <div class="fs-2 fw-bolder text-primary">{{ $totalKaryawan }}</div>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div
                                        class="col bg-light-success px-6 py-8 rounded-2 me-7 mb-7 d-flex align-items-center justify-content-between">
                                        <div>
                                            <!--begin::Svg Icon | Icon: Check Circle/Attendance -->
                                            <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                                        fill="black" />
                                                    <path
                                                        d="M10.5 15.5L16.5 9.5C16.9 9.1 16.9 8.5 16.5 8.1C16.1 7.7 15.5 7.7 15.1 8.1L10 13.2L8.5 11.7C8.1 11.3 7.5 11.3 7.1 11.7C6.7 12.1 6.7 12.7 7.1 13.1L9.5 15.5C9.9 15.9 10.1 15.9 10.5 15.5Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <a href="{{route('data_absen')}}" class="text-success fw-bold fs-6">Hadir
                                                Hari Ini</a>
                                        </div>
                                        <div class="fs-2 fw-bolder text-success">{{ $hadir }}</div>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div
                                        class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7 d-flex align-items-center justify-content-between">
                                        <div>
                                            <!--begin::Svg Icon | Icon: Document/File (Izin) -->
                                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                        fill="black" />
                                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="black" />
                                                    <path
                                                        d="M17 16H12C11.4 16 11 15.6 11 15V14C11 13.4 11.4 13 12 13H17C17.6 13 18 13.4 18 14V15C18 15.6 17.6 16 17 16ZM12 11C11.4 11 11 10.6 11 10V9C11 8.4 11.4 8 12 8H17C17.6 8 18 8.4 18 9V10C18 10.6 17.6 11 17 11H12ZM12 19C11.4 19 11 18.6 11 18V17C11 16.4 11.4 16 12 16H14C14.6 16 15 16.4 15 17V18C15 18.6 14.6 19 14 19H12Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <a href="{{route('data_absen')}}"
                                                class="text-warning fw-bold fs-6 mt-2">Terlambat</a>
                                        </div>
                                        <div class="fs-2 fw-bolder text-warning">{{ $terlambat }}</div>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col-->
                                    <div
                                        class="col bg-light-danger px-6 py-8 rounded-2 me-7 mb-7 d-flex align-items-center justify-content-between">
                                        <div>
                                            <!--begin::Svg Icon | Icon: Clock/Pending -->
                                            <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                                        fill="black" />
                                                    <path
                                                        d="M12 7C11.4 7 11 7.4 11 8V12.5C11 12.8 11.1 13 11.3 13.2L14.3 16.2C14.7 16.6 15.3 16.6 15.7 16.2C16.1 15.8 16.1 15.2 15.7 14.8L13 12.1V8C13 7.4 12.6 7 12 7Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <a href="{{route('data_absen')}}"
                                                class="text-danger fw-bold fs-6 mt-2">Tidak Hadir</a>
                                        </div>
                                        <div class="fs-2 fw-bolder text-danger">{{ $alpha }}</div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row Statistik-->

            <!--begin::Row - Data Tabel Terbaru-->
            <div class="row gy-5 g-xl-8 mt-0">
                <!-- Daftar Absensi Terbaru -->
                <div class="col-xl-8">
                    <div class="card card-xxl-stretch">
                        <div class="card-header border-0 bg-light-primary">
                            <h3 class="card-title fw-bolder text-primary">Absensi Hari Ini</h3>
                        </div>
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="ps-4 min-w-150px rounded-start">Nama</th>
                                            <th class="min-w-100px">Jam Masuk</th>
                                            <th class="min-w-100px">Jam Pulang</th>
                                            <th class="min-w-80px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($absensiHariIni as $a)
                                        <tr>
                                            <td class="ps-4 text-capitalize">{{ $a->karyawan->nama ?? '-' }}</td>
                                            <td><span class="badge badge-light-dark">
                                                    {{ $a->jam_masuk ?? 'Belum Absen' }}</span></td>
                                            <td><span class="badge badge-light-dark">{{ $a->jam_pulang ?? 'Belum
                                                    Absen' }}</span></td>
                                            <td>
                                                @switch($a->status)
                                                @case('hadir')
                                                <span class="badge badge-light-success">Hadir</span>
                                                @break
                                                @case('izin')
                                                <span class="badge badge-light-warning">Izin</span>
                                                @break
                                                @case('tidak hadir')
                                                <span class="badge badge-light-danger">Tidak Hadir</span>
                                                @break
                                                @case('terlambat')
                                                <span class="badge badge-light-danger">Terlambat</span>
                                                @break
                                                @default
                                                <span class="badge badge-light-danger">Belum Absen</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada data absensi hari
                                                ini</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengumuman / Slip Gaji -->
                <div class="col-xl-4">
                    <div class="card card-xxl-stretch">
                        <div class="card-header border-0 bg-light-success">
                            <h3 class="card-title fw-bolder text-success">Informasi Terbaru</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="fw-bold fs-6">Slip Gaji Bulan Ini</div>
                                <p class="text-muted mb-0">Periode: {{ now()->locale('id')->monthName }} {{ now()->year
                                    }}</p>
                                <a href="{{ route('gaji') }}" class="btn btn-sm btn-success mt-2">Lihat Slip</a>
                                {{-- <a href="" class="btn btn-sm btn-success mt-2 disabled">Lihat Slip</a> --}}
                            </div>
                            <hr>
                            <div>
                                <div class="fw-bold fs-6">Pengumuman HRD</div>
                                <p class="text-muted">
                                    Karyawan lembur wajib konfirmasi ke mandor sebelum pukul 10.00 setiap Sabtu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->

        </div>
    </div>
</div>
@endsection