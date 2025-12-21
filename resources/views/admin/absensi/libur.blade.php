@extends('layouts.admin.index')
@section('title', 'Libur Nasional')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link href="{{ asset('assets/css/libur.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
  <div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
      <div class="row g-5">

        {{-- LEFT: Calendar --}}
        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <div class="card-title flex-column">
                <h3 class="fw-bold my-3">Pengaturan Libur Nasional</h3>
                <div class="text-muted fs-7">Klik tanggal pada kalender untuk menandai libur</div>
              </div>
            </div>

            <div class="card-body">
              <div id="kalenderLibur"></div>

              <div class="d-flex flex-wrap gap-6 mt-5">
                <div class="d-flex align-items-center gap-2">
                  <span class="legend-dot" style="background:#50cd89;"></span>
                  <span class="text-muted">Libur ditetapkan</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="legend-dot" style="background:#f1faff;border:1px solid #dbeafe;"></span>
                  {{-- <span class="text-muted">Hover/seleksi</span> --}}
                </div>
              </div>

              <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-4 mt-6">
                <div class="d-flex flex-stack flex-grow-1">
                  <div class="fw-semibold">
                    <div class="fs-7 text-gray-700">
                      Tips: klik tanggal yang sudah hijau untuk <b>Edit</b>. Klik tanggal kosong
                      untuk <b>Tambah</b>.
                      <div class="mt-2 text-muted">Hover tanggal hijau untuk melihat label
                        (tooltip).</div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        {{-- RIGHT: Table --}}
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div class="card-title flex-column">
                <h3 class="fw-bold my-3">Daftar Hari Libur</h3>
                {{-- <div class="text-muted fs-7">Daftar libur yang sudah ditetapkan HRD</div> --}}
              </div>

              {{-- <button class="btn btn-sm btn-light" id="btnRefresh">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
              </button> --}}
            </div>

            <div class="card-body">
              <div id="alertBox"></div>

              {{-- SUMMARY --}}
              <div class="row g-4 mb-6">
                <div class="col-md-6">
                  <div class="stat-card">
                    <div class="stat-title">Total Libur Ditetapkan</div>
                    <div class="stat-value" id="statTotal">0</div>
                    <div class="stat-meta">Semua libur yang sudah diinput</div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="stat-card">
                    <div class="stat-title">Libur Bulan Ini</div>
                    <div class="stat-value" id="statBulanIni">0</div>
                    <div class="stat-meta">Sesuai bulan pada kalender</div>
                  </div>
                </div>
              </div>

              <div class="table-responsive">
                <table id="tableLibur" class="table table-row-dashed table-row-gray-300 align-middle   g-4">
                  <thead>
                    <tr class="fw-bolder text-muted bg-light">
                      <th style="width:140px;">Tanggal</th>
                      <th style="width:120px;">Hari</th>
                      <th style="width:220px;">Libur</th>
                      <th>Keterangan</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    {{-- diisi via JS --}}
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>

      </div>

      <div class="modal fade" id="modalLibur" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <div>
                <h5 class="modal-title fw-bold">Set Libur</h5>
                <div class="text-muted fs-7">Isi detail libur untuk tanggal yang dipilih</div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
              <input type="hidden" id="liburId" />

              <div class="mb-4">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="text" id="tanggal" class="form-control" readonly>
                <div class="text-muted fs-8 mt-1">Format: YYYY-MM-DD</div>
              </div>

              <div class="mb-4">
                <label class="form-label fw-semibold">Nama (opsional)</label>
                <input type="text" id="nama" class="form-control" placeholder="Contoh: Idul Fitri">
              </div>

              <div class="mb-2">
                <label class="form-label fw-semibold">Keterangan (opsional)</label>
                <textarea id="keterangan" class="form-control" rows="3"
                  placeholder="Contoh: Libur nasional / cuti bersama"></textarea>
              </div>

              <div class="text-muted small mt-3">
                Jika tanggal sudah ada, form ini akan masuk mode <b>Edit</b>.
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
              <button class="btn btn-primary" id="btnSimpan">Simpan</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const modalEl = document.getElementById('modalLibur');
  const modal = modalEl ? new bootstrap.Modal(modalEl) : null;

  let liburList = [];        // [{id,tanggal,nama,keterangan}]
  let liburMap  = new Map(); // key: tanggal -> item
  let kalender  = null;

  function showAlert(type, msg) {
    const iconMap = {
      'success': 'success',
      'danger': 'error',
      'warning': 'warning',
      'info': 'info'
    };

    Swal.fire({
      icon: iconMap[type] || 'info',
      title: type === 'success' ? 'Berhasil!' : type === 'danger' ? 'Oops...' : 'Perhatian!',
      text: msg,
      showConfirmButton: type === 'danger',
      timer: type === 'success' ? 2000 : undefined,
      confirmButtonText: 'OK',
      customClass: {
        confirmButton: 'btn btn-primary'
      },
      buttonsStyling: false
    });
  }

  function getHari(dateStr){
    // aman timezone (tanpa geser)
    const d = new Date(dateStr + "T00:00:00");
    const hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
    return hari[d.getDay()];
  }

  function updateStats(){
    const elTotal = document.getElementById('statTotal');
    const elBulan = document.getElementById('statBulanIni');
    if (elTotal) elTotal.textContent = liburList.length;

    if (!kalender) {
      if (elBulan) elBulan.textContent = 0;
      return;
    }

    const month = kalender.currentMonth + 1;
    const year  = kalender.currentYear;

    const bulanIni = liburList.filter(x => {
      const [yy, mm] = x.tanggal.split('-').map(Number);
      return yy === year && mm === month;
    }).length;

    if (elBulan) elBulan.textContent = bulanIni;
  }

  function initTooltips(){
    if (!kalender || !kalender.calendarContainer) return;

    // ambil semua day yang punya tooltip
    const els = kalender.calendarContainer.querySelectorAll('[data-bs-toggle="tooltip"]');

    els.forEach(el => {
      const inst = bootstrap.Tooltip.getInstance(el);
      if (inst) inst.dispose();

      new bootstrap.Tooltip(el, {
        container: 'body',
        boundary: 'window',
        trigger: 'hover'
      });
    });
  }

  function refreshCalendarUI(){
    if (!kalender) return;

    kalender.redraw();

    requestAnimationFrame(() => {
      initTooltips();
    });
  }

  function renderTable(){
    const tbody = document.querySelector('#tableLibur tbody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if(!liburList.length){
      tbody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center text-muted py-8">
            Belum ada libur yang ditetapkan.
          </td>
        </tr>`;
      return;
    }

    liburList
      .slice()
      .sort((a,b)=> b.tanggal.localeCompare(a.tanggal))
      .forEach(item => {
        const tr = document.createElement('tr');

        const safeKet = (item.keterangan ?? '-').toString().replaceAll('"','&quot;');

        tr.innerHTML = `
          <td class="fw-semibold">${item.tanggal}</td>
          <td><span class="badge badge-light">${getHari(item.tanggal)}</span></td>
          <td>
           
            <span class="fw-semibold">${item.nama ?? '-'}</span>
          </td>
          <td class="text-muted">
            <div class="text-clip" title="${safeKet}">
              ${item.keterangan ?? '-'}
            </div>
          </td>
          <td class="text-end">
            <div class="d-flex justify-content-end flex-shrink-0">
            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " title="Edit"
              onclick="openEdit('${item.tanggal}')">
              <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path opacity="0.3"
                        d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                        fill="black" />
                    <path
                        d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                        fill="black" />
                </svg>
            </span>
            </button>
            <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Hapus"
              onclick="hapus(${item.id})">
              <span class="svg-icon svg-icon-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z"
                        fill="black" />
                    <path opacity="0.5"
                        d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z"
                        fill="black" />
                    <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
                </svg>
            </span>
            </button>
            </div>
          </td>
        `;
        tbody.appendChild(tr);
      });
  }

  async function fetchLibur() {
  try {
    const res = await fetch("{{ route('admin.libur-nasional.data') }}", {
      headers: { 'Accept': 'application/json' }
    });

    if (!res.ok) {
      showAlert('danger', 'Gagal memuat data libur.');
      return;
    }

    const json = await res.json();
    liburList = json.data || [];
    liburMap = new Map(liburList.map(x => [x.tanggal, x]));

    renderTable();
    updateStats();
    refreshCalendarUI();
  } catch (error) {
    showAlert('danger', 'Terjadi kesalahan saat memuat data.');
    console.error(error);
  }
}

  // ===== Modal handlers =====
  window.openCreate = function(dateStr){
    document.getElementById('liburId').value = '';
    document.getElementById('tanggal').value = dateStr;
    document.getElementById('nama').value = '';
    document.getElementById('keterangan').value = '';
    modal?.show();
  }

  window.openEdit = function(dateStr){
    const item = liburMap.get(dateStr);
    if(!item) return window.openCreate(dateStr);

    document.getElementById('liburId').value = item.id;
    document.getElementById('tanggal').value = item.tanggal;
    document.getElementById('nama').value = item.nama ?? '';
    document.getElementById('keterangan').value = item.keterangan ?? '';
    modal?.show();
  }

  async function simpan() {
  const id = document.getElementById('liburId').value;

  const payload = {
    tanggal: document.getElementById('tanggal').value,
    nama: document.getElementById('nama').value,
    keterangan: document.getElementById('keterangan').value,
  };

  const isEdit = !!id;
  const url = isEdit
    ? `{{ url('admin/libur-nasional') }}/${id}`
    : `{{ url('admin/libur-nasional') }}`;

  const res = await fetch(url, {
    method: isEdit ? 'PUT' : 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'Accept': 'application/json',
    },
    body: JSON.stringify(payload),
  });

  if (!res.ok) {
    const err = await res.json().catch(() => ({}));
    if (res.status === 422 && err.errors) {
      const k = Object.keys(err.errors)[0];
      return showAlert('danger', err.errors[k][0]);
    }
    return showAlert('danger', err.message ?? 'Gagal menyimpan.');
  }

  modal?.hide();
  showAlert('success', isEdit ? 'Berhasil update libur.' : 'Berhasil menambah libur.');
  await fetchLibur();
}

window.hapus = async function(id) {
  const result = await Swal.fire({
    title: 'Apakah Anda yakin?',
    text: "Data libur ini akan dihapus permanen!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    customClass: {
      confirmButton: 'btn btn-danger',
      cancelButton: 'btn btn-light'
    },
    buttonsStyling: false
  });

  if (!result.isConfirmed) return;

  const res = await fetch(`{{ url('admin/libur-nasional') }}/${id}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrf,
      'Accept': 'application/json',
    }
  });

  if (!res.ok) {
    return showAlert('danger', 'Gagal menghapus libur.');
  }

  showAlert('success', 'Libur berhasil dihapus.');
  await fetchLibur();
}

  // ===== Flatpickr init =====
  kalender = flatpickr("#kalenderLibur", {
    inline: true,
    static: true,
    dateFormat: "Y-m-d",
    locale: { ...flatpickr.l10ns.id, firstDayOfWeek: 1 },

    onDayCreate: function(dObj, dStr, fp, dayElem) {
      const date = fp.formatDate(dayElem.dateObj, "Y-m-d");

      if (liburMap.has(date)) {
        const item = liburMap.get(date);
        const label = item?.nama || item?.keterangan || "Libur";

        dayElem.classList.add("is-libur");

        // tooltip bootstrap (pakai data-bs-title)
        dayElem.setAttribute("data-bs-toggle", "tooltip");
        dayElem.setAttribute("data-bs-placement", "top");
        dayElem.setAttribute("data-bs-title", label);
      }
    },

    onChange: function(selectedDates, dateStr) {
      if(!dateStr) return;
      if (liburMap.has(dateStr)) window.openEdit(dateStr);
      else window.openCreate(dateStr);
    },

    onMonthChange: function(){
      updateStats();
      refreshCalendarUI();
    },

    onYearChange: function(){
      updateStats();
      refreshCalendarUI();
    }
  });

  // events
  document.getElementById('btnSimpan')?.addEventListener('click', simpan);
  document.getElementById('btnRefresh')?.addEventListener('click', fetchLibur);

  // init
  fetchLibur();
</script>
@endpush