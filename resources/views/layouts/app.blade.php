<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') &mdash; School-app</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/dashboard/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/dashboard/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/dashboard/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/jquery-selectric/selectric.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/dashboard/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/dashboard/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/components.css') }}">
    <!-- Start GA -->
    @stack('styles')
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
    @livewireStyles
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                            data-width="250">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        <div class="search-backdrop"></div>
                        <div class="search-result">
                            <div class="search-header">
                                Histories
                            </div>
                            <div class="search-item">
                                <a href="#">How to hack NASA using CSS</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-item">
                                <a href="#">Kodinger.com</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-item">
                                <a href="#">#Stisla</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-header">
                                Result
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets/img/products/product-3-50.png') }}" alt="product">
                                    oPhone S9 Limited Edition
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets/img/products/product-2-50.png') }}" alt="product">
                                    Drone X2 New Gen-7
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets/img/products/product-1-50.png') }}" alt="product">
                                    Headphone Blitz
                                </a>
                            </div>
                            <div class="search-header">
                                Projects
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <div class="search-icon bg-danger text-white mr-3">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    Stisla Admin Template
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <div class="search-icon bg-primary text-white mr-3">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    Create a new Homepage Design
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                            class="nav-link nav-link-lg message-toggle beep"><i class="far fa-envelope"></i></a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right">
                            <div class="dropdown-header">Messages
                                <div class="float-right">
                                    <a href="#">Mark All As Read</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-message">
                                <a href="#" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-avatar">
                                        <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}"
                                            class="rounded-circle">
                                        <div class="is-online"></div>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>Kusnaedi</b>
                                        <p>Hello, Bro!</p>
                                        <div class="time">10 Hours Ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-avatar">
                                        <img alt="image" src="{{ asset('assets/img/avatar/avatar-2.png') }}"
                                            class="rounded-circle">
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>Dedik Sugiharto</b>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
                                        <div class="time">12 Hours Ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-avatar">
                                        <img alt="image" src="{{ asset('assets/img/avatar/avatar-3.png') }}"
                                            class="rounded-circle">
                                        <div class="is-online"></div>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>Agung Ardiansyah</b>
                                        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                        <div class="time">12 Hours Ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="dropdown-item-avatar">
                                        <img alt="image" src="{{ asset('assets/img/avatar/avatar-4.png') }}"
                                            class="rounded-circle">
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>Ardian Rahardiansyah</b>
                                        <p>Duis aute irure dolor in reprehenderit in voluptate velit ess</p>
                                        <div class="time">16 Hours Ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="dropdown-item-avatar">
                                        <img alt="image" src="{{ asset('assets/img/avatar/avatar-5.png') }}"
                                            class="rounded-circle">
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>Alfa Zulkarnain</b>
                                        <p>Exercitation ullamco laboris nisi ut aliquip ex ea commodo</p>
                                        <div class="time">Yesterday</div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                            class="nav-link notification-toggle nav-link-lg beep"><i class="far fa-bell"></i></a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right">
                            <div class="dropdown-header">Notifications
                                <div class="float-right">
                                    <a href="#">Mark All As Read</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons">
                                <a href="#" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Template update is available now!
                                        <div class="time text-primary">2 Min Ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="dropdown-item-icon bg-info text-white">
                                        <i class="far fa-user"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>You</b> and <b>Dedik Sugiharto</b> are now friends
                                        <div class="time">10 Hours Ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="dropdown-item-icon bg-success text-white">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        <b>Kusnaedi</b> has moved task <b>Fix bug header</b> to <b>Done</b>
                                        <div class="time">12 Hours Ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="dropdown-item-icon bg-danger text-white">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Low disk space. Let's clean it!
                                        <div class="time">17 Hours Ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="dropdown-item-icon bg-info text-white">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Welcome to Stisla template!
                                        <div class="time">Yesterday</div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('assets/dashboard/img/avatar/avatar-1.png') }}"
                                class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title">Logged in 5 min ago</div>
                            <a href="features-profile.html" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <a href="features-activities.html" class="dropdown-item has-icon">
                                <i class="fas fa-bolt"></i> Activities
                            </a>
                            <a href="features-settings.html" class="dropdown-item has-icon">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="index.html">Stisla</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="index.html">St</a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Dashboard</li>

                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-fire"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="menu-header">Starter</li>

                        @hasallroles('super admin')
                            {{-- Academic Setup --}}
                            <li class="{{ request()->routeIs('academic.setup.view') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('academic.setup.view') }}">
                                    <i class="fas fa-school"></i> {{-- changed --}}
                                    <span>Academic Setup</span>
                                </a>
                            </li>

                            {{-- Access Management --}}
                            <li class="{{ request()->routeIs('access.management.view') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('access.management.view') }}">
                                    <i class="fas fa-user-shield"></i> {{-- changed --}}
                                    <span>Access Management</span>
                                </a>
                            </li>
                        @endhasallroles

                        {{-- Students --}}
                        <li class="dropdown {{ request()->routeIs('students.*') ? 'active' : '' }}">
                            <a href="#"
                                class="nav-link has-dropdown {{ request()->routeIs('students.*') ? 'toggled' : '' }}">
                                <i class="fas fa-user-graduate"></i>
                                <span>Students</span>
                            </a>

                            <ul class="dropdown-menu"
                                style="{{ request()->routeIs('students.*') ? 'display: block;' : '' }}">
                                <li class="{{ request()->routeIs('students.create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('students.create') }}">
                                        Create Student
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('students.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('students.index') }}">
                                        View Students
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Attendance --}}
                        @php
                            $attendanceMenuActive =
                                request()->routeIs('attendances.*') || request()->routeIs('teacher-attendances.*');
                        @endphp
                        <li class="dropdown {{ $attendanceMenuActive ? 'active' : '' }}">
                            <a href="#"
                                class="nav-link has-dropdown {{ $attendanceMenuActive ? 'toggled' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                <span>Attendance</span>
                            </a>

                            <ul class="dropdown-menu" style="{{ $attendanceMenuActive ? 'display: block;' : '' }}">
                                <li class="{{ request()->routeIs('attendances.create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('attendances.create') }}">
                                        Mark Student Attendance
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('attendances.index') }}">
                                        View Student Attendance
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('teacher-attendances.create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('teacher-attendances.create') }}">
                                        Mark Teacher Attendance
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('teacher-attendances.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('teacher-attendances.index') }}">
                                        View Teacher Attendance
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Fee Management --}}
                        @php
                            $feeMenuActive =
                                request()->routeIs('fee-types.*') ||
                                request()->routeIs('fee-structures.*') ||
                                request()->routeIs('student-fees.*');
                        @endphp

                        <li class="dropdown {{ $feeMenuActive ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown {{ $feeMenuActive ? 'toggled' : '' }}">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Fee Management</span>
                            </a>

                            <ul class="dropdown-menu" style="{{ $feeMenuActive ? 'display: block;' : '' }}">
                                <li class="{{ request()->routeIs('fee-types.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('fee-types.index') }}">
                                        Fee Type
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('fee-structures.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('fee-structures.index') }}">
                                        Fee Structure
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('student-fees.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('student-fees.index') }}">
                                        Student Fees
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('student-fees.create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('student-fees.create') }}">
                                        Generate Student Fee
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('student-fees.bulk-create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('student-fees.bulk-create') }}">
                                        Bulk Generate Fee
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Exam Management --}}
                        @php
                            $examMenuActive =
                                request()->routeIs('exams.*') ||
                                request()->routeIs('exam-marks.*') ||
                                request()->routeIs('results.*');
                        @endphp

                        <li class="dropdown {{ $examMenuActive ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown {{ $examMenuActive ? 'toggled' : '' }}">
                                <i class="fas fa-file-alt"></i>
                                <span>Exam Management</span>
                            </a>

                            <ul class="dropdown-menu" style="{{ $examMenuActive ? 'display: block;' : '' }}">
                                <li class="{{ request()->routeIs('exams.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('exams.index') }}">
                                        Exam Type
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('exam-marks.create') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('exam-marks.create') }}">
                                        Exam Marks Entry
                                    </a>
                                </li>

                                <li class="{{ request()->routeIs('results.index') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('results.index') }}">
                                        Class Results
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- Guardian Management --}}
                        <li class="{{ request()->routeIs('guardians.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('guardians.index') }}">
                                <i class="fas fa-users"></i>
                                <span>Guardian Management</span>
                            </a>
                        </li>

                        {{-- /// Teacher Management --}}
                        <li class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('teachers.index') }}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>Teacher Management</span>
                            </a>
                        </li>
                    </ul>

                    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                        <a href="#" class="btn btn-primary btn-lg btn-block btn-icon-split">
                            <i class="fas fa-rocket"></i> Website Visit
                        </a>
                    </div>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    @yield('content')
                </section>
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2026 <div class="bullet"></div> Design By <a href="#">Izhar Baloch</a>
                </div>
                <div class="footer-right">

                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('assets/dashboard/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('assets/dashboard/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('assets/dashboard/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/jquery-ui/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets/dashboard/modules/cleave-js/dist/cleave.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/cleave-js/dist/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}">
    </script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/jquery-selectric/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('assets/dashboard/js/page/modules-datatables.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/dashboard/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/dashboard/js/custom.js') }}"></script>

    @stack('scripts')

    @livewireScripts
</body>

</html>
