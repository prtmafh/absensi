@extends('layouts.admin.index')

@section('title', 'Periode Gaji')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">

            <!--begin::Card-->
            <div class="card mb-5 mb-xl-8 shadow-sm">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                    <h3 class="card-title fw-bolder fs-3 mb-0">Daftar Gaji</h3>
                    <a href="#" class="btn btn-primary px-6 py-3 fw-bold" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_new_target">
                        <span class="svg-icon svg-icon-2 me-2">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1"
                                    transform="rotate(-90 11.364 20.364)" fill="black" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        Tambah Jenis Gaji
                    </a>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body py-5 ">
                    <div class="table-responsive">
                        <table id="kt_datatable_example"
                            class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bolder text-muted">
                                    <th class="w-10px">No.</th>
                                    <th>Sistem Gaji</th>
                                    <th>Upah</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jenis_gaji as $index => $j)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold text-capitalize">{{ $j->sistem_gaji }}</td>
                                    <td><span class="badge badge-light-success ">{{ 'Rp ' . number_format($j->upah,
                                            0, ',', '.') }}</span></td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end flex-shrink-0">
                                            <!-- Lihat -->
                                            {{-- <a href="#"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-bs-toggle="tooltip" title="Lihat">
                                                <span class="svg-icon svg-icon-3">
                                                    <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                            fill="black" />
                                                        <path opacity="0.3"
                                                            d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                            fill="black" />
                                                    </svg>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </a> --}}
                                            <!-- Edit -->
                                            <a href="#"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_edit"
                                                data-id="{{ $j->id_jenis_gaji }}" data-nama="{{ $j->sistem_gaji }}"
                                                data-upah="{{ $j->upah }}">
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
                                            <!-- Hapus -->
                                            <a href="javascript:void(0)"
                                                class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm"
                                                onclick="confirmDelete({{ $j->id_jenis_gaji }})">
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
                                            <form id="delete-form-{{ $j->id_jenis_gaji }}"
                                                action="{{ route('jenis_gaji.destroy', $j->id_jenis_gaji) }}"
                                                method="POST" style="display: none;">
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
                <!--end::Card body-->
            </div>
            <!--end::Card-->

            <!--begin::Modal Tambah-->
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
                            <form id="kt_modal_new_target_form" class="form" action="{{ route('jenis_gaji.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <!--begin::Heading-->
                                <div class="mb-13 text-center">
                                    <h1 class="mb-3">Tambah Data Gaji</h1>
                                    {{-- <div class="text-muted fw-bold fs-5">Lengkapi data di bawah untuk menambahkan
                                        karyawan baru</div> --}}
                                </div>
                                <!--end::Heading-->

                                <!--begin::Input group: Nama-->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Sistem Gaji</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Masukkan sistem gaji" name="sistem_gaji" />
                                </div>
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Upah</span>
                                    </label>
                                    <input type="number" class="form-control form-control-solid"
                                        placeholder="Masukkan upah" name="upah" />
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
            <!--end::Modal Tambah-->
            <div class="modal fade" id="kt_modal_edit" tabindex="-1" aria-hidden="true">
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
                            <form id="kt_modal_edit_form" class="form" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-13 text-center">
                                    <h1 class="mb-3">Edit Data Gaji</h1>
                                    <div class="text-muted fw-bold fs-5">
                                        Perbarui data gaji
                                    </div>
                                </div>
                                <input type="hidden" name="id_jenis_gaji" id="edit_id_jenis_gaji">

                                <!-- Nama -->
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Sistem Gaji</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" name="sistem_gaji"
                                        id="edit_sistem_gaji" required />
                                </div>
                                <div class="d-flex flex-column mb-8 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Upah</span>
                                    </label>
                                    <input type="number" class="form-control form-control-solid" name="upah"
                                        id="edit_upah" required />
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
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('kt_modal_edit');
    
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const form = document.getElementById('kt_modal_edit_form');
        
        // Get ID from button
        const id = button.getAttribute('data-id');
        
        // Set form action with ID parameter
        form.action = "{{ route('jenis_gaji.update', ':id') }}".replace(':id', id);
        
        // Set hidden input ID
        document.getElementById('edit_id_jenis_gaji').value = id;
        
        // Fill form fields
        document.getElementById('edit_sistem_gaji').value = button.getAttribute('data-nama');
        document.getElementById('edit_upah').value = button.getAttribute('data-upah');
       
    });
});
</script>


@endpush