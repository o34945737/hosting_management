<x-layouts.app>
    <x-slot name="toolbar">
        <x-layouts.toolbar title="Multi Tenant" />
    </x-slot>
                        <div class="card card-flush">
								<!--begin::Card header-->
								<div class="card-header mt-5">
									<div class="card-toolbar my-1">
										<div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-3"></i>
                                            <input type="text" id="kt_filter_search" class="form-control form-control-solid form-select-sm w-350px ps-9" placeholder="Search Tenant" />
                                        </div>
										<!--end::Search-->
									</div>
                                    <div class="card-title flex-column">
										<a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal" data-bs-target="#multy_tenancy">Add Data</a>
									</div>
									<!--begin::Card toolbar-->
								</div>
								<!--end::Card header-->
								<!--begin::Card body-->
								<div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table id="tenantsTable"
                                            class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                            <thead class="fs-7 text-gray-400 text-uppercase">
                                                <tr>
                                                    <th class="w-50px text-center">No</th>
                                                    <th class="min-w-200px">Tenant Name</th>
                                                    <th class="min-w-250px">Domain</th>
                                                    <th class="min-w-150px">Created At</th>
                                                    <th class="min-w-100px text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fs-6"></tbody>
                                        </table>
                                    </div>
                                </div>

								<!--end::Card body-->
					    </div>


