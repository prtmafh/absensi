@extends('layouts.karyawan.index')

@section('title', 'Profile Karyawan')

@push('styles')
<style>
    .edit-photo-wrapper {
        width: 150px;
        height: 150px;
        overflow: hidden;
        border-radius: 12px;
        position: relative;
    }

    .edit-photo-overlay {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        /* Teks berada di bagian bawah foto */
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        font-size: 0.8rem;
        padding: 5px;
        text-align: center;
        opacity: 1;
        /* SELALU TAMPIL */
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }
</style>
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card mb-5 mb-xxl-8">
                <div class="card-body pt-9 pb-9">
                    <!--begin::Details-->
                    <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                        <!--begin: Pic-->
                        <div class="me-7 mb-4">
                            <div class="symbol position-relative edit-photo-wrapper">

                                <form id="formFotoProfil" action="{{ route('karyawan.profile.update-foto') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <input type="file" name="foto" id="inputFotoProfil" accept="image/*" class="d-none"
                                        onchange="document.getElementById('formFotoProfil').submit();">

                                    <label for="inputFotoProfil" class="d-block position-relative"
                                        style="cursor:pointer; width:100%; height:100%;">

                                        {{-- Foto --}}
                                        <img src="{{ $karyawan->foto ? asset('storage/'.$karyawan->foto) : asset('images/default-avatar.png') }}"
                                            alt="{{ $karyawan->nama }}" class="rounded"
                                            style="width:100%; height:100%; object-fit:cover;">

                                        {{-- Teks selalu tampil --}}
                                        <div
                                            class="edit-photo-overlay d-flex align-items-center justify-content-center">
                                            <span class="fw-semibold">Edit Foto</span>
                                        </div>

                                    </label>
                                </form>

                            </div>
                        </div>
                        <!--end::Pic-->
                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="#"
                                            class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1 text-capitalize">{{
                                            $karyawan->nama }}</a>
                                        <a href="#">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen026.svg-->
                                            <span class="svg-icon svg-icon-1 svg-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                        fill="#00A3FF" />
                                                    <path class="permanent"
                                                        d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                        fill="white" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </a>
                                    </div>
                                    <!--end::Name-->
                                    <!--begin::Info-->
                                    <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                        <a href="#"
                                            class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2 text-capitalize">
                                            <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                            <span class="svg-icon svg-icon-4 me-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z"
                                                        fill="black" />
                                                    <path
                                                        d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->{{$karyawan->jabatan->nama_jabatan}}
                                        </a>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->
                                <!--begin::Actions-->
                                <div class="d-flex my-4">
                                    <a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal"
                                        data-bs-target="#modalUbahPassword">Edit Profile</a>
                                    <!--begin::Menu-->

                                    <!--end::Menu-->
                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Title-->
                            <!--begin::Stats-->
                            <div class="d-flex flex-wrap flex-stack">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column flex-grow-1 pe-8">
                                    <!--begin::Stats-->
                                    <div class="d-flex flex-wrap">
                                        <!--begin::Stat-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <!--begin::Number-->
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bolder text-success" data-kt-countup="true"
                                                    data-kt-countup-value="{{ $detail['hari_hadir'] }}"
                                                    data-kt-countup-prefix="">0</div>
                                            </div>
                                            <!--end::Number-->
                                            <!--begin::Label-->
                                            <div class="fw-bold fs-6 text-gray-400">Hadir</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Stat-->
                                        <!--begin::Stat-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <!--begin::Number-->
                                            <div class="d-flex align-items-center">
                                                <div class="fs-2 fw-bolder text-danger" data-kt-countup="true"
                                                    data-kt-countup-value="{{ $detail['hari_tidak_hadir'] }}">0
                                                </div>
                                            </div>
                                            <!--end::Number-->
                                            <!--begin::Label-->
                                            <div class="fw-bold fs-6 text-gray-400">Tidak Hadir</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Stat-->

                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Progress-->
                                <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
                                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                        <span class="fw-bold fs-6 text-gray-400">Persentase Kehadiran </span>
                                        <span class="fw-bolder fs-6">{{ $detail['persentase'] }}%</span>
                                    </div>
                                    <div class="h-5px mx-3 w-100 bg-light mb-3">
                                        <div class="bg-success rounded h-5px" role="progressbar"
                                            style="width: {{ $detail['persentase'] }}%;"
                                            aria-valuenow="{{ $detail['persentase'] }}" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!--end::Progress-->
                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Details-->
                    <div class="modal fade" id="modalUbahPassword" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered mw-650px">
                            <div class="modal-content rounded">
                                <!-- Header -->
                                <div class="modal-header pb-0 border-0 justify-content-end">
                                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                        <span class="svg-icon svg-icon-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                    transform="rotate(45 7.41422 6)" fill="black" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <!-- Body -->
                                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                    <form id="formUserAkses" method="POST"
                                        action="{{ route('karyawan.profile.update', $karyawan->id_karyawan) }}">
                                        @csrf
                                        @method('PUT')

                                        <!-- Heading -->
                                        <div class="mb-13 text-center">
                                            <h1 class="mb-3" id="modalUserTitle">Ubah Username & Password</h1>
                                        </div>

                                        <!-- Hidden -->
                                        <input type="hidden" name="karyawan_id" id="karyawan_id"
                                            value="{{ $karyawan->id_karyawan }}">
                                        <input type="hidden" name="id_user" id="id_user"
                                            value="{{ $karyawan->user->id ?? '' }}">

                                        <!-- Username -->
                                        <div class="d-flex flex-column mb-8 fv-row">
                                            <label class="required fs-6 fw-bold mb-2">Username</label>
                                            <input type="text" class="form-control form-control-solid" name="username"
                                                id="username" placeholder="Masukkan username"
                                                value="{{ old('username', $karyawan->user->username ?? '') }}" required>
                                        </div>

                                        <!-- Password -->
                                        <div class="d-flex flex-column mb-8 fv-row" id="password_field">
                                            <label class="fs-6 fw-bold mb-2">Password</label>
                                            <input type="password" class="form-control form-control-solid"
                                                name="password" id="password"
                                                placeholder="Masukkan password baru (opsional)">
                                            <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="text-center">
                                            <button type="reset" class="btn btn-light me-3"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary" id="btnSimpanUser">
                                                <span class="indicator-label">Simpan</span>
                                                <span class="indicator-progress">
                                                    Please wait...
                                                    <span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
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


<script>
    document.getElementById('modalUserAkses').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const mode = button.getAttribute('data-mode');
    const form = document.getElementById('formUserAkses');
    const methodField = document.getElementById('methodField');


    if (mode === 'tambah') {
        document.getElementById('modalUserTitle').textContent = 'Tambah Admin Baru';
        form.action = "{{ route('admin.store') }}";
        methodField.innerHTML = '@method("POST")';
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password').required = true;
        document.getElementById('role_user').value = 'admin';
        document.getElementById('status').value = 'nonaktif';
    }

    if (mode === 'edit') {
        const idUser = button.getAttribute('data-id');
        const username = button.getAttribute('data-username');
        const role = button.getAttribute('data-role');
        const status = button.getAttribute('data-status');

        document.getElementById('modalUserTitle').textContent = 'Edit Admin';
        form.action = `/daftar_admin/${idUser}`;
        methodField.innerHTML = '@method("PUT")';
        document.getElementById('username').value = username;
        document.getElementById('password').value = '';
        document.getElementById('password').required = false;
        document.getElementById('role_user').value = role;
        document.getElementById('status').value = status;
    }
});
</script>

@endpush