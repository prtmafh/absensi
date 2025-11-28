@extends('layouts.admin.index')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <div class="card mb-5 mb-xl-8 shadow-sm">
                <div class="card-header border-0 pt-5 d-flex justify-content-between">
                    {{-- <div>
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                        transform="rotate(45 17.0365 15.1223)" fill="black" />
                                    <path
                                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                        fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-docs-table-filter="search"
                                class="form-control form-control-solid w-250px ps-15" placeholder="Cari Karyawan" />
                        </div>
                        <!--end::Search-->
                    </div> --}}
                    <h3 class="card-title fw-bolder fs-3 mb-0">Data Karyawan</h3>
                    <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover"
                        title="Tambah Karyawan Baru">
                        <a href="#" class="btn btn-primary px-6 py-3 fw-bold" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_new_target">
                            <!--begin::Svg Icon | path: assets/media/icons/duotune/arrows/arr017.svg-->
                            <span class="svg-icon svg-icon-3"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3"
                                        d="M11 13H7C6.4 13 6 12.6 6 12C6 11.4 6.4 11 7 11H11V13ZM17 11H13V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z"
                                        fill="black" />
                                    <path
                                        d="M21 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H21C21.6 2 22 2.4 22 3V21C22 21.6 21.6 22 21 22ZM17 11H13V7C13 6.4 12.6 6 12 6C11.4 6 11 6.4 11 7V11H7C6.4 11 6 11.4 6 12C6 12.6 6.4 13 7 13H11V17C11 17.6 11.4 18 12 18C12.6 18 13 17.6 13 17V13H17C17.6 13 18 12.6 18 12C18 11.4 17.6 11 17 11Z"
                                        fill="black" />
                                </svg></span>
                            <!--end::Svg Icon-->
                            Tambah Karyawan
                        </a>
                    </div>
                </div>

                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table id="kt_datatable_example_1"
                            class="table table-row-dashed table-row-gray-300 align-middle   g-4">
                            <thead class="">
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>No.</th>
                                    {{-- <th></th> --}}
                                    <th>Karyawan</th>
                                    {{-- <th>Jabatan</th> --}}
                                    <th>Alamat</th>
                                    <th>No HP</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Jenis Gaji</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($karyawan as $index => $k)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-2">
                                            <img src="{{ asset('storage/' . $k->foto) }}" alt="{{ $k->nama }}" />

                                            {{-- <img src="{{asset('metronic')}}/assets/media/avatars/150-4.jpg"
                                                alt="" /> --}}
                                        </div>
                                        <div>

                                            <div class="fw-bold text-capitalize">{{ $k->nama }}</div>
                                            <div class="fw-bold text-muted text-capitalize">{{ $k->jabatan->nama_jabatan
                                                ?? '-' }}</div>
                                        </div>
                                    </td>
                                    {{-- <td>
                                    </td> --}}

                                    <td class="text-capitalize">{{ $k->alamat }}</td>
                                    <td>{{ $k->no_hp }}</td>
                                    <td>{{ \Carbon\Carbon::parse($k->tgl_masuk)->format('d M Y') }}</td>
                                    <td>{{ $k->jenisGaji->sistem_gaji ?? '-' }}</td>
                                    <td>
                                        @if($k->user && $k->user->status === 'aktif')
                                        <span class="badge badge-light-success">Aktif</span>
                                        @elseif($k->user && $k->user->status === 'nonaktif')
                                        <span class="badge badge-light-danger">Nonaktif</span>
                                        @else
                                        <span class="badge badge-light-danger">Nonaktif</span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <div class="d-flex justify-content-end flex-shrink-0">
                                            @if($k->user)
                                            <a href="#"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-bs-toggle="modal" data-bs-target="#modalUserAkses" data-mode="edit"
                                                data-id="{{ $k->user->id_user }}"
                                                data-username="{{ $k->user->username }}"
                                                data-role="{{ $k->user->role_user }}"
                                                data-status="{{ $k->user->status }}" data-nama="{{ $k->nama }}"
                                                data-karyawan="{{ $k->id_karyawan }}" title="User Akses">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                            fill="black" />
                                                        <path opacity="0.3"
                                                            d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </a>
                                            @else
                                            <a href="#"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-bs-toggle="modal" data-bs-target="#modalUserAkses"
                                                data-mode="tambah" data-nama="{{ $k->nama }}"
                                                data-karyawan="{{ $k->id_karyawan }}" title="User Akses">
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                            fill="black" />
                                                        <path opacity="0.3"
                                                            d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </a>
                                            @endif
                                            <a href="#"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_edit_karyawan"
                                                data-id="{{ $k->id_karyawan }}" data-nama="{{ $k->nama }}"
                                                data-alamat="{{ $k->alamat }}" data-nohp="{{ $k->no_hp }}"
                                                data-tglmasuk="{{ $k->tgl_masuk }}" data-jabatan="{{ $k->jabatan_id }}"
                                                data-jenisgaji="{{ $k->jenis_gaji_id }}" data-status="{{ $k->status }}"
                                                data-foto="{{ $k->foto ? asset('storage/'.$k->foto) : '' }}"
                                                title="Edit">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3"
                                                            d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                            fill="black" />
                                                        <path
                                                            d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <a href="javascript:void(0)"
                                                class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                                                onclick="confirmDelete({{ $k->id_karyawan }})" title="Hapus">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                                                            fill="black" />
                                                        <path opacity="0.5"
                                                            d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                                                            fill="black" />
                                                        <path opacity="0.5"
                                                            d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z"
                                                            fill="black" />
                                                    </svg>
                                                </span>
                                            </a>

                                            <!-- Form hidden untuk delete -->
                                            <form id="delete-form-{{ $k->id_karyawan }}"
                                                action="{{ route('karyawan.destroy', $k->id_karyawan) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="kt_modal_new_target" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content rounded">
                        <!--begin::Modal header-->
                        <div class="modal-header pb-0 border-0 justify-content-end">
                            <!--begin::Close-->
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                            transform="rotate(-45 6 17.3137)" fill="black" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                            transform="rotate(45 7.41422 6)" fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--begin::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                            <!--begin:Form-->
                            <form id="kt_modal_new_target_form" class="form" action="{{ route('karyawan.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <!--begin::Heading-->
                                <div class="mb-13 text-center">
                                    <h1 class="mb-3">Tambah Data Karyawan</h1>
                                    <div class="text-muted fw-bold fs-5">Lengkapi data di bawah untuk menambahkan
                                        karyawan baru</div>
                                </div>
                                <!--end::Heading-->

                                <!--begin::Input group: Nama-->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Nama Karyawan</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Masukkan nama lengkap" name="nama" />
                                </div>
                                <!--end::Input group-->

                                <!--begin::Input group: Alamat-->
                                <div class="d-flex flex-column mb-8">
                                    <label class="fs-6 fw-bold mb-2">Alamat</label>
                                    <textarea class="form-control form-control-solid" rows="3" name="alamat"
                                        placeholder="Masukkan alamat lengkap"></textarea>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Row-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col: No HP-->
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-6 fw-bold mb-2">No HP</label>
                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="08xxxxxxxxxx" name="no_hp" />
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col: Tanggal Masuk-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2">Tanggal Masuk</label>
                                        <div class="position-relative d-flex align-items-center">
                                            <span class="svg-icon svg-icon-2 position-absolute mx-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3"
                                                        d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                                                        fill="black" />
                                                    <path
                                                        d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z"
                                                        fill="black" />
                                                </svg>
                                            </span>
                                            <input class="form-control form-control-solid ps-12"
                                                placeholder="Pilih tanggal" type="date" name="tgl_masuk" />
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Row-->
                                <div class="row g-9 mb-8">
                                    <!--begin::Col: Jabatan-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2">Jabatan</label>
                                        <select class="form-select form-select-solid" name="jabatan_id">
                                            <option value="">-- Pilih Jabatan --</option>
                                            @foreach($jabatan as $j)
                                            <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan
                                                }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->

                                    <!--begin::Col: Jenis Gaji-->
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2">Jenis Gaji</label>
                                        <select class="form-select form-select-solid" name="jenis_gaji_id">
                                            <option value="">-- Pilih Jenis Gaji --</option>
                                            @foreach($jenis_gaji as $g)
                                            <option value="{{ $g->id_jenis_gaji }}">{{
                                                $g->sistem_gaji }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Input group: Status-->
                                {{-- <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Status</label>
                                    <select class="form-select form-select-solid" name="status">
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                    </select>
                                </div> --}}
                                <!--end::Input group-->

                                <!--begin::Input group: Foto-->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Foto</label>
                                    <input type="file" class="form-control form-control-solid" name="foto"
                                        accept="image/*" />
                                    <div class="form-text">Format: jpg, jpeg, png. Maksimal 2MB.</div>
                                </div>
                                <!--end::Input group-->

                                <!--begin::Actions-->
                                <div class="text-center">
                                    <button type="reset" id="kt_modal_new_target_cancel" class="btn btn-light me-3"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">
                                        <span class="indicator-label">Simpan</span>
                                        <span class="indicator-progress">Please wait...
                                            <span
                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end:Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

            <!-- Letakkan modal ini di luar loop foreach karyawan -->
            <div class="modal fade" id="kt_modal_edit_karyawan" tabindex="-1" aria-hidden="true">
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
                                    <h1 class="mb-3">Edit Data Karyawan</h1>
                                    <div class="text-muted fw-bold fs-5">
                                        Perbarui data karyawan sesuai kebutuhan
                                    </div>
                                </div>
                                <input type="hidden" name="id_karyawan" id="edit_id_karyawan">

                                <!-- Nama -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Nama Karyawan</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" name="nama"
                                        id="edit_nama" required />
                                </div>

                                <!-- Alamat -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Alamat</label>
                                    <textarea class="form-control form-control-solid" name="alamat" id="edit_alamat"
                                        rows="3"></textarea>
                                </div>

                                <!-- No HP & Tanggal Masuk -->
                                <div class="row g-9 mb-8">
                                    <div class="col-md-6 fv-row">
                                        <label class="fs-6 fw-bold mb-2">No HP</label>
                                        <input type="text" class="form-control form-control-solid" name="no_hp"
                                            id="edit_no_hp" />
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2">Tanggal Masuk</label>
                                        <input type="date" class="form-control form-control-solid" name="tgl_masuk"
                                            id="edit_tgl_masuk" required />
                                    </div>
                                </div>

                                <!-- Jabatan & Jenis Gaji -->
                                <div class="row g-9 mb-8">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2">Jabatan</label>
                                        <select class="form-select form-select-solid" name="jabatan_id"
                                            id="edit_jabatan" required>
                                            <option value="">-- Pilih Jabatan --</option>
                                            @foreach($jabatan as $j)
                                            <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-bold mb-2">Jenis Gaji</label>
                                        <select class="form-select form-select-solid" name="jenis_gaji_id"
                                            id="edit_jenis_gaji" required>
                                            <option value="">-- Pilih Jenis Gaji --</option>
                                            @foreach($jenis_gaji as $g)
                                            <option value="{{ $g->id_jenis_gaji }}">{{ $g->sistem_gaji }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Foto -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Foto</label>
                                    <input type="file" class="form-control form-control-solid" name="foto"
                                        accept="image/*" />
                                    <div id="edit_foto_preview" class="mt-2" style="display:none;">
                                        <img src="" alt="Foto Karyawan" class="rounded w-100px h-100px object-cover">
                                    </div>
                                    <div class="form-text">Kosongkan jika tidak ingin mengganti foto.</div>
                                </div>

                                <!-- Tombol -->
                                <div class="text-center">
                                    <button type="button" class="btn btn-light me-3"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="indicator-label">Simpan Perubahan</span>
                                        <span class="indicator-progress">Mohon tunggu...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalUserAkses" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content rounded">
                        <!-- Header -->
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

                        <!-- Body -->
                        <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                            <form id="formUserAkses" method="POST">
                                @csrf
                                <div id="methodField"></div>

                                <!-- Heading -->
                                <div class="mb-13 text-center">
                                    <h1 class="mb-3" id="modalUserTitle">Tambah User Akses</h1>
                                    <div class="text-muted fw-bold fs-5">Lengkapi data di bawah untuk menambahkan atau
                                        mengedit
                                        akses login karyawan</div>
                                </div>

                                <!-- Hidden -->
                                <input type="hidden" name="karyawan_id" id="karyawan_id">
                                <input type="hidden" name="id_user" id="id_user">

                                <!-- Nama Karyawan -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Nama Karyawan</label>
                                    <input type="text" class="form-control form-control-solid" id="nama_karyawan"
                                        readonly>
                                </div>

                                <!-- Username -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="required fs-6 fw-bold mb-2">Username</label>
                                    <input type="text" class="form-control form-control-solid" name="username"
                                        id="username" placeholder="Masukkan username" required>
                                </div>

                                <!-- Password -->
                                <div class="d-flex flex-column mb-8 fv-row" id="password_field">
                                    <label class="fs-6 fw-bold mb-2">Password</label>
                                    <input type="password" class="form-control form-control-solid" name="password"
                                        id="password" placeholder="Masukkan password">
                                    <div class="form-text">Isi hanya jika ingin mengubah password.</div>
                                </div>

                                <!-- Role -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="required fs-6 fw-bold mb-2">Role User</label>
                                    <select class="form-select form-select-solid" name="role_user" id="role_user"
                                        required>
                                        <option value="admin">Admin</option>
                                        <option value="karyawan">Karyawan</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="fs-6 fw-bold mb-2">Status</label>
                                    <select class="form-select form-select-solid" name="status" id="status">
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                    </select>
                                </div>

                                <!-- Actions -->
                                <div class="text-center">
                                    <button type="reset" class="btn btn-light me-3"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" id="btnSimpanUser">
                                        <span class="indicator-label">Simpan</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
@endsection
@push('scripts')


<script>
    document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('kt_modal_edit_karyawan');
    
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const form = document.getElementById('kt_modal_edit_karyawan_form');
        
        // Get ID from button
        const id = button.getAttribute('data-id');
        
        // Set form action with ID parameter
        form.action = "{{ route('karyawan.update', ':id') }}".replace(':id', id);
        
        // Set hidden input ID
        document.getElementById('edit_id_karyawan').value = id;
        
        // Fill form fields
        document.getElementById('edit_nama').value = button.getAttribute('data-nama');
        document.getElementById('edit_alamat').value = button.getAttribute('data-alamat') || '';
        document.getElementById('edit_no_hp').value = button.getAttribute('data-nohp') || '';
        document.getElementById('edit_tgl_masuk').value = button.getAttribute('data-tglmasuk');
        document.getElementById('edit_jabatan').value = button.getAttribute('data-jabatan');
        document.getElementById('edit_jenis_gaji').value = button.getAttribute('data-jenisgaji');
        // document.getElementById('edit_status').value = button.getAttribute('data-status');
        
        // Handle photo preview
        const fotoUrl = button.getAttribute('data-foto');
        const fotoPreview = document.getElementById('edit_foto_preview');
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


<script>
    document.getElementById('modalUserAkses').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const mode = button.getAttribute('data-mode');
    const nama = button.getAttribute('data-nama');
    const karyawanId = button.getAttribute('data-karyawan');
    const form = document.getElementById('formUserAkses');
    const methodField = document.getElementById('methodField');

    document.getElementById('nama_karyawan').value = nama;
    document.getElementById('karyawan_id').value = karyawanId;

    if (mode === 'tambah') {
        document.getElementById('modalUserTitle').textContent = 'Tambah User Akses';
        form.action = "{{ route('users.store') }}";
        methodField.innerHTML = '';
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password').required = true;
        document.getElementById('role_user').value = 'karyawan';
        document.getElementById('status').value = 'nonaktif';
    }

    if (mode === 'edit') {
        const idUser = button.getAttribute('data-id');
        const username = button.getAttribute('data-username');
        const role = button.getAttribute('data-role');
        const status = button.getAttribute('data-status');

        document.getElementById('modalUserTitle').textContent = 'Edit User Akses';
        form.action = `/users/${idUser}`;
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