<div class="modal fade" id="multy_tenancy" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded">

            <div class="modal-header pb-0 border-0 justify-content-end">
                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </button>
            </div>

            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <!-- optional title (best practice UX) -->
                <div class="text-center mb-8">
                    <h2 class="fw-bold" id="multy_tenancy_title">Create Tenant</h2>
                </div>

                <form id="multy_tenancy_form" method="POST" action="{{ route('central.multi-tenants.store') }}">
                    @csrf

                    <!-- CREATE TENANT FIELDS -->
                    <div id="tenant_create_fields">
                        <div class="d-flex flex-column mb-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">
                                Name <span class="required"></span>
                            </label>
                            <input type="text"
                                   class="form-control form-control-solid"
                                   placeholder="Name"
                                   name="name"
                                   autocomplete="organization">
                            <div class="text-danger small mt-1" data-error="name"></div>
                        </div>

                        <div class="d-flex flex-column mb-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">
                                Subdomain <span class="required"></span>
                            </label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control form-control-solid"
                                       placeholder="sub-domain"
                                       name="subdomain"
                                       autocomplete="off">
                            </div>
                            <div class="text-danger small mt-1" data-error="subdomain"></div>
                        </div>
                    </div>

                    <!-- ADD ADMIN FIELDS -->
                    <div id="tenant_admin_fields">
                        <div class="d-flex flex-column mb-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Admin Email <span class="required"></span></label>
                            <input type="email"
                                   class="form-control form-control-solid"
                                   name="admin_email"
                                   autocomplete="email">
                            <div class="text-danger small mt-1" data-error="admin_email"></div>
                        </div>

                        <div class="d-flex flex-column mb-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Admin Password <span class="required"></span></label>
                            <input type="password"
                                   class="form-control form-control-solid"
                                   name="admin_password"
                                   autocomplete="new-password">
                            <div class="text-danger small mt-1" data-error="admin_password"></div>
                        </div>
                    </div>

                    <div class="text-center mt-8">
                        <button type="button" id="multy_tenancy_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" id="multy_tenancy_submit" class="btn btn-primary">
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
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 (Swal) belum ter-load.');
        return;
    }

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1800,
        timerProgressBar: true
    });

    function toastSuccess(message) { Toast.fire({ icon: 'success', title: message }); }
    function toastError(message) { Toast.fire({ icon: 'error', title: message }); }

    const dt = $('#tenantsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('central.multi-tenants.data') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center', width: '50px' },
            { data: 'name', name: 'name' },
            { data: 'domain', name: 'domain', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        dom: 'lritp',
    });

    let searchTimer = null;
    $('#kt_filter_search').on('input', function () {
        const value = this.value;
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            dt.search(value).draw();
        }, 300);
    });

    const modalEl = document.getElementById('multy_tenancy');
    const modal = new bootstrap.Modal(modalEl);

    const form = $('#multy_tenancy_form');
    const submitBtn = $('#multy_tenancy_submit');

    const createFields = $('#tenant_create_fields');
    const adminFields  = $('#tenant_admin_fields');
    const titleEl      = $('#multy_tenancy_title');

    let formMode = 'create'; // 'create' | 'add-admin'

    function setLoading(isLoading) {
        if (isLoading) submitBtn.attr('data-kt-indicator','on').prop('disabled', true);
        else submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
    }

    function clearErrors() {
        form.find('[data-error]').text('');
        form.find('.is-invalid').removeClass('is-invalid');
    }

    function showErrors(errors) {
        Object.keys(errors || {}).forEach(function (key) {
            const input = form.find(`[name="${key}"]`);
            input.addClass('is-invalid');
            form.find(`[data-error="${key}"]`).text(errors[key]?.[0] ?? 'Invalid');
        });
    }

    function setSubmitLabel(label) {
        submitBtn.find('.indicator-label').text(label);
    }

    function enableCreateInputs(enable) {
        form.find('[name="name"]').prop('disabled', !enable);
        form.find('[name="subdomain"]').prop('disabled', !enable);
    }

    function enableAdminInputs(enable) {
        form.find('[name="admin_email"]').prop('disabled', !enable);
        form.find('[name="admin_password"]').prop('disabled', !enable);
    }

    function resetCreateForm() {
        formMode = 'create';
        clearErrors();
        form[0].reset();

        // ✅ create mode: show create fields, hide admin fields
        createFields.show();
        adminFields.hide();

        enableCreateInputs(true);
        enableAdminInputs(false); // penting agar tidak ikut serialize

        form.find('input[name="_method"]').remove();
        form.attr('action', "{{ route('central.multi-tenants.store') }}");

        titleEl.text('Create Tenant');
        setSubmitLabel('Submit');
    }

    function setAddAdminMode(tenantId) {
        formMode = 'add-admin';
        clearErrors();
        form[0].reset();

        // ✅ add-admin mode: hide create fields, show admin fields
        createFields.hide();
        adminFields.show();

        enableCreateInputs(false); // tidak ikut serialize
        enableAdminInputs(true);

        const addAdminUrl = "{{ route('central.multi-tenants.add-admin', ':id') }}".replace(':id', tenantId);
        form.attr('action', addAdminUrl);

        form.find('input[name="_method"]').remove();

        titleEl.text('Add Tenant Admin');
        setSubmitLabel('Add Admin');

        // focus email biar enak
        setTimeout(() => form.find('[name="admin_email"]').trigger('focus'), 150);
    }

    // Submit (create / add-admin)
    form.on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        setLoading(true);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (res) {
                modal.hide();
                dt.ajax.reload(null, false);

                toastSuccess(res?.message || (formMode === 'add-admin'
                    ? 'Admin tenant berhasil ditambahkan.'
                    : 'Tenant berhasil dibuat.'
                ));
            },
            error: function (xhr) {
                const json = xhr.responseJSON || {};

                if (xhr.status === 422) {
                    showErrors(json.errors || {});
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi gagal',
                        text: json.message || 'Periksa input kamu.'
                    });
                    return;
                }

                if (xhr.status === 419) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Session expired',
                        text: 'Silakan refresh halaman dan coba lagi.'
                    });
                    return;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: json.message || 'Terjadi kesalahan.'
                });
            },
            complete: function () {
                setLoading(false);
            }
        });
    });

    // Open modal create
    $('#btnCreateTenant').on('click', function () {
        resetCreateForm();
        modal.show();
    });

    // Open modal add admin
    $('#tenantsTable').on('click', '.btn-add-admin', function () {
        const id = $(this).data('id');
        setAddAdminMode(id);
        modal.show();
    });

    // Delete
    $('#tenantsTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = "{{ route('central.multi-tenants.destroy', ':id') }}".replace(':id', id);

        Swal.fire({
            icon: 'warning',
            title: 'Hapus tenant?',
            text: 'Tenant & domain di central akan dihapus. Database tenant biasanya tidak otomatis terhapus.',
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
                    toastSuccess(res?.message || 'Tenant berhasil dihapus.');
                },
                error: function (xhr) {
                    const json = xhr.responseJSON || {};
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: json.message || 'Gagal menghapus tenant.'
                    });
                }
            });
        });
    });

    // Reset saat modal ditutup
    modalEl.addEventListener('hidden.bs.modal', function () {
        resetCreateForm();
        setLoading(false);
    });

    // init
    resetCreateForm();
});
</script>
@endpush

</x-layouts.app>
