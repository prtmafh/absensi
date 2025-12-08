@extends('layouts.admin.index')

@section('title', 'Detail Rekap Kehadiran Tahunan')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            {{-- Breadcrumb & Header --}}
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="fw-bolder fs-2 mb-1">
                        Detail Rekap Kehadiran Tahunan
                    </h3>
                    <div class="text-muted">
                        {{ $karyawan->nama }} &mdash;
                        Tahun <strong>{{ $tahun }}</strong>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('rekap.tahunan', ['tahun' => $tahun]) }}" class="btn btn-light-secondary">
                        &larr; Kembali
                    </a>
                </div>
            </div>

            {{-- Ringkasan Angka --}}
            <div class="row g-5 mb-5">
                <div class="col-md-3">
                    <div class="card card-bordered shadow-sm">
                        <div class="card-body">
                            <div class="text-muted fw-semibold fs-7 mb-1">Total Hari Tahun {{ $tahun }}</div>
                            <div class="fs-2 fw-bolder">
                                {{ $detail['total_hari'] }} / {{ $detail['total_hari_tahun'] }}
                            </div>
                            <div class="text-muted fs-8">
                                Termasuk Sabtu, Minggu & hari libur nasional.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-bordered shadow-sm">
                        <div class="card-body">
                            <div class="text-muted fw-semibold fs-7 mb-1">Hari Hadir</div>
                            <div class="fs-2 fw-bolder text-success">
                                {{ $detail['hari_hadir'] }}
                            </div>
                            <div class="text-muted fs-8">
                                Status: <span class="fw-semibold">hadir</span> &amp;
                                <span class="fw-semibold">terlambat</span>.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-bordered shadow-sm">
                        <div class="card-body">
                            <div class="text-muted fw-semibold fs-7 mb-1">Hari Tidak Hadir</div>
                            <div class="fs-2 fw-bolder text-danger">
                                {{ $detail['hari_tidak_hadir'] }}
                            </div>
                            <div class="text-muted fs-8">
                                Izin, tidak hadir, tidak absen, libur, dll.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-bordered shadow-sm">
                        <div class="card-body d-flex flex-column align-items-start">
                            <div class="text-muted fw-semibold fs-7 mb-1">Persentase Kehadiran</div>
                            <div class="d-flex align-items-baseline mb-3">
                                <span class="fs-2 fw-bolder me-2">
                                    {{ $detail['persentase'] }}%
                                </span>
                            </div>
                            <div class="progress h-8px w-100 mb-2">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $detail['persentase'] }}%;"
                                    aria-valuenow="{{ $detail['persentase'] }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <div class="text-muted fs-8">
                                Nilai ini dihitung dari hari hadir dibagi total hari dalam 1 tahun.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penjelasan Tambahan --}}
            <div class="card shadow-sm mb-5">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title fw-bolder fs-4 mb-0">
                        Penjelasan Perhitungan
                    </h3>
                </div>
                <div class="card-body fs-7 text-muted">
                    <p class="mb-2">
                        Rekap kehadiran tahunan ini menggunakan data absensi harian karyawan
                        selama tahun <strong>{{ $tahun }}</strong>.
                    </p>
                    <ul class="mb-2">
                        <li>
                            <strong>Hari Hadir</strong> dihitung dari absensi dengan
                            status <code>hadir</code> dan <code>terlambat</code>.
                        </li>
                        <li>
                            <strong>Hari Tidak Hadir</strong> mencakup hari dengan status
                            <code>izin</code>, <code>tidak hadir</code>, hari tanpa absen,
                            serta hari Sabtu, Minggu, dan hari libur nasional.
                        </li>
                        <li>
                            <strong>Persentase Kehadiran</strong> dihitung dengan rumus:
                            <code>(Hari Hadir / Total Hari) × 100%</code>.
                        </li>
                    </ul>
                    <p class="mb-0">
                        Data ini dapat digunakan sebagai indikator kedisiplinan karyawan
                        dalam 1 tahun berjalan, dan bisa menjadi dasar penilaian kinerja,
                        pemberian reward, maupun peringatan kedisiplinan.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection