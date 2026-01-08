<x-layouts.app>
    <x-slot name="toolbar">
        <x-layouts.toolbar title="Brand" />
    </x-slot>
                        <div class="card card-flush">
								<!--begin::Card header-->
								<div class="card-header mt-5">
									<div class="card-toolbar my-1">
										<div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-3"></i>
                                            <input type="text" id="kt_filter_search" class="form-control form-control-solid form-select-sm w-350px ps-9" placeholder="Search" />
                                        </div>
										<!--end::Search-->
									</div>
                                    <div class="card-title flex-column">
										<a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal" data-bs-target="#studio_modal">Add Data</a>
									</div>
									<!--begin::Card toolbar-->
								</div>
								<!--end::Card header-->
								<!--begin::Card body-->
								<div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table id="brandTable"
                                            class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                            <thead class="fs-7 text-gray-400 text-uppercase">
                                                <tr>
                                                    <th class="w-50px text-center">No</th>
                                                    <th class="min-w-200px">Brand</th>
                                                    <th class="min-w-250px">Description</th>
                                                    <th class="min-w-150px">Created By</th>
                                                    <th class="min-w-100px text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fs-6"></tbody>
                                        </table>
                                    </div>
                                </div>
					    </div>


        <div class="modal fade" id="studio_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content rounded">

                    <div class="modal-header pb-0 border-0 justify-content-end">
                        <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </button>
                    </div>

                    <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                        <div class="text-center mb-8">
                            <h2 class="fw-bold" id="studio_modal_title">Create Role</h2>
                        </div>

                        <form id="brand_form" method="POST" action="{{ route('tenant.brands.store') }}">
                            @csrf

                            <div class="d-flex flex-column mb-6 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Brand <span class="required"></span></label>
                                <input type="text" class="form-control form-control-solid" placeholder="Brand" name="name" autocomplete="off">
                                <div class="text-danger small mt-1" data-error="name"></div>
                            </div>

                            <div class="d-flex flex-column mb-6 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Description</label>
                                <textarea class="form-control form-control-solid" rows="3" name="description" placeholder="Optional..."></textarea>
                                <div class="text-danger small mt-1" data-error="description"></div>
                            </div>

                            <div class="text-center mt-8">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>

                                <button type="submit" id="brand_submit" class="btn btn-primary">
                                    <span class="indicator-label">Submit</span>
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

