@extends('layouts.admin.index')

@section('title', 'Data Absensi')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-container {
        z-index: 1;
        border: 1px solid #ccc;
        border-radius: 8px;
        cursor: grab;
    }

    .leaflet-container:active {
        cursor: grabbing;
    }
</style>
@endpush


@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <div class="card mb-5 mb-xl-8 shadow-sm">
                <div class="card-header border-0 pt-5 d-flex justify-content-between">
                    <h3 class="card-title fw-bolder fs-3 mb-0">Data Absensi Karyawan</h3>
                    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"
                        title="Refresh Data Absensi">
                        <a href="javascript:void(0)" class="btn btn-primary px-6 py-3 fw-bold d-flex align-items-center"
                            onclick="refreshAbsensi()">
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
                            Refresh
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
                                    <th>Tanggal</th>
                                    <th>Nama Karyawan</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                    <th>Map</th>
                                    <th class="">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($absen as $index => $a)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="">
                                        {{\Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}
                                    </td>
                                    <td class="text-capitalize">{{ $a->karyawan->nama }}</td>
                                    <td>
                                        <span class="badge badge-light-dark">{{ $a->jam_masuk ?? 'Belum Absen'}}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-dark">{{ $a->jam_pulang ?? 'Belum Absen'}}</span>
                                    </td>
                                    <td>
                                        @if ($a->status == 'hadir')
                                        <span class="badge badge-light-success">Hadir</span>
                                        @elseif ($a->status == 'izin')
                                        <span class="badge badge-light-warning">Izin</span>
                                        @elseif ($a->status == 'tidak hadir')
                                        <span class="badge badge-light-danger">Tidak Hadir</span>
                                        @elseif ($a->status == 'terlambat')
                                        <span class="badge badge-light-danger">Terlambat</span>
                                        @else
                                        <span class="badge badge-light-secondary">-</span>
                                        @endif

                                    </td>
                                    <td>
                                        @if($a->latitude && $a->longitude)
                                        <div id="map-{{ $a->id_absen }}" data-lat="{{ $a->latitude }}"
                                            data-lng="{{ $a->longitude }}" data-nama="{{ $a->karyawan->nama }}"
                                            data-tanggal="{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}"
                                            style="height: 120px; width: 180px; border-radius: 8px; overflow: hidden; cursor: pointer;"
                                            class="map-mini" data-bs-toggle="modal" data-bs-target="#modalMapBesar"
                                            title="Lihat Map">
                                        </div>
                                        @else
                                        <span class="text-muted">Tidak ada lokasi</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-start flex-shrink-0">
                                            <a href="#"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_lihat_foto"
                                                data-id="{{ $a->id_absen }}"
                                                data-foto="{{ $a->foto ? asset('storage/'.$a->foto) : '' }}"
                                                title="Lihat Foto Absen">
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

            <!-- Letakkan modal ini di luar loop foreach karyawan -->
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
                                    <h1 class="mb-3">Foto Absen</h1>
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
            <!-- Modal Map Besar -->
            <div class="modal fade" id="modalMapBesar" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content rounded shadow-lg">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">Lokasi Absen</h5>
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

                        <div class="modal-body p-0">
                            <div id="mapBesar" style="height: 500px; width: 100%; border-radius: 0 0 10px 10px;"></div>
                        </div>

                        <div class="modal-footer justify-content-between">
                            <div>
                                <span id="mapInfo" class="fw-bold text-muted text-capitalize"></span>
                            </div>
                            <button type="button" id="btnOpenGoogle" class="btn btn-primary" target="_blank">
                                Lihat di Google Maps
                            </button>
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    function refreshAbsensi() {
    Swal.fire({
        title: 'Memproses...',
        // text: 'Menandai karyawan yang tidak hadir.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    fetch('{{ route("run.mark.absent") }}')
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            }).then(() => {
                location.reload(); // refresh halaman setelah selesai
            });
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat memperbarui absensi.'
            });
        });
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Render mini map di tabel
    setTimeout(function() {
        const maps = document.querySelectorAll('[id^="map-"]');
        maps.forEach(function(el) {
            const lat = parseFloat(el.dataset.lat);
            const lng = parseFloat(el.dataset.lng);
            const nama = el.dataset.nama;
            const tanggal = el.dataset.tanggal;

            if (!isNaN(lat) && !isNaN(lng)) {
                const map = L.map(el, {
                    center: [lat, lng],
                    zoom: 15,
                    scrollWheelZoom: false,
                    dragging: false,
                    zoomControl: false
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng])
                    .addTo(map);
                    // .bindPopup(`${nama}<br>${tanggal}`);
            }
        });
    }, 500);


    // --- Modal Map Besar ---
    let mapBesar, markerBesar;

    const modalMap = document.getElementById('modalMapBesar');
    modalMap.addEventListener('show.bs.modal', function(event) {
        const triggerEl = event.relatedTarget; // map-mini yang diklik
        const lat = parseFloat(triggerEl.dataset.lat);
        const lng = parseFloat(triggerEl.dataset.lng);
        const nama = triggerEl.dataset.nama;
        const tanggal = triggerEl.dataset.tanggal;

        // Tampilkan info di footer modal
        document.getElementById('mapInfo').textContent = `${nama} - ${tanggal}`;

        // Set tombol Google Maps
        const googleBtn = document.getElementById('btnOpenGoogle');
        googleBtn.onclick = function() {
            window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
        };

        // Render peta besar
        setTimeout(() => {
            if (!mapBesar) {
                mapBesar = L.map('mapBesar').setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapBesar);

                markerBesar = L.marker([lat, lng]).addTo(mapBesar).bindPopup(`${nama}<br>${tanggal}`);
            } else {
                mapBesar.setView([lat, lng], 16);
                markerBesar.setLatLng([lat, lng]);
            }

            mapBesar.invalidateSize();
        }, 300);
    });
});
</script>


@endpush