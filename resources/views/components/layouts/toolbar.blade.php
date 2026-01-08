<div id="kt_toolbar_container" class="container-fluid d-flex align-items-center">
    <div class="flex-grow-1 flex-shrink-0 me-5">
        <div data-kt-swapper="true"
             data-kt-swapper-mode="prepend"
             data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
             class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">

            <h1 class="d-flex align-items-center text-dark fw-bold my-1 fs-3">
                {{ $title ?? 'Dashboard' }}
                <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            </h1>
        </div>
    </div>

    <div class="d-flex align-items-center flex-wrap">
        <div class="flex-shrink-0 me-2">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active-color-primary btn-active-light active fw-semibold fs-7 px-4 me-1" href="#">Day</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active-color-primary btn-active-light fw-semibold fs-7 px-4 me-1" href="#">Week</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-color-muted btn-active-color-primary btn-active-light fw-semibold fs-7 px-4" href="#">Year</a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center">
            <a href="#" class="btn btn-sm btn-bg-light btn-color-gray-500 btn-active-color-primary me-2">
                <span class="fw-semibold me-1">Range:</span>
                <span class="fw-bold">July 19</span>
            </a>

            <button type="button"
                    class="btn btn-sm btn-icon btn-color-primary btn-active-light btn-active-color-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#kt_modal_create_campaign">
                <i class="ki-outline ki-plus-square fs-2"></i>
            </button>
        </div>
    </div>
</div>