@push('scripts')
<script>
$(function () {
  // Guards
  if (!window.bootstrap || !bootstrap.Modal) { console.error('Bootstrap Modal belum ter-load.'); return; }
  if (typeof Swal === 'undefined') { console.error('SweetAlert2 belum ter-load.'); return; }
  if (!$.fn.DataTable) { console.error('DataTables belum ter-load.'); return; }

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  // Toast
  const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false, timer: 1800, timerProgressBar: true
  });
  const toastSuccess = (msg) => Toast.fire({ icon: 'success', title: msg });

  // DataTable (brand)
  const dt = $('#brandTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: "{{ route('tenant.brands.data') }}",
    columns: [
      { data: 'DT_RowIndex', orderable:false, searchable:false, className:'text-center', width:'50px' },
      { data: 'name', name: 'name' },
      { data: 'description', name: 'description', orderable:false, searchable:true },
      { data: 'created_at', name: 'created_at' },
      { data: 'actions', orderable:false, searchable:false, className:'text-end' },
    ],
    dom: 'lritp',
  });

  // External search (optional)
  let searchTimer = null;
  const $search = $('#kt_filter_search');
  if ($search.length) {
    $search.on('input', function () {
      clearTimeout(searchTimer);
      const value = this.value;
      searchTimer = setTimeout(() => dt.search(value).draw(), 300);
    });
  }

  // Modal + Form
  const modalEl = document.getElementById('studio_modal');
  if (!modalEl) { console.error('Modal #studio_modal tidak ditemukan.'); return; }
  const getModal = () => bootstrap.Modal.getOrCreateInstance(modalEl);

  const $form   = $('#brand_form');
  const $submit = $('#brand_submit');
  const $title  = $('#studio_modal_title');

  let mode = 'create';

  function setLoading(on) {
    if (on) $submit.attr('data-kt-indicator','on').prop('disabled', true);
    else $submit.removeAttr('data-kt-indicator').prop('disabled', false);
  }

  function clearErrors() {
    $form.find('[data-error]').text('');
    $form.find('.is-invalid').removeClass('is-invalid');
  }

  function showErrors(errors) {
    Object.keys(errors || {}).forEach((key) => {
      const $input = $form.find(`[name="${key}"]`);
      $input.addClass('is-invalid');
      $form.find(`[data-error="${key}"]`).text(errors[key]?.[0] ?? 'Invalid');
    });
  }

  function setMethodSpoof(method) {
    $form.find('input[name="_method"]').remove();
    if (method && method !== 'POST') {
      $form.append(`<input type="hidden" name="_method" value="${method}">`);
    }
  }

  function resetCreate() {
    mode = 'create';
    clearErrors();
    $form[0].reset();
    setMethodSpoof(null);

    $form.attr('action', "{{ route('tenant.brands.store') }}");
    $title.text('Create Studio');
    $submit.find('.indicator-label').text('Submit');
  }

  function setEdit(id, data) {
    mode = 'edit';
    clearErrors();
    $form[0].reset();

    $form.find('[name="name"]').val(data.name ?? '');
    $form.find('[name="location"]').val(data.location ?? '');
    $form.find('[name="description"]').val(data.description ?? '');

    setMethodSpoof('PUT');
    $form.attr('action', "{{ route('tenant.brands.update', ':id') }}".replace(':id', id));
    $title.text('Edit Studio'); // ✅ fix
    $submit.find('.indicator-label').text('Update');
  }

  function closeModalSafely() {
    const instance = getModal();
    instance.hide();

    // fallback cleanup (kalau backdrop nyangkut)
    setTimeout(() => {
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('padding-right');
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    }, 250);
  }

  // OPEN CREATE ✅ (fix id tombol)
  $('#btnCreateStudio').on('click', function () {
    resetCreate();
    getModal().show();
    setTimeout(() => $form.find('[name="name"]').trigger('focus'), 150);
  });

  // OPEN EDIT
  $('#brandTable').on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    const showUrl = "{{ route('tenant.brands.show', ':id') }}".replace(':id', id);

    setLoading(true);
    $.get(showUrl)
      .done((res) => {
        setEdit(id, res?.data || {});
        getModal().show();
      })
      .fail(() => Swal.fire({ icon:'error', title:'Gagal', text:'Gagal mengambil data studio.' }))
      .always(() => setLoading(false));
  });

  // SUBMIT CREATE / UPDATE
  $form.on('submit', function(e){
    e.preventDefault();
    clearErrors();
    setLoading(true);

    $.ajax({
      url: $form.attr('action'),
      method: 'POST',
      data: $form.serialize(),
      success: function(res){
        closeModalSafely();
        dt.ajax.reload(null, false);
        toastSuccess(res?.message || (mode === 'edit' ? 'Studio berhasil diupdate.' : 'Studio berhasil dibuat.'));
      },
      error: function(xhr){
        const json = xhr.responseJSON || {};
        if (xhr.status === 422) {
          showErrors(json.errors || {});
          Swal.fire({ icon:'warning', title:'Validasi gagal', text: json.message || 'Periksa input kamu.' });
          return;
        }
        Swal.fire({ icon:'error', title:'Gagal', text: json.message || 'Terjadi kesalahan.' });
      },
      complete: () => setLoading(false)
    });
  });

  // DELETE
  $('#brandTable').on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    const deleteUrl = "{{ route('tenant.brands.destroy', ':id') }}".replace(':id', id);

    Swal.fire({
      icon: 'warning',
      title: 'Hapus studio?',
      text: 'Studio akan dihapus permanen.',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal',
      reverseButtons: true
    }).then((result) => {
      if (!result.isConfirmed) return;

      Swal.fire({ title:'Menghapus...', allowOutsideClick:false, didOpen: () => Swal.showLoading() });

      $.ajax({
        url: deleteUrl,
        method: 'POST',
        data: { _method: 'DELETE' },
        success: function(res){
          dt.ajax.reload(null, false);
          Swal.close();
          toastSuccess(res?.message || 'Studio berhasil dihapus.');
        },
        error: function(xhr){
          const json = xhr.responseJSON || {};
          Swal.close();
          Swal.fire({ icon:'error', title:'Gagal', text: json.message || 'Gagal menghapus studio.' });
        }
      });
    });
  });

  // RESET on close
  modalEl.addEventListener('hidden.bs.modal', function(){
    setLoading(false);
    resetCreate();
  });

  // init
  resetCreate();
});
</script>
@endpush

</x-layouts.app>
