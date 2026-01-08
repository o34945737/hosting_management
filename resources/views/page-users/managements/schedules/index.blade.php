<x-layouts.app>
    <x-slot name="toolbar">
        <x-layouts.toolbar title="Schedules" />
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
                                    <div class="d-flex card-title ">
                                        <a href="#" class="btn btn-sm btn-success me-3" data-bs-toggle="modal" data-bs-target="#schedules_import_modal">Import Jadwal</a>
                                        <a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal" data-bs-target="#schedules_modal">Add Data</a>
									</div>
								</div>
								<!--end::Card header-->
								<!--begin::Card body-->
								<div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table id="schedulesTable"
                                            class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                            <thead class="fs-7 text-gray-400 text-uppercase">
                                                <tr>
                                                    <th class="w-50px text-center">No</th>
                                                    <th class="min-w-100px">Studio</th>
                                                    <th class="min-w-100px">Host</th>
                                                    <th class="min-w-100px">Brand</th>
                                                    <th class="min-w-100px">Start</th>
                                                    <th class="min-w-100px">End</th>
                                                    <th class="min-w-100px">Status</th>
                                                    <th class="min-w-100px text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fs-6"></tbody>
                                        </table>
                                    </div>
                                </div>
					    </div>

                        {{-- add/update --}}
                        <div class="modal fade" id="schedules_modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content rounded">
                                    <div class="modal-header pb-0 border-0 justify-content-end">
                                        <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                            <i class="ki-outline ki-cross fs-1"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                        <div class="text-center mb-8">
                                            <h2 class="fw-bold" id="schedules_modal_title">Create Schedules</h2>
                                        </div>

                                        <form id="schedules_form" method="POST" action="{{ route('tenant.schedules.store') }}">
                                            @csrf
                                            <div class="d-flex flex-column mb-6 fv-row">
                                                <label class="fs-6 fw-semibold mb-2">Host</label>
                                                <select class="form-select form-select-solid" name="host_id" id="host_id_select">
                                                    <option value="">Pilih Host...</option>
                                                </select>
                                                <div class="text-danger small mt-1" data-error="host_id"></div>
                                            </div>

                                            {{-- Studio --}}
                                            <div class="d-flex flex-column mb-6 fv-row">
                                                <label class="fs-6 fw-semibold mb-2">Studio</label>
                                                <select class="form-select form-select-solid" name="studio_id" id="studio_id_select">
                                                    <option value="">Pilih Studio...</option>
                                                </select>
                                                <div class="text-danger small mt-1" data-error="studio_id"></div>
                                            </div>

                                            {{-- Brand --}}
                                            <div class="d-flex flex-column mb-6 fv-row">
                                                <label class="fs-6 fw-semibold mb-2">Brand</label>
                                                <select class="form-select form-select-solid" name="brand_id" id="brand_id_select">
                                                    <option value="">Pilih Brand...</option>
                                                </select>
                                                <div class="text-danger small mt-1" data-error="brand_id"></div>
                                            </div>

                                            <div class="row g-4 mb-6">
                                                {{-- Start --}}
                                                <div class="col-md-6 fv-row">
                                                    <label class="fs-6 fw-semibold mb-2">Start</label>
                                                    <input
                                                        type="datetime-local"
                                                        class="form-control form-control-solid"
                                                        name="start_at"

                                                    >
                                                    <div class="text-danger small mt-1" data-error="start_at"></div>
                                                </div>

                                                {{-- End --}}
                                                <div class="col-md-6 fv-row">
                                                    <label class="fs-6 fw-semibold mb-2">End</label>
                                                    <input
                                                        type="datetime-local"
                                                        class="form-control form-control-solid"
                                                        name="end_at"

                                                    >
                                                    <div class="text-danger small mt-1" data-error="end_at"></div>
                                                </div>
                                            </div>

                                            {{-- Status --}}
                                            {{-- Status --}}
                                            <div class="d-flex flex-column mb-6 fv-row">
                                                <label class="required fs-6 fw-semibold mb-2">Status</label>

                                                <select class="form-select form-select-solid" name="status" id="status_select">
                                                    <option value="">Pilih Status...</option>
                                                    <option value="planned">Planned</option>
                                                    <option value="ongoing">Ongoing</option>
                                                    <option value="done">Done</option>
                                                    <option value="canceled">Canceled</option>
                                                </select>

                                                <div class="text-danger small mt-1" data-error="status"></div>
                                            </div>


                                            {{-- Notes --}}
                                            <div class="d-flex flex-column mb-6 fv-row">
                                                <label class="fs-6 fw-semibold mb-2">Notes</label>
                                                <textarea class="form-control form-control-solid" rows="3" name="notes" placeholder="Optional..."></textarea>
                                                <div class="text-danger small mt-1" data-error="notes"></div>
                                            </div>

                                            <div class="text-center mt-8">
                                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>

                                                <button type="submit" id="schedules_submit" class="btn btn-primary">
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

                        {{-- import excel --}}
                        <div class="modal fade" id="schedules_import_modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Import Jadwal</h5>
                                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                                    ✕
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="schedules_import_form" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">Upload Excel</label>
                                        <input type="file" name="file" id="import_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                        <div class="text-danger small mt-1" data-error="file"></div>
                                        <div class="form-text">
                                        Header wajib: <code>host_email, studio, brand, start_at, end_at, status, notes</code>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="btnPreviewImport">
                                        Preview
                                        </button>

                                        <button type="button" class="btn btn-success" id="btnCommitImport" disabled>
                                        Confirm Import
                                        </button>
                                    </div>
                                    </form>

                                    <hr class="my-6">

                                    <div class="row g-5">
                                    <div class="col-lg-8">
                                        <h6 class="mb-3">Preview</h6>
                                        <div class="table-responsive">
                                        <table class="table table-row-dashed align-middle" id="importPreviewTable">
                                            <thead>
                                            <tr class="text-gray-600 fw-semibold">
                                                <th>Row</th>
                                                <th>Host</th>
                                                <th>Studio</th>
                                                <th>Brand</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <h6 class="mb-3">Errors</h6>
                                        <div id="importErrorsBox" class="border rounded p-3" style="min-height:150px;">
                                        <div class="text-muted">Belum ada preview.</div>
                                        </div>

                                        <div class="mt-3">
                                        <div><span class="fw-semibold">Total:</span> <span id="importTotal">0</span></div>
                                        <div><span class="fw-semibold text-success">Valid:</span> <span id="importValid">0</span></div>
                                        <div><span class="fw-semibold text-danger">Invalid:</span> <span id="importInvalid">0</span></div>
                                        </div>

                                        <input type="hidden" id="importToken" value="">
                                    </div>
                                    </div>

                                </div>
                                </div>
                            </div>
                        </div>
