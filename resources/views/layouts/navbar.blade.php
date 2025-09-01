<nav
    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="flex-grow-1">
            <h3 class="fs-6 text-break mb-0">
                Welcome back, <b class="text-primary">{{ Auth::user()->name }}</b>
            </h3>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Notifikasi Dropdown
            <li class="nav-item dropdown notif-dropdown position-relative">
                <a class="nav-link" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-bell fs-4"></i>
                    <span class="badge bg-danger rounded-pill position-absolute notif-badge">3</span>
                </a>

                <div class="dropdown-menu dropdown-menu-end p-3 shadow">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 fw-bold text-primary">Notifikasi <span class="text-primary">(3)</span></h6>
                        <a href="#" class="text-muted small">Mark all as read</a>
                    </div>

                    <div class="notif-item d-flex align-items-start mb-3 border-bottom pb-2">
                        @if(Auth::user()->image != null)
                        <img src="{{ asset('img/profile/' . Auth::user()->image) }}" class="rounded-circle me-2" alt="Avatar">
                        @else
                        <img src="{{ asset('assets/img/avatars/1.png') }}" class="rounded-circle me-2" alt="Avatar">
                        @endif
                        <p class="mb-0">Pekerjaan harianmu tanggal 13 Februari 2025 mendapatkan feedback.</p>
                    </div>
                </div>
            </li> -->

            <!-- Language Switcher Dropdown -->
            <!-- Language Switcher Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="languageDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-globe fs-4"></i> Language
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2" aria-labelledby="languageDropdown">
                    <!-- Default / Reset -->
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="restoreOriginal()">
                            <i class="bx bx-refresh me-2"></i> Indonesia (Default)
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <!-- Google Translate Dropdown -->
                    <li class="text-center">
                        <div id="google_translate_element"></div>
                    </li>
                </ul>
            </li>

            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if(Auth::user()->image != null)
                        <img src="{{ asset('img/profile/' . Auth::user()->image) }}" alt class="w-px-40 h-auto rounded-circle">
                        @else
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if(Auth::user()->image != null)
                                        <img src="{{ asset('img/profile/' . Auth::user()->image) }}" alt class="w-px-40 h-auto rounded-circle">
                                        @else
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ Auth::user()->getNameDivisi() }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                        <i class="bx bx-user-check me-2"></i>
                        <span class="align-middle">Profile</span>
                    </a>
                    <li>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>