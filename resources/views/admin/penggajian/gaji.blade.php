@extends('layouts.admin.index')

@section('title', 'Data Gaji')

@push('styles')

@endpush


@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <div class="card mb-5 mb-xl-8 shadow-sm">
                <div class="card-header border-0 pt-5 d-flex justify-content-between">
                    <h3 class="card-title fw-bolder fs-3 mb-0">Data Gaji Karyawan</h3>
                    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"
                        title="Refresh Data Absensi">
                        <a href="#" class="btn btn-primary px-6 py-3 fw-bold d-flex align-items-center"
                            data-bs-toggle="modal" data-bs-target="#modalGenerateGaji">
                            <span class="svg-icon svg-icon-muted svg-icon-2x">
                                <!-- ikon panah -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="24" viewBox="0 0 23 24"
                                    fill="none">
                                    <path
                                        d="M21 13V13.5C21 16 19 18 16.5 18H5.6V16H16.5C17.9 16 19 14.9 19 13.5V13C19 12.4 19.4 12 20 12C20.6 12 21 12.4 21 13ZM18.4 6H7.5C5 6 3 8 3 10.5V11C3 11.6 3.4 12 4 12C4.6 12 5 11.6 5 11V10.5C5 9.1 6.1 8 7.5 8H18.4V6Z"
                                        fill="black" />
                                    <path opacity="0.3"
                                        d="M21.7 6.29999C22.1 6.69999 22.1 7.30001 21.7 7.70001L18.4 11V3L21.7 6.29999ZM2.3 16.3C1.9 16.7 1.9 17.3 2.3 17.7L5.6 21V13L2.3 16.3Z"
                                        fill="black" />
                                </svg>
                            </span>
                            Generate Gaji
                        </a>
                    </div>
                </div>

                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table id="kt_datatable_example_1"
                            class="table table-row-dashed table-row-gray-300 align-middle   g-4">
                            <thead class="">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>No</th>
                                    <th>Nama Karyawan</th>
                                    <th>Bulan</th>
                                    <th>Tahun</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gaji as $index => $g)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-capitalize">{{ $g->karyawan->nama }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create()->month($g->periode_bulan)->format('F') }}
                                    </td>
                                    <td>
                                        {{ $g->periode_tahun }}
                                    </td>

                                    <td class="text-end">
                                        <div class="d-flex justify-content-end flex-shrink-0">
                                            <a href="#"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_lihat_slip"
                                                data-id="{{ $g->id_gaji }}" title="Lihat Slip Gaji">
                                                <span class="svg-icon svg-icon-2 ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <path opacity="0.3"
                                                            d="M22 12C20 8 16 5 12 5S4 8 2 12c2 4 6 7 10 7s8-3 10-7Z"
                                                            fill="currentColor" />
                                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Generate Gaji -->
            <div class="modal fade" id="modalGenerateGaji" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-500px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Generate Gaji Karyawan</h5>
                            <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                            transform="rotate(-45 6 17.3137)" fill="black" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                            transform="rotate(45 7.41422 6)" fill="black" />
                                    </svg>
                                </span>
                            </button>
                        </div>

                        <form id="formGenerateGaji" action="{{ route('gaji.generate') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-5">
                                    <label class="form-label fw-bold">Periode Bulan</label>
                                    <select name="periode_bulan" class="form-select" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        @for($i = 1; $i <= 12; $i++) <option value="{{ $i }}">{{
                                            \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                                            @endfor
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold">Periode Tahun</label>
                                    <select name="periode_tahun" class="form-select" required>
                                        @for($t = date('Y'); $t >= date('Y') - 5; $t--)
                                        <option value="{{ $t }}">{{ $t }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Generate Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Preview Slip Gaji -->
            <div class="modal fade" id="kt_modal_lihat_slip" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header pb-0 border-0 justify-content-between">
                            <h5 class="modal-title fw-bold">Slip Gaji Karyawan</h5>
                            <button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                            transform="rotate(-45 6 17.3137)" fill="black" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                            transform="rotate(45 7.41422 6)" fill="black" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="slip-content" class="p-3">
                                <div class="text-center mb-4">
                                    <h4 class="fw-bold text-capitalize" id="slip-nama"></h4>
                                    <span class="text-muted" id="slip-periode"></span>
                                </div>

                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Jenis Gaji</td>
                                            <td class="text-end" id="slip-upah-jenis"></td>
                                        </tr>
                                        <tr>
                                            <td>Upah Dasar</td>
                                            <td class="text-end" id="slip-upah"></td>
                                        </tr>
                                        <tr>
                                            <td>Total Hadir</td>
                                            <td class="text-end" id="slip-hadir"></td>
                                        </tr>
                                        <tr>
                                            <td>Terlambat (hari)</td>
                                            <td class="text-end" id="slip-terlambat"></td>
                                        </tr>
                                        <tr>
                                            <td>Menit Terlambat</td>
                                            <td class="text-end" id="slip-menit"></td>
                                        </tr>
                                        <tr>
                                            <td>Denda Terlambat</td>
                                            <td class="text-end text-danger" id="slip-denda"></td>
                                        </tr>
                                        <tr>
                                            <td>Izin</td>
                                            <td class="text-end" id="slip-izin"></td>
                                        </tr>
                                        <tr>
                                            <td>Tidak Hadir</td>
                                            <td class="text-end" id="slip-alpha"></td>
                                        </tr>
                                        <tr>
                                            <td>Lembur</td>
                                            <td class="text-end text-success" id="slip-lembur"></td>
                                        </tr>
                                        <tr>
                                            <td>Potongan</td>
                                            <td class="text-end text-danger" id="slip-potongan"></td>
                                        </tr>
                                        <tr class="fw-bold fs-5">
                                            <td>Total Gaji</td>
                                            <td class="text-end text-success" id="slip-total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            <button id="btnPrintSlip" class="btn btn-success">Cetak Slip</button>
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
    document.addEventListener('DOMContentLoaded', function () {
    const slipModal = document.getElementById('kt_modal_lihat_slip');
    
    slipModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        
        // Reset dulu isinya
        document.querySelectorAll('#slip-content td[id^="slip-"], #slip-nama, #slip-periode').forEach(el => {
            el.innerHTML = '<i class="text-muted">Memuat...</i>';
        });

        // Ambil data dari server via AJAX
        fetch(`/preview/gaji/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('slip-nama').textContent = data.karyawan;
                document.getElementById('slip-periode').textContent = data.periode;
                document.getElementById('slip-upah-jenis').textContent = data.jenis_gaji;
                document.getElementById('slip-upah').textContent = `Rp ${data.upah_dasar}`;
                document.getElementById('slip-hadir').textContent = data.total_hadir;
                document.getElementById('slip-terlambat').textContent = data.total_terlambat;
                document.getElementById('slip-menit').textContent = data.late_minutes + ' menit';
                document.getElementById('slip-denda').textContent = `Rp ${data.late_penalty}`;
                document.getElementById('slip-izin').textContent = data.izin;
                document.getElementById('slip-alpha').textContent = data.tidak_hadir;
                document.getElementById('slip-lembur').textContent = `Rp ${data.lembur}`;
                document.getElementById('slip-potongan').textContent = `Rp ${data.potongan}`;
                document.getElementById('slip-total').textContent = `Rp ${data.total_gaji}`;
            })
            .catch(err => {
                console.error(err);
                document.getElementById('slip-content').innerHTML = `
                    <div class="alert alert-danger text-center">
                        Gagal memuat data slip gaji.
                    </div>`;
            });
    });
});
</script>
<script>
    document.getElementById('btnPrintSlip').addEventListener('click', () => {
window.print();
});
</script>
@endpush