@push('scripts')
    <script>
        $(function () {
        // ===== Guard libs =====
        if (!window.bootstrap || !bootstrap.Modal) { console.error('Bootstrap Modal belum ter-load.'); return; }
        if (typeof Swal === 'undefined') { console.error('SweetAlert2 (Swal) belum ter-load.'); return; }
        if (!$.fn.DataTable) { console.error('DataTables belum ter-load.'); return; }

        // ===== CSRF =====
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // ===== Toast =====
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false,
            timer: 1800, timerProgressBar: true
        });
        const toastSuccess = (msg) => Toast.fire({ icon: 'success', title: msg });
        const toastError   = (msg) => Toast.fire({ icon: 'error', title: msg });

        // ===== DataTable =====
        const dt = $('#schedulesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('tenant.schedules.data') }}",
            columns: [
                { data: 'DT_RowIndex', orderable:false, searchable:false, className:'text-center', width:'50px' },
                { data: 'studio', name: 'studio' },
                { data: 'host',   name: 'host' },
                { data: 'brand',  name: 'brand' },
                { data: 'start_at', name: 'start_at' },
                { data: 'end_at',   name: 'end_at' },
                { data: 'status', name: 'status' },
                { data: 'actions', orderable:false, searchable:false, className:'text-end' },
            ],
            dom: 'lritp',
        });

        // External search
        let searchTimer = null;
        const $search = $('#kt_filter_search');
        if ($search.length) {
            $search.on('input', function () {
            const value = this.value;
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => dt.search(value).draw(), 300);
            });
        }

        // ===== Modal + Form =====
        const modalEl = document.getElementById('schedules_modal');
        if (!modalEl) { console.error('Modal #schedules_modal tidak ditemukan.'); return; }

        const modal      = new bootstrap.Modal(modalEl);
        const $modal     = $('#schedules_modal');
        const $form      = $('#schedules_form');
        const $submitBtn = $('#schedules_submit');
        const $titleEl   = $('#schedules_modal_title');

        // ambil element dari dalam modal (aman)
        const $hostSelect   = $modal.find('#host_id_select');
        const $studioSelect = $modal.find('#studio_id_select');
        const $brandSelect  = $modal.find('#brand_id_select');
        const $statusSelect = $modal.find('#status_select');

        // Optional: supaya native validation tidak menghalangi submit (best practice untuk ajax form)
        $form.attr('novalidate', 'novalidate');

        let mode = 'create';

        function setLoading(isLoading) {
            if (isLoading) $submitBtn.attr('data-kt-indicator','on').prop('disabled', true);
            else $submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
        }

        function clearErrors() {
            $form.find('[data-error]').text('');
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.select2-selection').removeClass('is-invalid');
        }

        function showErrors(errors) {
            Object.keys(errors || {}).forEach(function (key) {
            const $input = $form.find(`[name="${key}"]`);
            $input.addClass('is-invalid');
            $form.find(`[data-error="${key}"]`).text(errors[key]?.[0] ?? 'Invalid');

            // kalau select2, tempel invalid ke selection
            if ($input.is('select') && $input.data('select2')) {
                $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
            }
            });
        }

        function markInvalid($input, message) {
            const name = $input.attr('name');
            $input.addClass('is-invalid');
            if (name) $form.find(`[data-error="${name}"]`).text(message || 'Wajib diisi.');

            if ($input.is('select') && $input.data('select2')) {
            $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
            }
        }

        // ✅ validasi client-side supaya error status pasti tampil (tanpa nunggu backend)
        function validateBeforeSubmit() {
            let ok = true;

            // status wajib dipilih
            if (!String($statusSelect.val() || '').trim()) {
            markInvalid($statusSelect, 'Status wajib dipilih.');
            ok = false;
            }

            // optional tapi recommended: validasi field required lain
            if (!String($hostSelect.val() || '').trim())   { markInvalid($hostSelect, 'Host wajib dipilih.'); ok = false; }
            if (!String($studioSelect.val() || '').trim()) { markInvalid($studioSelect, 'Studio wajib dipilih.'); ok = false; }
            if (!String($brandSelect.val() || '').trim())  { markInvalid($brandSelect, 'Brand wajib dipilih.'); ok = false; }

            const $start = $form.find('[name="start_at"]');
            const $end   = $form.find('[name="end_at"]');
            if (!String($start.val() || '').trim()) { markInvalid($start, 'Start wajib diisi.'); ok = false; }
            if (!String($end.val() || '').trim())   { markInvalid($end, 'End wajib diisi.'); ok = false; }

            return ok;
        }

        function setMethodSpoof(method) {
            $form.find('input[name="_method"]').remove();
            if (method && method !== 'POST') {
            $form.append('<input type="hidden" name="_method" value="'+method+'">');
            }
        }

        // ===== Select2 helpers =====
        function destroySelect2IfAny($select) {
            if ($select.data('select2')) $select.select2('destroy');
        }

        function initSelect2IfAvailable($select) {
            if (!$.fn.select2) return;
            destroySelect2IfAny($select);
            $select.select2({
            dropdownParent: $modal,
            width: '100%',
            minimumResultsForSearch: Infinity
            });
        }

        // ===== Options loader (same pattern as roles) =====
        function loadOptions($select, url, placeholder, selectedId) {
            if ($select.length === 0) {
            console.error('Select tidak ditemukan:', $select.selector);
            return $.Deferred().reject().promise();
            }

            $select.prop('disabled', true);

            return $.get(url)
            .done(function(res){
                console.log('options ok:', url, res);

                const items = res?.data || [];
                destroySelect2IfAny($select);

                let html = `<option value="">${placeholder}</option>`;
                items.forEach(function(it){
                const sel = (String(it.id) === String(selectedId)) ? ' selected' : '';
                html += `<option value="${it.id}"${sel}>${it.name}</option>`;
                });

                $select.html(html).prop('disabled', false);

                initSelect2IfAvailable($select);

                if (selectedId !== null && selectedId !== undefined && selectedId !== '') {
                $select.val(String(selectedId)).trigger('change');
                } else {
                $select.val('').trigger('change');
                }
            })
            .fail(function(xhr){
                console.error('options failed:', url, xhr.status, xhr.responseText);
                toastError('Gagal load options.');
                $select.prop('disabled', false);
            });
        }

        const URL_HOSTS   = "{{ route('tenant.schedules.hosts-options') }}";
        const URL_STUDIOS = "{{ route('tenant.schedules.studios-options') }}";
        const URL_BRANDS  = "{{ route('tenant.schedules.brands-options') }}";

        function resetCreateForm() {
            mode = 'create';
            clearErrors();
            $form[0].reset();

            setMethodSpoof(null);
            $form.attr('action', "{{ route('tenant.schedules.store') }}");

            if ($titleEl.length) $titleEl.text('Create Schedule');
            $submitBtn.find('.indicator-label').text('Submit');

            // ✅ JANGAN set default planned — biar bisa kosong dan error status bisa dites
            $statusSelect.val('').trigger('change');

            // selected ids untuk modal shown
            $modal.data('selectedHostId', null);
            $modal.data('selectedStudioId', null);
            $modal.data('selectedBrandId', null);
        }

        function setEditForm(id, data) {
            mode = 'edit';
            clearErrors();

            $form.find('[name="start_at"]').val(data.start_at_form ?? '');
            $form.find('[name="end_at"]').val(data.end_at_form ?? '');
            $form.find('[name="notes"]').val(data.notes ?? '');

            // status edit
            $statusSelect.val(data.status ?? '').trigger('change');

            setMethodSpoof('PUT');
            $form.attr('action', "{{ route('tenant.schedules.update', ':id') }}".replace(':id', id));

            if ($titleEl.length) $titleEl.text('Edit Schedule');
            $submitBtn.find('.indicator-label').text('Update');

            $modal.data('selectedHostId', data.host_id ?? null);
            $modal.data('selectedStudioId', data.studio_id ?? null);
            $modal.data('selectedBrandId', data.brand_id ?? null);
        }

        // ===== OPEN CREATE =====
        $('#btnCreateSchedules').on('click', function () {
            resetCreateForm();
            modal.show();
        });

        // ===== OPEN EDIT =====
        $('#schedulesTable').on('click', '.btn-edit', function () {
            const id = $(this).data('id');
            const showUrl = "{{ route('tenant.schedules.show', ':id') }}".replace(':id', id);

            resetCreateForm();
            setLoading(true);

            $.get(showUrl)
            .done(function(res){
                setEditForm(id, res?.data || {});
                modal.show();
            })
            .fail(function(xhr){
                console.error('show failed:', xhr.status, xhr.responseText);
                Swal.fire({ icon:'error', title:'Gagal', text:'Gagal mengambil data schedule.' });
            })
            .always(function(){ setLoading(false); });
        });

        // ===== Load options WHEN modal shown =====
        modalEl.addEventListener('shown.bs.modal', function () {
            const selectedHostId   = $modal.data('selectedHostId') ?? null;
            const selectedStudioId = $modal.data('selectedStudioId') ?? null;
            const selectedBrandId  = $modal.data('selectedBrandId') ?? null;

            // init select2 untuk status juga (jika select2 ada)
            initSelect2IfAvailable($statusSelect);

            $.when(
            loadOptions($hostSelect, URL_HOSTS, 'Pilih Host...', selectedHostId),
            loadOptions($studioSelect, URL_STUDIOS, 'Pilih Studio...', selectedStudioId),
            loadOptions($brandSelect, URL_BRANDS, 'Pilih Brand...', selectedBrandId)
            ).always(function(){
            setTimeout(() => $hostSelect.trigger('focus'), 150);
            });
        });

        // ===== Live clear error on change (UX) =====
        $statusSelect.on('change', function(){
            $(this).removeClass('is-invalid');
            $form.find('[data-error="status"]').text('');
            if ($(this).data('select2')) {
            $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            }
        });

        // ===== SUBMIT CREATE/UPDATE =====
        $form.on('submit', function(e){
            e.preventDefault();
            clearErrors();

            // ✅ pastikan error status muncul walau native required biasanya nahan submit
            if (!validateBeforeSubmit()) {
            Swal.fire({ icon:'warning', title:'Validasi gagal', text:'Periksa input kamu.' });
            return;
            }

            setLoading(true);

            $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function(res){
                modal.hide();
                dt.ajax.reload(null, false);
                toastSuccess(res?.message || (mode === 'edit' ? 'Schedule berhasil diupdate.' : 'Schedule berhasil dibuat.'));
            },
            error: function(xhr){
                const json = xhr.responseJSON || {};
                if (xhr.status === 422) {
                showErrors(json.errors || {});
                Swal.fire({ icon:'warning', title:'Validasi gagal', text: json.message || 'Periksa input kamu.' });
                return;
                }
                if (xhr.status === 419) {
                Swal.fire({ icon:'info', title:'Session expired', text:'Silakan refresh halaman dan coba lagi.' });
                return;
                }
                Swal.fire({ icon:'error', title:'Gagal', text: json.message || 'Terjadi kesalahan.' });
            },
            complete: function(){ setLoading(false); }
            });
        });

        $('#schedulesTable').on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            const deleteUrl = "{{ route('tenant.schedules.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                icon: 'warning',
                title: 'Hapus data?',
                text: 'Data akan dihapus permanen.',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Menghapus...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    data: { _method: 'DELETE' },
                    success: function (res) {
                        dt.ajax.reload(null, false);
                        Swal.close();
                        toastSuccess(res?.message || 'Schedule berhasil dihapus.');
                    },
                    error: function (xhr) {
                        const json = xhr.responseJSON || {};
                        Swal.close();
                        Swal.fire({ icon:'error', title:'Gagal', text: json.message || 'Gagal menghapus schedule.' });
                    }
                });
            });
        });

        // ===== RESET WHEN MODAL CLOSE =====
        modalEl.addEventListener('hidden.bs.modal', function(){
            setLoading(false);
            clearErrors();

            $form[0].reset();
            setMethodSpoof(null);

            $modal.removeData('selectedHostId');
            $modal.removeData('selectedStudioId');
            $modal.removeData('selectedBrandId');

            destroySelect2IfAny($hostSelect);
            destroySelect2IfAny($studioSelect);
            destroySelect2IfAny($brandSelect);
            destroySelect2IfAny($statusSelect);

            $hostSelect.html('<option value="">Pilih Host...</option>').val('');
            $studioSelect.html('<option value="">Pilih Studio...</option>').val('');
            $brandSelect.html('<option value="">Pilih Brand...</option>').val('');

            // status tetap seperti form (tidak diubah), cukup reset value
            $statusSelect.val('').trigger('change');
        });

        // init
        resetCreateForm();
        });
    </script>
    <script>
        $(function () {
        if (typeof Swal === 'undefined') return;

        const $importModal = $('#schedules_import_modal');
        const $importForm  = $('#schedules_import_form');
        const $file        = $('#import_file');

        const $btnPreview = $('#btnPreviewImport');
        const $btnCommit  = $('#btnCommitImport');

        const $token = $('#importToken');
        const $errorsBox = $('#importErrorsBox');

        const $total = $('#importTotal');
        const $valid = $('#importValid');
        const $invalid = $('#importInvalid');

        const previewUrl = "{{ route('tenant.schedules.import.preview') }}";
        const commitUrl  = "{{ route('tenant.schedules.import.commit') }}";

        function clearImportErrors() {
            $importForm.find('[data-error]').text('');
        }

        function setImportLoading(on) {
            $btnPreview.prop('disabled', on);
            $btnCommit.prop('disabled', on || !$token.val());
        }

        function renderPreviewTable(items) {
            const $tbody = $('#importPreviewTable tbody');
            $tbody.empty();

            if (!items || items.length === 0) {
            $tbody.append(`<tr><td colspan="8" class="text-center text-muted">Tidak ada data.</td></tr>`);
            return;
            }

            items.forEach(it => {
            $tbody.append(`
                <tr>
                <td>${it._row ?? '-'}</td>
                <td>${it.host_name ? $('<div>').text(it.host_name).html() : (it.host_email ? $('<div>').text(it.host_email).html() : '-')}</td>
                <td>${$('<div>').text(it.studio ?? '-').html()}</td>
                <td>${$('<div>').text(it.brand ?? '-').html()}</td>
                <td>${$('<div>').text(it.start_at ?? '-').html()}</td>
                <td>${$('<div>').text(it.end_at ?? '-').html()}</td>
                <td>${$('<div>').text(it.status ?? '-').html()}</td>
                <td>${$('<div>').text(it.notes ?? '').html()}</td>
                </tr>
            `);
            });
        }

        function renderErrors(errors) {
            if (!errors || errors.length === 0) {
            $errorsBox.html(`<div class="text-success fw-semibold">Semua baris valid ✅</div>`);
            return;
            }

            let html = `<div class="text-danger fw-semibold mb-2">Ada ${errors.length} baris invalid:</div>`;
            html += `<ul class="mb-0 ps-5">`;
            errors.slice(0, 100).forEach(e => {
            const messages = [];
            Object.keys(e.errors || {}).forEach(k => {
                (e.errors[k] || []).forEach(msg => messages.push(`${k}: ${msg}`));
            });
            html += `<li><span class="fw-semibold">Row ${e._row}:</span> ${$('<div>').text(messages.join(' | ')).html()}</li>`;
            });
            html += `</ul>`;
            if (errors.length > 100) html += `<div class="text-muted mt-2">Menampilkan 100 error pertama.</div>`;
            $errorsBox.html(html);
        }

        // Preview submit
        $importForm.on('submit', function(e){
            e.preventDefault();
            clearImportErrors();

            if (!$file[0].files || !$file[0].files[0]) {
            $importForm.find('[data-error="file"]').text('File wajib diupload.');
            return;
            }

            const fd = new FormData(this);

            setImportLoading(true);

            $.ajax({
            url: previewUrl,
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(res){
                const d = res?.data || {};
                $token.val(d.token || '');

                $total.text(d.total ?? 0);
                $valid.text(d.valid ?? 0);
                $invalid.text(d.invalid ?? 0);

                renderPreviewTable(d.items || []);
                renderErrors(d.errors || []);

                // enable confirm hanya jika valid semua
                const canCommit = (d.invalid ?? 0) === 0 && (d.total ?? 0) > 0 && !!d.token;
                $btnCommit.prop('disabled', !canCommit);

                Swal.fire({ icon:'success', title:'Preview siap', text: res?.message || 'Preview berhasil dibuat.' });
            },
            error: function(xhr){
                const json = xhr.responseJSON || {};
                if (xhr.status === 422) {
                const err = json.errors || {};
                if (err.file?.[0]) $importForm.find('[data-error="file"]').text(err.file[0]);
                Swal.fire({ icon:'warning', title:'Validasi gagal', text: json.message || 'Periksa file.' });
                return;
                }
                Swal.fire({ icon:'error', title:'Gagal', text: json.message || 'Terjadi kesalahan.' });
            },
            complete: function(){
                setImportLoading(false);
            }
            });
        });

        // Commit import
        $btnCommit.on('click', function(){
            const token = $token.val();
            if (!token) return;

            Swal.fire({
            icon: 'question',
            title: 'Confirm Import?',
            text: 'Data akan disimpan ke database.',
            showCancelButton: true,
            confirmButtonText: 'Ya, import',
            cancelButtonText: 'Batal',
            reverseButtons: true
            }).then((r) => {
            if (!r.isConfirmed) return;

            setImportLoading(true);

            $.ajax({
                url: commitUrl,
                method: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), token },
                success: function(res){
                Swal.fire({ icon:'success', title:'Berhasil', text: res?.message || 'Import berhasil.' });

                // reload datatable utama
                if ($.fn.DataTable && $('#schedulesTable').length) {
                    $('#schedulesTable').DataTable().ajax.reload(null, false);
                }

                // reset modal import
                $token.val('');
                $btnCommit.prop('disabled', true);
                $importForm[0].reset();
                renderPreviewTable([]);
                $errorsBox.html(`<div class="text-muted">Belum ada preview.</div>`);
                $total.text('0'); $valid.text('0'); $invalid.text('0');

                $importModal.modal('hide');
                },
                error: function(xhr){
                const json = xhr.responseJSON || {};
                Swal.fire({ icon:'error', title:'Gagal', text: json.message || 'Gagal import.' });
                },
                complete: function(){
                setImportLoading(false);
                }
            });
            });
        });

        // reset ketika modal ditutup
        $importModal.on('hidden.bs.modal', function(){
            clearImportErrors();
            $token.val('');
            $btnCommit.prop('disabled', true);
            $importForm[0].reset();
            renderPreviewTable([]);
            $errorsBox.html(`<div class="text-muted">Belum ada preview.</div>`);
            $total.text('0'); $valid.text('0'); $invalid.text('0');
        });
        });
    </script>
@endpush
</x-layouts.app>
