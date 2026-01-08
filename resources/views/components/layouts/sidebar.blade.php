<div id="kt_aside" class="aside overflow-visible pb-5 pt-5 pt-lg-0" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'80px', '300px': '100px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
					<!--begin::Brand-->
					<div class="aside-logo py-8" id="kt_aside_logo">
						<!--begin::Logo-->
						<a href="../../demo6/dist/index.html" class="d-flex align-items-center">
							<img alt="Logo" src="assets/media/logos/demo6.svg" class="h-45px logo" />
						</a>
						<!--end::Logo-->
					</div>
					<!--end::Brand-->
					<!--begin::Aside menu-->
					<div class="aside-menu flex-column-fluid" id="kt_aside_menu">
                        <div class="hover-scroll-y my-2 my-lg-5 scroll-ms"
                            id="kt_aside_menu_wrapper"
                            data-kt-scroll="true"
                            data-kt-scroll-height="auto"
                            data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
                            data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu"
                            data-kt-scroll-offset="5px">

                            @php
                                $isCentral = auth('central')->check();
                                $isTenant  = function_exists('tenancy') ? tenancy()->initialized : false;

                                // helpers active class
                                $active = fn(...$names) => request()->routeIs(...$names) ? 'here show' : '';
                                $activeLink = fn(...$names) => request()->routeIs(...$names) ? 'active' : '';
                            @endphp

                            <div class="menu menu-column menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-semibold"
                                id="kt_aside_menu"
                                data-kt-menu="true">

                                {{-- ========================
                                    CENTRAL MENU
                                ======================== --}}
                                @if($isCentral)
                                    {{-- Dashboard (central) --}}
                                    <div class="menu-item py-2 {{ $active('central.dashboard') }}">
                                        <a href="{{ route('central.dashboard') }}" class="menu-link menu-center px-4">
                                            <span class="menu-icon me-3"><i class="ki-outline ki-home-2 fs-2"></i></span>
                                            <span class="menu-title fw-semibold">Dashboard</span>
                                        </a>
                                    </div>

                                    {{-- Tenant (central only) --}}
                                    <div class="menu-item py-2 {{ $active('central.multi-tenants.*') }}">
                                        <a href="{{ route('central.multi-tenants.index') }}" class="menu-link menu-center px-4">
                                            <span class="menu-icon me-3"><i class="ki-outline ki-grid fs-2"></i></span>
                                            <span class="menu-title fw-semibold">Tenant</span>
                                        </a>
                                    </div>
                                @endif


                                {{-- ========================
                                    TENANT MENU
                                ======================== --}}
                                @if($isTenant)
                                    {{-- Dashboard (tenant) --}}
                                    <div class="menu-item py-2 {{ $active('tenant.dashboard') }}">
                                        <a href="{{ route('tenant.dashboard') }}" class="menu-link menu-center px-4">
                                            <span class="menu-icon me-3"><i class="ki-outline ki-home-2 fs-2"></i></span>
                                            <span class="menu-title fw-semibold">Dashboard</span>
                                        </a>
                                    </div>

                                    {{-- Management --}}
                                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start"
                                        class="menu-item py-2 {{ $active('tenant.users.*','tenant.roles.*','tenant.studios.*','tenant.brands.*','tenant.schedules.*') }}">

                                        <span class="menu-link menu-center px-4">
                                            <span class="menu-icon me-3"><i class="ki-outline ki-briefcase fs-2"></i></span>
                                            <span class="menu-title fw-semibold">Management</span>
                                            <span class="menu-arrow"></span>
                                        </span>

                                        <div class="menu-sub menu-sub-dropdown px-2 py-4 w-200px w-lg-225px mh-75 overflow-auto">
                                            <div class="menu-item">
                                                <div class="menu-content">
                                                    <span class="menu-section fs-5 fw-bolder ps-1 py-1">Management</span>
                                                </div>
                                            </div>

                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeLink('tenant.roles.*') }}"
                                                href="{{ route('tenant.roles.index') }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">Roles</span>
                                                </a>
                                            </div>

                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeLink('tenant.users.*') }}"
                                                href="{{ route('tenant.users.index') }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">User</span>
                                                </a>
                                            </div>

                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeLink('tenant.studios.*') }}"
                                                href="{{ route('tenant.studios.index') }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">Studio</span>
                                                </a>
                                            </div>

                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeLink('tenant.brands.*') }}"
                                                href="{{ route('tenant.brands.index') }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">Brand</span>
                                                </a>
                                            </div>

                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeLink('tenant.schedules.*') }}"
                                                href="{{ route('tenant.schedules.index') }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">Schedule</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Monitoring --}}
                                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start"
                                        class="menu-item py-2 {{ $active('tenant.attendances.*','tenant.reports.*') }}">

                                        <span class="menu-link menu-center px-4">
                                            <span class="menu-icon me-3"><i class="ki-outline ki-chart-line fs-2"></i></span>
                                            <span class="menu-title fw-semibold">Monitoring</span>
                                            <span class="menu-arrow"></span>
                                        </span>

                                        <div class="menu-sub menu-sub-dropdown px-2 py-4 w-200px w-lg-225px mh-75 overflow-auto">
                                            <div class="menu-item">
                                                <div class="menu-content">
                                                    <span class="menu-section fs-5 fw-bolder ps-1 py-1">Monitoring</span>
                                                </div>
                                            </div>

                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeLink('tenant.attendances.*') }}"
                                                href="{{ route('tenant.attendances.index') }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">Absensi Host</span>
                                                </a>
                                            </div>

                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeLink('tenant.reports.*') }}"
                                                href="{{ route('tenant.reports.index') }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">Laporan</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Settings --}}
                                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                        data-kt-menu-placement="right-start"
                                        class="menu-item py-2 {{ $active('tenant.settings.*') }}">

                                        <span class="menu-link menu-center px-4">
                                            <span class="menu-icon me-3"><i class="ki-outline ki-setting-2 fs-2"></i></span>
                                            <span class="menu-title fw-semibold">Settings</span>
                                            <span class="menu-arrow"></span>
                                        </span>

                                        <div class="menu-sub menu-sub-dropdown px-2 py-4 w-200px w-lg-225px mh-75 overflow-auto">
                                            <div class="menu-item">
                                                <div class="menu-content">
                                                    <span class="menu-section fs-5 fw-bolder ps-1 py-1">Settings</span>
                                                </div>
                                            </div>

                                            <div class="menu-item">
                                                <a class="menu-link {{ $activeLink('tenant.settings.attendance') }}"
                                                href="{{ route('tenant.settings.attendance') }}">
                                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">Check-in/Out</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

					<!--end::Aside menu-->
					<!--begin::Footer-->
					<div class="aside-footer flex-column-auto" id="kt_aside_footer">
						<!--begin::Menu-->
						<div class="d-flex justify-content-center">
							<button type="button" class="btn btm-sm btn-icon btn-custom btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="top-start" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-dismiss="click" title="Quick actions">
								<i class="ki-outline ki-notification-status fs-1"></i>
							</button>
							<!--begin::Menu 2-->
							<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu separator-->
								<div class="separator mb-3 opacity-75"></div>
								<!--end::Menu separator-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link px-3">New Ticket</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link px-3">New Customer</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
									<!--begin::Menu item-->
									<a href="#" class="menu-link px-3">
										<span class="menu-title">New Group</span>
										<span class="menu-arrow"></span>
									</a>
									<!--end::Menu item-->
									<!--begin::Menu sub-->
									<div class="menu-sub menu-sub-dropdown w-175px py-4">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">Admin Group</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">Staff Group</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<a href="#" class="menu-link px-3">Member Group</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu sub-->
								</div>
								<!--end::Menu item-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<a href="#" class="menu-link px-3">New Contact</a>
								</div>
								<!--end::Menu item-->
								<!--begin::Menu separator-->
								<div class="separator mt-3 opacity-75"></div>
								<!--end::Menu separator-->
								<!--begin::Menu item-->
								<div class="menu-item px-3">
									<div class="menu-content px-3 py-3">
										<a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
									</div>
								</div>
								<!--end::Menu item-->
							</div>
							<!--end::Menu 2-->
						</div>
						<!--end::Menu-->
					</div>
					<!--end::Footer-->
				</div>
