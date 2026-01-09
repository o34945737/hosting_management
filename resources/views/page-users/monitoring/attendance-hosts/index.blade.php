<x-layouts.app>
    <x-slot name="toolbar">
        <x-layouts.toolbar title="Attendace Host" />
    </x-slot>
                        <div class="card card-flush">
                                <div class="card-body py-10">
									<div class="row">
										<!--begin::Col-->
										<div class="col">
											<div class="card card-dashed flex-center min-w-175px my-3 p-6">
												<span class="fs-4 fw-semibold text-info pb-1 px-2">Total Attendace</span>
												<span class="fs-lg-2tx fw-bold d-flex justify-content-center">0
												</span>
											</div>
										</div>
										<!--end::Col-->
										<!--begin::Col-->
										<div class="col">
											<div class="card card-dashed flex-center min-w-175px my-3 p-6">
												<span class="fs-4 fw-semibold text-success pb-1 px-2">On Time</span>
												<span class="fs-lg-2tx fw-bold d-flex justify-content-center">0
												</span>
											</div>
										</div>
										<!--end::Col-->
										<!--begin::Col-->
										<div class="col">
											<div class="card card-dashed flex-center min-w-175px my-3 p-6">
												<span class="fs-4 fw-semibold text-danger pb-1 px-2">Late</span>
												<span class="fs-lg-2tx fw-bold d-flex justify-content-center">0
												</span>
											</div>
										</div>
										<!--end::Col-->
										<!--begin::Col-->
										<div class="col">
											<div class="card card-dashed flex-center min-w-175px my-3 p-6">
												<span class="fs-4 fw-semibold text-primary pb-1 px-2">Absent</span>
												<span class="fs-lg-2tx fw-bold d-flex justify-content-center">0
												</span>
											</div>
										</div>
										<!--end::Col-->
									</div>
								</div>
								<!--begin::Card header-->
								<div class="card-header mt-5">
									<div class="card-toolbar my-1">
                                       <div class="me-4 my-1">
                                            <label class="fs-6 fw-semibold mb-2">Host <span class="required"></span></label>
											<select id="kt_filter_orders" name="orders" data-control="select2" data-hide-search="true" class="w-250px form-select form-select-solid form-select-sm">
												<option value="All" selected="selected">All Orders</option>
												<option value="Approved">Approved</option>
												<option value="Declined">Declined</option>
												<option value="In Progress">In Progress</option>
												<option value="In Transit">In Transit</option>
											</select>
										</div>
										<div class="me-4 my-1">
                                            <label class="fs-6 fw-semibold mb-2">Studio <span class="required"></span></label>
											<select id="kt_filter_orders" name="orders" data-control="select2" data-hide-search="true" class="w-250px form-select form-select-solid form-select-sm">
												<option value="All" selected="selected">All Orders</option>
												<option value="Approved">Approved</option>
												<option value="Declined">Declined</option>
												<option value="In Progress">In Progress</option>
												<option value="In Transit">In Transit</option>
											</select>
										</div>
                                        <div class="me-4 my-1">
                                            <label class="fs-6 fw-semibold mb-2">Brand <span class="required"></span></label>
											<select id="kt_filter_orders" name="orders" data-control="select2" data-hide-search="true" class="w-250px form-select form-select-solid form-select-sm">
												<option value="All" selected="selected">All Orders</option>
												<option value="Approved">Approved</option>
												<option value="Declined">Declined</option>
												<option value="In Progress">In Progress</option>
												<option value="In Transit">In Transit</option>
											</select>
										</div>
                                        <div class="me-4 my-1">
                                            <label class="fs-6 fw-semibold mb-2">Status Attendance <span class="required"></span></label>
											<select id="kt_filter_orders" name="orders" data-control="select2" data-hide-search="true" class="w-250px form-select form-select-solid form-select-sm">
												<option value="All" selected="selected">All Orders</option>
												<option value="Approved">Approved</option>
												<option value="Declined">Declined</option>
												<option value="In Progress">In Progress</option>
												<option value="In Transit">In Transit</option>
											</select>
										</div>
                                        <div class="me-4 my-1">
                                            <label class="fs-6 fw-semibold mb-2">From Date <span class="required"></span></label>
                                            <input id="filter_date_from"
                                                name="date_from"
                                                type="date"
                                                class="w-250px form-control form-control-solid form-control-sm" />
										</div>
                                        <div class="me-4 my-1">
                                            <label class="fs-6 fw-semibold mb-2">End Date <span class="required"></span></label>
											<input id="filter_date_to"
                                                name="date_to"
                                                type="date"
                                                class="w-250px form-control form-control-solid form-control-sm" />
										</div>
                                        <div class="my-1 d-flex gap-2">
                                            <div class="d-flex flex-column">
                                                <!-- spacer label -->
                                                <label class="fs-6 fw-semibold mb-2 invisible">Actions</label>

                                                <div class="d-flex gap-2">
                                                    <button type="button" id="btnFilter"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="ki-outline ki-filter fs-6 me-1"></i>
                                                        Filter
                                                    </button>

                                                    <button type="button" id="btnResetFilter"
                                                        class="btn btn-sm btn-light">
                                                        <i class="ki-outline ki-cross fs-6 me-1"></i>
                                                        Reset
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
									</div>
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
