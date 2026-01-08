<x-layouts.app>
    <x-slot name="toolbar">
        <x-layouts.toolbar title="Users" />
    </x-slot>
                        <div class="card card-flush">
								<!--begin::Card header-->
								<div class="card-header mt-5">
									<div class="card-toolbar my-1">
										<div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-3"></i>
                                            <input type="text" id="kt_filter_search" class="form-control form-control-solid form-select-sm w-350px ps-9" placeholder="Search Users" />
                                        </div>
										<!--end::Search-->
									</div>
                                    <div class="card-title flex-column">
										<a href="#" class="btn btn-sm btn-primary me-3" data-bs-toggle="modal" data-bs-target="#users_roles">Add Data</a>
									</div>
									<!--begin::Card toolbar-->
								</div>
								<!--end::Card header-->
								<!--begin::Card body-->
								<div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table id="usersTable"
                                            class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold">
                                            <thead class="fs-7 text-gray-400 text-uppercase">
                                                <tr>
                                                    <th class="w-50px text-center">No</th>
                                                    <th class="min-w-200px">Name</th>
                                                    <th class="min-w-250px">Email</th>
                                                    <th class="min-w-250px">Role</th>
                                                    <th class="min-w-150px">Created At</th>
                                                    <th class="min-w-100px text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fs-6"></tbody>
                                        </table>
                                    </div>
                                </div>
					    </div>


        <div class="modal fade" id="users_roles" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content rounded">

                <div class="modal-header pb-0 border-0 justify-content-end">
                    <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>

                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <div class="text-center mb-8">
                    <h2 class="fw-bold" id="users_roles_title">Create User</h2>
                    </div>

                    <form id="users_roles_form" method="POST" action="{{ route('tenant.users.store') }}">
                        @csrf

                        <div class="d-flex flex-column mb-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Name <span class="required"></span></label>
                            <input type="text" class="form-control form-control-solid" placeholder="Name" name="name" autocomplete="off">
                            <div class="text-danger small mt-1" data-error="name"></div>
                        </div>

                        <div class="d-flex flex-column mb-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2">Email <span class="required"></span></label>
                            <input type="email" class="form-control form-control-solid" placeholder="Email" name="email" autocomplete="off">
                            <div class="text-danger small mt-1" data-error="email"></div>
                        </div>

                        <div class="d-flex flex-column mb-6 fv-row">
                            <label class="fs-6 fw-semibold mb-2" id="password_label">
                                Password <span class="required" id="password_required_mark"></span>
                            </label>

                            <input type="password" class="form-control form-control-solid"
                                placeholder="Password" name="password" autocomplete="new-password">

                            <div class="form-text" id="password_help" style="display:none;">
                                Kosongkan jika tidak ingin mengubah password.
                            </div>

                            <div class="text-danger small mt-1" data-error="password"></div>
                        </div>

                        <div class="d-flex flex-column mb-6 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Role</label>
                            <select class="form-select form-select-solid" name="role_id" id="role_id_select">
                                <option value="">Select role...</option>
                            </select>
                            <div class="text-danger small mt-1" data-error="role_id"></div>
                        </div>

                        <div class="text-center mt-8">
                            <button type="button" id="users_roles_cancel" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="users_roles_submit" class="btn btn-primary">
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
    // ===== Guard libs =====
    if (!window.bootstrap || !bootstrap.Modal) {
        console.error('Bootstrap Modal belum ter-load.');
        return;
    }
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 (Swal) belum ter-load.');
        return;
    }
    if (!$.fn.DataTable) {
        console.error('DataTables belum ter-load.');
        return;
    }

    // ===== CSRF =====
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ===== Toast =====
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1800,
        timerProgressBar: true
    });
    const toastSuccess = (msg) => Toast.fire({ icon: 'success', title: msg });
    const toastError   = (msg) => Toast.fire({ icon: 'error', title: msg });

    // ===== DataTable =====
    const dt = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('tenant.users.data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center', width: '50px' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role.name', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        dom: 'lritp',
    });

    // External search (optional)
    let searchTimer = null;
    const $search = $('#kt_filter_search');
    if ($search.length) {
        $search.on('input', function () {
            const value = this.value;
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                dt.search(value).draw();
            }, 300);
        });
    }

    // ===== Modal + Form =====
    const modalEl    = document.getElementById('users_roles');
    if (!modalEl) {
        console.error('Modal #users_roles tidak ditemukan.');
        return;
    }

    const modal      = new bootstrap.Modal(modalEl);
    const $modal     = $('#users_roles');
    const $form      = $('#users_roles_form');
    const $submitBtn = $('#users_roles_submit');
    const $titleEl   = $('#users_roles_title');
    const $select    = $modal.find('#role_id_select');

    let mode = 'create'; // create | edit

    function setLoading(isLoading) {
        if (isLoading) $submitBtn.attr('data-kt-indicator','on').prop('disabled', true);
        else $submitBtn.removeAttr('data-kt-indicator').prop('disabled', false);
    }

    function clearErrors() {
        $form.find('[data-error]').text('');
        $form.find('.is-invalid').removeClass('is-invalid');
    }

    function showErrors(errors) {
        Object.keys(errors || {}).forEach(function (key) {
            const $input = $form.find(`[name="${key}"]`);
            $input.addClass('is-invalid');
            $form.find(`[data-error="${key}"]`).text(errors[key]?.[0] ?? 'Invalid');
        });
    }

    function setMethodSpoof(method) {
        $form.find('input[name="_method"]').remove();
        if (method && method !== 'POST') {
            $form.append('<input type="hidden" name="_method" value="'+method+'">');
        }
    }

    function destroySelect2IfAny() {
        if ($select.data('select2')) $select.select2('destroy');
    }

    function initSelect2IfAvailable() {
        if (!$.fn.select2) return;
        destroySelect2IfAny();
        $select.select2({
            dropdownParent: $modal,
            width: '100%',
            minimumResultsForSearch: Infinity
        });
    }

    // ===== Roles loader (stabil) =====
    function loadRolesOptions(selectedId = null) {
        if ($select.length === 0) {
            console.error('Select #role_id_select tidak ditemukan di dalam modal.');
            return $.Deferred().reject().promise();
        }

        $select.prop('disabled', true);

        return $.get("{{ route('tenant.users.roles-options') }}")
            .done(function (res) {
                const items = res?.data || [];

                // IMPORTANT: destroy select2 sebelum replace option
                destroySelect2IfAny();

                let html = '<option value="">Select role...</option>';
                items.forEach(function (r) {
                    const sel = (String(r.id) === String(selectedId)) ? ' selected' : '';
                    html += `<option value="${r.id}"${sel}>${r.name}</option>`;
                });

                $select.html(html).prop('disabled', false);

                // init select2 setelah modal tampil (modal shown), jadi di sini aman
                initSelect2IfAvailable();

                // set value terpilih
                if (selectedId !== null && selectedId !== undefined) {
                    $select.val(String(selectedId)).trigger('change');
                } else {
                    $select.val('').trigger('change');
                }
            })
            .fail(function (xhr) {
                console.error('roles-options failed:', xhr.status, xhr.responseText);
                toastError('Gagal load roles.');
                $select.prop('disabled', false);
            });
    }

    // ===== Form mode =====
    function resetCreateForm() {
        mode = 'create';
        clearErrors();
        $form[0].reset();

        setMethodSpoof(null);
        $form.attr('action', "{{ route('tenant.users.store') }}");

        if ($titleEl.length) $titleEl.text('Create User');
        $submitBtn.find('.indicator-label').text('Submit');

        const $pass = $form.find('[name="password"]');
        $pass.prop('required', true).val('').attr('placeholder', 'Password');

        // simpan selected role = null
        $modal.data('selectedRoleId', null);
    }

    function setEditForm(userId, data) {
        mode = 'edit';
        clearErrors();

        // JANGAN reset() lagi setelah ini (biar role id tidak hilang)
        $form.find('[name="name"]').val(data.name ?? '');
        $form.find('[name="email"]').val(data.email ?? '');

        const $pass = $form.find('[name="password"]');
        $pass.prop('required', false).val('').attr('placeholder', 'Kosongkan jika tidak diubah');

        setMethodSpoof('PUT');
        const updateUrl = "{{ route('tenant.users.update', ':id') }}".replace(':id', userId);
        $form.attr('action', updateUrl);

        if ($titleEl.length) $titleEl.text('Edit User');
        $submitBtn.find('.indicator-label').text('Update');

        // simpan selected role untuk dipakai saat modal shown
        $modal.data('selectedRoleId', data.role_id ?? null);
    }

    // ===== OPEN CREATE =====
    $('#btnCreateUser').on('click', function () {
        resetCreateForm();
        modal.show();
    });

    // ===== OPEN EDIT =====
    $('#usersTable').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        const showUrl = "{{ route('tenant.users.show', ':id') }}".replace(':id', id);

        resetCreateForm(); // bersihin dulu (safe)
        setLoading(true);

        $.get(showUrl)
            .done(function (res) {
                const data = res?.data || {};
                setEditForm(id, data);
                modal.show();
            })
            .fail(function () {
                Swal.fire({ icon:'error', title:'Gagal', text:'Gagal mengambil data user.' });
            })
            .always(function () {
                setLoading(false);
            });
    });

    // ===== Load roles only when modal is visible (BEST PRACTICE) =====
    modalEl.addEventListener('shown.bs.modal', function () {
        const selectedId = $modal.data('selectedRoleId') ?? null;

        loadRolesOptions(selectedId).always(function () {
            // focus nyaman
            setTimeout(() => $form.find('[name="name"]').trigger('focus'), 150);
        });
    });

    // ===== SUBMIT CREATE/UPDATE =====
    $form.on('submit', function (e) {
        e.preventDefault();
        clearErrors();
        setLoading(true);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST', // create=POST, edit=POST + _method=PUT
            data: $form.serialize(),
            success: function (res) {
                modal.hide();
                dt.ajax.reload(null, false);
                toastSuccess(res?.message || (mode === 'edit' ? 'User berhasil diupdate.' : 'User berhasil dibuat.'));
            },
            error: function (xhr) {
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
            complete: function () {
                setLoading(false);
            }
        });
    });

    // ===== DELETE =====
    $('#usersTable').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = "{{ route('tenant.users.destroy', ':id') }}".replace(':id', id);

        Swal.fire({
            icon: 'warning',
            title: 'Hapus user?',
            text: 'User akan dihapus permanen.',
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
                    toastSuccess(res?.message || 'User berhasil dihapus.');
                },
                error: function (xhr) {
                    const json = xhr.responseJSON || {};
                    Swal.close();
                    Swal.fire({ icon:'error', title:'Gagal', text: json.message || 'Gagal menghapus user.' });
                }
            });
        });
    });

    // ===== RESET WHEN MODAL CLOSE =====
    modalEl.addEventListener('hidden.bs.modal', function () {
        setLoading(false);
        clearErrors();

        // reset form tapi jangan ganggu table
        $form[0].reset();
        setMethodSpoof(null);

        // clear selected role id
        $modal.removeData('selectedRoleId');

        // reset select + destroy select2 biar bersih
        destroySelect2IfAny();
        $select.html('<option value="">Select role...</option>').val('');
    });

    // init
    resetCreateForm();
});
</script>
@endpush



</x-layouts.app>
