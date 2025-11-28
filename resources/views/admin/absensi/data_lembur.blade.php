@extends('layouts.admin.index')

@section('title', 'Data Lembur')

@push('styles')

@endpush


@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <div class="card mb-5 mb-xl-8 shadow-sm">
                <div class="card-header border-0 pt-5 d-flex justify-content-between">
                    <h3 class="card-title fw-bolder fs-3 mb-0">Data Lembur Karyawan</h3>
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
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Total Jam</th>
                                    <th>Total Upah</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lembur as $i => $l)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="text-capitalize">{{ $l->karyawan->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $l->jam_mulai }}</td>
                                    <td>{{ $l->jam_selesai }}</td>
                                    <td>{{ number_format($l->total_jam, 2) }}</td>
                                    <td>Rp {{ number_format($l->total_upah, 0, ',', '.') }}</td>
                                    <td>
                                        @if($l->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @elseif($l->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                        @else
                                        <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($l->status == 'pending')
                                        {{-- Setujui --}}
                                        <form action="{{ route('admin.lembur.approve', $l->id_lembur) }}" method="POST"
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
                                        <form action="{{ route('admin.lembur.reject', $l->id_lembur) }}" method="POST"
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
                                    <label class="fs-6 fw-bold mb-2">Foto</label>

                                    <div id="foto_preview" class="mt-2" style="display:none;">
                                        <img src="" alt="Foto Karyawan" class="rounded w-100px h-100px object-cover">
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

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Beri sedikit delay
    setTimeout(function() {
        const maps = document.querySelectorAll('[id^="map-"]');

        maps.forEach(function(el) {
            const lat = parseFloat(el.dataset.lat);
            const lng = parseFloat(el.dataset.lng);
            const nama = el.dataset.nama;
            const tanggal = el.dataset.tanggal;

            console.log('Map ID:', el.id, 'Lat:', lat, 'Lng:', lng); // Debug log

            if (!isNaN(lat) && !isNaN(lng)) {
                try {
                    const map = L.map(el, {
                        center: [lat, lng],
                        zoom: 15,
                        scrollWheelZoom: false,
                        dragging: true,
                        zoomControl: true
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    L.marker([lat, lng])
                        .addTo(map)
                        .bindPopup(`${nama}<br>${tanggal}`);
                    
                    // Force map to resize
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 100);
                } catch (error) {
                    console.error('Error creating map:', error);
                }
            } else {
                console.warn('Invalid coordinates for element:', el.id);
            }
        });
    }, 500);
});
</script> --}}

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