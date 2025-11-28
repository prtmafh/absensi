@extends('layouts.karyawan.index')

@section('title', 'Dashboard Absensi Karyawan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    :root {
        --primary-color: #3b82f6;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --dark-color: #1f2937;
        --light-bg: #f9fafb;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --card-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }



    #map {
        height: 250px;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
    }

    @media (min-width: 768px) {
        #map {
            height: 350px;
        }
    }

    .clock-display {
        font-size: clamp(2rem, 8vw, 3.5rem);
        font-weight: 700;
        color: var(--dark-color);
        letter-spacing: -0.02em;
    }

    .date-display {
        font-size: clamp(0.875rem, 3vw, 1.125rem);
        color: #6b7280;
        font-weight: 500;
    }

    .greeting {
        font-size: clamp(1rem, 4vw, 1.375rem);
        font-weight: 600;
        color: #4b5563;
    }

    .header-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        border-radius: 16px;
        box-shadow: var(--card-shadow-lg);
        border: none;
        overflow: hidden;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow-lg);
        border: none;
        overflow: hidden;
    }

    .status-card {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: clamp(0.875rem, 3vw, 1rem);
    }

    @media (max-width: 576px) {
        .status-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
    }

    .btn-absen {
        padding: 0.875rem 2rem;
        font-weight: 600;
        font-size: clamp(0.875rem, 3vw, 1rem);
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow);
        border: none;
        flex: 1;
        max-width: 200px;
    }

    @media (max-width: 576px) {
        .btn-absen {
            width: 100%;
            max-width: none;
            padding: 1rem;
        }
    }

    .btn-absen:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-lg);
    }

    .btn-absen:active {
        transform: translateY(0);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .form-select {
        border-radius: 12px;
        padding: 0.875rem;
        border: 2px solid #e5e7eb;
        font-size: clamp(0.875rem, 3vw, 1rem);
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .company-logo {
        height: clamp(60px, 15vw, 80px);
        object-fit: contain;
    }

    .time-info {
        background: #fef3c7;
        border-left: 4px solid var(--warning-color);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .time-info p {
        margin: 0.25rem 0;
        font-size: clamp(0.75rem, 2.5vw, 0.875rem);
        color: #92400e;
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: clamp(0.75rem, 2.5vw, 0.875rem);
    }

    .card-header {
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        border-bottom: 2px solid #e5e7eb;
    }

    .separatorr {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        margin: 1.5rem 0;
    }

    /* Loading Animation */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Smooth transitions */
    * {
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Button container responsive */
    .button-container {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    @media (max-width: 576px) {
        .button-container {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl px-3 px-md-4">

            <!-- HEADER -->
            <div class="card header-card shadow-sm mb-4 mb-md-6">
                <div class="card-body text-center py-4 py-md-6">
                    <img src="{{asset('')}}assets/img/logotsi.png" alt="logo" class="company-logo mb-3 mb-md-4">
                    <h1 class="fw-bolder text-dark mb-2" style="font-size: clamp(1.25rem, 5vw, 1.75rem);">
                        PT. Tidarjaya Solidindo
                    </h1>
                    <div class="greeting text-muted" id="greetingText">Selamat Datang</div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="row g-4 g-md-6 justify-content-center">
                <div class="col-12 col-xl-10">
                    <div class="card main-card shadow-sm h-100">
                        <div class="card-header border-0 pt-4 pt-md-6 px-4 px-md-6">
                            <h3 class="card-title fw-bolder text-dark mb-0"
                                style="font-size: clamp(1.125rem, 4vw, 1.5rem);">
                                📍 Absensi Hari Ini
                            </h3>
                        </div>

                        <div class="card-body pt-3 pt-md-4 px-4 px-md-6">
                            <!-- MAP -->
                            <div id="map"></div>

                            <!-- CLOCK -->
                            <div class="text-center mb-4">
                                <div id="clock" class="clock-display mb-1"></div>
                                <div class="date-display" id="today"></div>
                            </div>

                            <!-- STATUS -->
                            <div class="status-card">
                                <div class="status-row mb-2">
                                    <span class="fw-bold" style="font-size: clamp(0.875rem, 3vw, 1.125rem);">
                                        Status Kehadiran:
                                    </span>
                                    <div>
                                        @if (is_null($cek))
                                        <span class="badge badge-light-danger">Belum Absen</span>
                                        @else
                                        @switch($cek->status)
                                        @case('hadir')
                                        <span class="badge badge-light-success">✓ Hadir</span>
                                        @break
                                        @case('izin')
                                        <span class="badge badge-light-warning">⚠ Izin</span>
                                        @break
                                        @case('tidak hadir')
                                        <span class="badge badge-light-danger">✗ Tidak Hadir</span>
                                        @break
                                        @case('terlambat')
                                        <span class="badge badge-light-danger">⚠ Terlambat</span>
                                        @break
                                        @default
                                        <span class="badge badge-light-danger">Belum Absen</span>
                                        @endswitch
                                        @endif
                                    </div>
                                </div>

                                <div class="status-row">
                                    <span class="fw-semibold">🕐 Waktu Datang:</span>
                                    <span class="badge badge-light-dark">{{$cek->jam_masuk ?? '00:00:00'}}</span>
                                </div>

                                <div class="status-row">
                                    <span class="fw-semibold">🕐 Waktu Pulang:</span>
                                    <span class="badge badge-light-dark">{{$cek->jam_pulang ?? '00:00:00'}}</span>
                                </div>
                            </div>

                            <!-- Dropdown -->
                            {{-- <div class="mb-4">
                                <label class="form-label fw-semibold mb-2"
                                    style="font-size: clamp(0.875rem, 3vw, 1rem);">
                                    Pilih Status Kehadiran:
                                </label>
                                <select class="form-select">
                                    <option selected>Hadir</option>
                                    <option>Izin</option>
                                </select>
                            </div> --}}

                            <!-- BUTTON -->
                            <div class="button-container mb-4">
                                <button id="btnMasuk" class="btn btn-success btn-absen">
                                    📸 Absen Masuk
                                </button>
                                <button id="btnPulang" class="btn btn-danger btn-absen">
                                    👋 Absen Pulang
                                </button>
                            </div>

                            <div class="separatorr"></div>

                            <div class="time-info">
                                <p class="mb-1"><strong>⏰ Jam Kerja:</strong></p>
                                <p class="mb-1">
                                    • Absen Masuk:
                                    <strong>{{ $shift_start ? \Carbon\Carbon::parse($shift_start)->format('H:i') : '-'
                                        }} WIB</strong>
                                </p>
                                <p class="mb-0">
                                    • Absen Pulang:
                                    <strong>{{ $shift_end ? \Carbon\Carbon::parse($shift_end)->format('H:i') : '-' }}
                                        WIB</strong>
                                </p>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // --- GREETING ---
    const hours = new Date().getHours();
    let greeting = "Selamat Datang";
    if (hours >= 4 && hours < 11) greeting = "Selamat Pagi";
    else if (hours >= 11 && hours < 15) greeting = "Selamat Siang";
    else if (hours >= 15 && hours < 18) greeting = "Selamat Sore";
    else greeting = "Selamat Malam";
    document.getElementById('greetingText').innerHTML = greeting + ', <strong class="text-capitalize">{{ $namaKaryawan }}</strong>!';

    // --- MAP ---
    var map = L.map('map', { 
        // scrollWheelZoom: false,
        zoomControl: true }).setView([0, 0], 15);
    var marker;
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(function(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;
            var accuracy = position.coords.accuracy;
            map.setView([lat, lon], 17);
            if (marker) {
                marker.setLatLng([lat, lon]).bindPopup(`📍 Lokasi Anda<br>Akurasi: ${Math.round(accuracy)} m`).openPopup();
            } else {
                marker = L.marker([lat, lon]).addTo(map)
                    .bindPopup(`📍 Lokasi Anda<br>Akurasi: ${Math.round(accuracy)} m`).openPopup();
            }
        });
    }

    // --- CLOCK ---
    function updateClock() {
        const now = new Date();
        document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID');
        document.getElementById('today').textContent = now.toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // --- VARIABEL GLOBAL ---
    let stream = null;
    let isProcessing = false;
    let videoReady = false;

    function bukaKameraPopup(judulAbsen, urlAbsen) {
        if (isProcessing) return;
        isProcessing = true;
        videoReady = false;

        Swal.fire({
            title: judulAbsen,
            html: `
                <div class="text-center">
                    <div id="loadingCamera" style="padding: 60px 0;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Membuka kamera...</p>
                    </div>
                    <video id="cameraPopup" autoplay playsinline 
                        style="border-radius: 12px; display: none; width: 100%; max-width: 320px; height: auto;">
                    </video>
                    <canvas id="canvasPopup" width="320" height="240" class="d-none"></canvas>
                    <p class="mt-3 mb-0" id="cameraInstruction" style="display: none;">
                        📸 Pastikan wajah Anda terlihat jelas
                    </p>
                </div>
            `,
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: '📸 Ambil Foto & Kirim',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            allowOutsideClick: false,
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'rounded-3',
                cancelButton: 'rounded-3'
            },
            didOpen: () => {
                const videoElement = document.getElementById('cameraPopup');
                const loadingDiv = document.getElementById('loadingCamera');
                const instruction = document.getElementById('cameraInstruction');
                const confirmBtn = Swal.getConfirmButton();
                confirmBtn.style.display = 'none';
                confirmBtn.disabled = true;

                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                .then(s => {
                    stream = s;
                    videoElement.srcObject = s;
                    videoElement.onplaying = () => {
                        setTimeout(() => {
                            videoReady = true;
                            loadingDiv.style.display = 'none';
                            videoElement.style.display = 'block';
                            instruction.style.display = 'block';
                            confirmBtn.style.display = 'inline-block';
                            confirmBtn.disabled = false;
                        }, 300);
                    };
                })
                .catch(err => {
                    Swal.fire('Gagal', 'Kamera tidak dapat diakses: ' + err.message, 'error');
                    isProcessing = false;
                });
            },
            preConfirm: () => {
                if (!videoReady) {
                    Swal.showValidationMessage('Tunggu hingga kamera siap...');
                    return false;
                }
                
                const video = document.getElementById('cameraPopup');
                const canvas = document.getElementById('canvasPopup');
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const fotoData = canvas.toDataURL('image/jpeg', 0.8);

                return fotoData;
            },
            willClose: () => {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                }
                isProcessing = false;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const fotoData = result.value;
                kirimAbsen(urlAbsen, fotoData);
            }
        });
    }
    
    async function kirimAbsen(url, fotoData = null) {
        if (!navigator.geolocation) {
            Swal.fire('Error', 'Browser Anda tidak mendukung geolocation.', 'error');
            return;
        }

        Swal.fire({
            title: 'Mengirim absensi...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const pos = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    timeout: 10000,
                    enableHighAccuracy: true
                });
            });

            const payload = {
                latitude: pos.coords.latitude,
                longitude: pos.coords.longitude,
                status: document.querySelector('select').value,
                foto: fotoData
            };

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            Swal.close();
            await new Promise(r => setTimeout(r, 100));

            Swal.fire({
                icon: data.status === 'success' ? 'success' : 'error',
                title: data.status === 'success' ? '✓ Berhasil!' : '✗ Gagal!',
                text: data.message,
                confirmButtonColor: data.status === 'success' ? '#10b981' : '#ef4444',
                showConfirmButton: true,
                timer: data.status === 'success' ? 2000 : undefined,
                timerProgressBar: true
            }).then(() => {
                if (data.status === 'success') location.reload();
            });

        } catch (error) {
            Swal.close();
            await new Promise(r => setTimeout(r, 100));
            Swal.fire('Error', 'Terjadi kesalahan: ' + error.message, 'error');
        }
    }
        
    async function kirimAbsenPulang(url) {
        Swal.fire({
            title: 'Mengirim absensi...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });

            const data = await res.json();

            if (Swal.isVisible()) Swal.close();
            await new Promise(r => setTimeout(r, 100));

            Swal.fire({
                icon: data.status === 'success' ? 'success' : 'error',
                title: data.status === 'success' ? '✓ Berhasil!' : '✗ Gagal!',
                text: data.message,
                confirmButtonColor: data.status === 'success' ? '#10b981' : '#ef4444',
                showConfirmButton: true,
                timer: data.status === 'success' ? 2000 : undefined,
                timerProgressBar: true,
                didClose: () => {
                    if (data.status === 'success') location.reload();
                }
            });
        } catch (error) {
            if (Swal.isVisible()) Swal.close();
            Swal.fire('Error', 'Terjadi kesalahan saat mengirim data: ' + error.message, 'error');
        }
    }

    // --- EVENT TOMBOL ---
    document.getElementById('btnMasuk').addEventListener('click', () => {
        bukaKameraPopup('📸 Absen Masuk', '{{ route("absen.masuk") }}');
    });

    document.getElementById('btnPulang').addEventListener('click', () => {
        Swal.fire({
            title: '👋 Absen Pulang',
            text: 'Apakah Anda yakin ingin absen pulang?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Absen Pulang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444'
        }).then(result => {
            if (result.isConfirmed) {
                kirimAbsenPulang('{{ route("absen.pulang") }}');
            }
        });
    });
</script>
@endpush