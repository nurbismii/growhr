<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo d-flex align-items-center">
                <svg width="256px" height="256px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" transform="matrix(1, 0, 0, 1, 0, 0)rotate(0)">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.144"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19 8.5L17 5.5H14.5L15.5 8.5L12 18.5L19 8.5Z" stroke="#9C4FFF" stroke-width="1.56" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M4.37596 8.08397C4.1462 8.42862 4.23933 8.89427 4.58397 9.12404C4.92862 9.3538 5.39427 9.26067 5.62404 8.91603L4.37596 8.08397ZM7 5.5V4.75C6.74924 4.75 6.51506 4.87533 6.37596 5.08397L7 5.5ZM9.5 6.25C9.91421 6.25 10.25 5.91421 10.25 5.5C10.25 5.08579 9.91421 4.75 9.5 4.75V6.25ZM5.61442 8.0699C5.37689 7.73057 4.90924 7.64804 4.5699 7.88558C4.23057 8.12311 4.14804 8.59076 4.38558 8.9301L5.61442 8.0699ZM12 18.5L11.3856 18.9301C11.6004 19.237 12.0088 19.3383 12.3421 19.1674C12.6755 18.9965 12.8317 18.6058 12.7079 18.2522L12 18.5ZM9.20789 8.25224C9.07106 7.86128 8.6432 7.65527 8.25224 7.79211C7.86128 7.92894 7.65527 8.3568 7.79211 8.74776L9.20789 8.25224ZM5 7.75C4.58579 7.75 4.25 8.08579 4.25 8.5C4.25 8.91421 4.58579 9.25 5 9.25V7.75ZM8.5 9.25C8.91421 9.25 9.25 8.91421 9.25 8.5C9.25 8.08579 8.91421 7.75 8.5 7.75V9.25ZM10.2115 5.73717C10.3425 5.34421 10.1301 4.91947 9.73717 4.78849C9.34421 4.6575 8.91947 4.86987 8.78849 5.26283L10.2115 5.73717ZM7.78849 8.26283C7.6575 8.65579 7.86987 9.08053 8.26283 9.21151C8.65579 9.3425 9.08053 9.13013 9.21151 8.73717L7.78849 8.26283ZM9.5 4.75C9.08579 4.75 8.75 5.08579 8.75 5.5C8.75 5.91421 9.08579 6.25 9.5 6.25V4.75ZM14.5 6.25C14.9142 6.25 15.25 5.91421 15.25 5.5C15.25 5.08579 14.9142 4.75 14.5 4.75V6.25ZM8.5 7.75C8.08579 7.75 7.75 8.08579 7.75 8.5C7.75 8.91421 8.08579 9.25 8.5 9.25V7.75ZM19 9.25C19.4142 9.25 19.75 8.91421 19.75 8.5C19.75 8.08579 19.4142 7.75 19 7.75V9.25ZM5.62404 8.91603L7.62404 5.91603L6.37596 5.08397L4.37596 8.08397L5.62404 8.91603ZM7 6.25H9.5V4.75H7V6.25ZM4.38558 8.9301L11.3856 18.9301L12.6144 18.0699L5.61442 8.0699L4.38558 8.9301ZM12.7079 18.2522L9.20789 8.25224L7.79211 8.74776L11.2921 18.7478L12.7079 18.2522ZM5 9.25H8.5V7.75H5V9.25ZM8.78849 5.26283L7.78849 8.26283L9.21151 8.73717L10.2115 5.73717L8.78849 5.26283ZM9.5 6.25H14.5V4.75H9.5V6.25ZM8.5 9.25H19V7.75H8.5V9.25Z" fill="#9C4FFF"></path>
                    </g>
                </svg>
                <span class="" style="color: #9C4FFF; font-size: 22px;">GrowHR</span>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- INSIGHT --}}
        <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-compass"></i>
                <div data-i18n="Analytics">Pekerjaan Harian</div>
            </a>
        </li>

        {{-- CORE --}}
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Core</span></li>

        <li class="menu-item {{ request()->routeIs('user.index') ? 'active' : '' }}">
            <a href="{{ route('user.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Analytics">Pengguna</div>
            </a>
        </li>

        {{-- PAGES --}}
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Pages</span></li>

        <li class="menu-item {{ request()->routeIs('log-harian.index') ? 'active' : '' }}">
            <a href="{{ route('log-harian.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-compass"></i>
                <div data-i18n="Analytics">Pekerjaan Harian</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('laporan-masalah.index') ? 'active' : '' }}">
            <a href="{{ route('laporan-masalah.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-error"></i>
                <div data-i18n="Analytics">Laporan Kendala</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('laporan-hasil.index') ? 'active' : '' }}">
            <a href="{{ route('laporan-hasil.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-check-circle"></i>
                <div data-i18n="Analytics">Laporan Hasil</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('laporan-pelayanan.index') ? 'active' : '' }}">
            <a href="{{ route('laporan-pelayanan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div data-i18n="Analytics">Pelayanan</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('penilaian*') ? 'active' : '' }}">
            <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon tf-icons bx bx-star"></i>
                <div data-i18n="Analytics">Penilaian</div>
            </a>
        </li>

        {{-- ACCOUNT --}}
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Account</span></li>

        <li class="menu-item {{ request()->routeIs('profile.index') ? 'active' : '' }}">
            <a href="{{ route('profile.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-check"></i>
                <div data-i18n="Basic">My Profile</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('setting*') ? 'active' : '' }}">
            <a href="cards-basic.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Basic">Setting</div>
            </a>
        </li>

        {{-- WIDGET --}}
        @if(Auth::user()->role == 'ADMIN')
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Widget</span></li>

        <li class="menu-item {{ request()->routeIs('status-pekerjaan.index', 'jenis-pekerjaan.index', 'kategori-pekerjaan.index', 'divisi.index', 'prioritas.index', 'kategori-pelayanan.index', 'sub-kategori-pelayanan.index') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-menu-alt-right"></i>
                <div data-i18n="Form Elements">Dropdown</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('status-pekerjaan.index') ? 'active' : '' }}">
                    <a href="{{ route('status-pekerjaan.index') }}" class="menu-link">
                        <div data-i18n="Basic Inputs">Status Pekerjaan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('jenis-pekerjaan.index') ? 'active' : '' }}">
                    <a href="{{ route('jenis-pekerjaan.index') }}" class="menu-link">
                        <div data-i18n="Input groups">Jenis Pekerjaan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('kategori-pekerjaan.index') ? 'active' : '' }}">
                    <a href="{{ route('kategori-pekerjaan.index') }}" class="menu-link">
                        <div data-i18n="Input groups">Kategori Pekerjaan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('divisi.index') ? 'active' : '' }}">
                    <a href="{{ route('divisi.index') }}" class="menu-link">
                        <div data-i18n="Input groups">Divisi</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('prioritas.index') ? 'active' : '' }}">
                    <a href="{{ route('prioritas.index') }}" class="menu-link">
                        <div data-i18n="Input groups">Prioritas</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('kategori-pelayanan.index') ? 'active' : '' }}">
                    <a href="{{ route('kategori-pelayanan.index') }}" class="menu-link">
                        <div data-i18n="Input groups">Kategori Pelayanan</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('sub-kategori-pelayanan.index') ? 'active' : '' }}">
                    <a href="{{ route('sub-kategori-pelayanan.index') }}" class="menu-link">
                        <div data-i18n="Input groups">Sub Kategori Pelayanan</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

    </ul>

</aside>