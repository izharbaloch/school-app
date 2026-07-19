<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover" name="viewport">
    <title>@yield('title') &mdash; School ERP</title>

    <!-- Google Fonts — Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/fontawesome/css/all.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/jquery-selectric/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/components.css') }}">
    @stack('styles')
    @livewireStyles
</head>

<body class="nativephp-safe-area">
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
                    </ul>
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Quick search..." aria-label="Search" data-width="250">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        <div class="search-backdrop"></div>
                        <div class="search-result">
                            <div class="search-header">Quick Links</div>
                            <div class="search-item">
                                <a href="{{ route('students.index') }}">
                                    <div class="search-icon bg-primary text-white mr-3"><i class="fas fa-user-graduate"></i></div>
                                    All Students
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="{{ route('teachers.index') }}">
                                    <div class="search-icon bg-info text-white mr-3"><i class="fas fa-chalkboard-teacher"></i></div>
                                    Teachers
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="{{ route('admissions.index') }}">
                                    <div class="search-icon bg-success text-white mr-3"><i class="fas fa-file-signature"></i></div>
                                    Admissions
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="{{ route('student-fees.index') }}">
                                    <div class="search-icon bg-warning text-white mr-3"><i class="fas fa-money-bill-wave"></i></div>
                                    Fee Management
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-right">

                    {{-- Notifications bell --}}
                    <li class="dropdown dropdown-list-toggle">
                        <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
                            <i class="far fa-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right">
                            <div class="dropdown-header">
                                Notifications
                                @can('settings.view')
                                <div class="float-right">
                                    <a href="{{ route('activity-logs.index') }}">Activity Log</a>
                                </div>
                                @endcan
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons">
                                <div class="text-center py-4 px-3">
                                    <i class="far fa-bell fa-2x mb-2 d-block" style="color:#cbd5e1"></i>
                                    <small class="text-muted">No new notifications</small>
                                </div>
                            </div>
                            <div class="dropdown-footer text-center">
                                @can('settings.view')
                                <a href="{{ route('activity-logs.index') }}">View Activity Log <i class="fas fa-chevron-right"></i></a>
                                @else
                                <span class="text-muted" style="font-size:.8rem">All caught up</span>
                                @endcan
                            </div>
                        </div>
                    </li>

                    {{-- User dropdown --}}
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title">
                                <div style="font-weight:600;color:#1e293b;font-size:.88rem">{{ Auth::user()->name }}</div>
                                <div style="font-size:.75rem;color:#94a3b8">{{ Auth::user()->email }}</div>
                            </div>
                            @can('settings.view')
                            <a href="{{ route('settings.index') }}" class="dropdown-item has-icon">
                                <i class="fas fa-cog"></i> School Settings
                            </a>
                            @endcan
                            @can('settings.view')
                            <a href="{{ route('activity-logs.index') }}" class="dropdown-item has-icon">
                                <i class="fas fa-history"></i> Activity Log
                            </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item has-icon text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>

                </ul>
            </nav>

            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-graduation-cap" style="color:#f0a500;margin-right:8px"></i>School ERP
                        </a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-graduation-cap" style="color:#f0a500"></i>
                        </a>
                    </div>

                    <ul class="sidebar-menu">
                        <li class="menu-header">Main</li>

                        <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        {{-- Administration --}}
                        @can('settings.view')
                        <li class="menu-header">Administration</li>
                        <li class="{{ request()->routeIs('academic.setup.view') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('academic.setup.view') }}">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Academic Setup</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('academic-sessions.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('academic-sessions.index') }}">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Academic Sessions</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('access.management.view') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('access.management.view') }}">
                                <i class="fas fa-user-shield"></i>
                                <span>Access Management</span>
                            </a>
                        </li>
                        @endcan

                        {{-- People --}}
                        <li class="menu-header">People</li>

                        @can('admissions.view')
                        <li class="{{ request()->routeIs('admissions.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admissions.index') }}">
                                <i class="fas fa-file-signature"></i>
                                <span>Admissions</span>
                            </a>
                        </li>
                        @endcan

                        @can('leaves.view')
                        <li class="{{ request()->routeIs('leaves.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('leaves.index') }}">
                                <i class="fas fa-calendar-times"></i>
                                <span>Leave Management</span>
                            </a>
                        </li>
                        @endcan

                        @can('conduct.view')
                        <li class="{{ request()->routeIs('conduct.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('conduct.index') }}">
                                <i class="fas fa-gavel"></i>
                                <span>Conduct & Discipline</span>
                            </a>
                        </li>
                        @endcan

                        @can('medical.view')
                        <li class="{{ request()->routeIs('medical.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('medical.index') }}">
                                <i class="fas fa-notes-medical"></i>
                                <span>Medical Records</span>
                            </a>
                        </li>
                        @endcan

                        @can('hostel.view')
                        <li class="{{ request()->routeIs('hostel.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('hostel.index') }}">
                                <i class="fas fa-building"></i>
                                <span>Hostel</span>
                            </a>
                        </li>
                        @endcan

                        @can('sports.view')
                        <li class="{{ request()->routeIs('sports.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('sports.index') }}">
                                <i class="fas fa-futbol"></i>
                                <span>Sports & Activities</span>
                            </a>
                        </li>
                        @endcan

                        @can('students.view')
                            @php $studMenuActive = request()->routeIs('students.*') || request()->routeIs('student-promotions.*') || request()->routeIs('certificates.*'); @endphp
                            <li class="dropdown {{ $studMenuActive ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown {{ $studMenuActive ? 'toggled' : '' }}">
                                    <i class="fas fa-user-graduate"></i>
                                    <span>Students</span>
                                </a>
                                <ul class="dropdown-menu" style="{{ $studMenuActive ? 'display: block;' : '' }}">
                                    @can('students.create')
                                    <li class="{{ request()->routeIs('students.create') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('students.create') }}">Add Student</a>
                                    </li>
                                    @endcan
                                    <li class="{{ request()->routeIs('students.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('students.index') }}">All Students</a>
                                    </li>
                                    @can('students.edit')
                                    <li class="{{ request()->routeIs('student-promotions.*') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('student-promotions.index') }}">Promotions</a>
                                    </li>
                                    @endcan
                                    <li class="{{ request()->routeIs('certificates.*') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('certificates.index') }}">Certificates</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('teachers.view')
                        <li class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('teachers.index') }}">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span>Teachers</span>
                            </a>
                        </li>
                        @endcan

                        @can('parents.view')
                        <li class="{{ request()->routeIs('guardians.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('guardians.index') }}">
                                <i class="fas fa-users"></i>
                                <span>Guardians / Parents</span>
                            </a>
                        </li>
                        @endcan

                        {{-- Academic --}}
                        <li class="menu-header">Academic</li>

                        @can('timetable.view')
                        <li class="{{ request()->routeIs('timetable.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('timetable.index') }}">
                                <i class="fas fa-clock"></i>
                                <span>Timetable</span>
                            </a>
                        </li>
                        @endcan

                        @can('attendance.view')
                            @php $attendanceMenuActive = request()->routeIs('attendances.*') || request()->routeIs('teacher-attendances.*'); @endphp
                            <li class="dropdown {{ $attendanceMenuActive ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown {{ $attendanceMenuActive ? 'toggled' : '' }}">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Attendance</span>
                                </a>
                                <ul class="dropdown-menu" style="{{ $attendanceMenuActive ? 'display: block;' : '' }}">
                                    @can('attendance.mark')
                                    <li class="{{ request()->routeIs('attendances.create') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('attendances.create') }}">Mark Student</a>
                                    </li>
                                    @endcan
                                    <li class="{{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('attendances.index') }}">Student Attendance</a>
                                    </li>
                                    @can('attendance.mark')
                                    <li class="{{ request()->routeIs('teacher-attendances.create') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('teacher-attendances.create') }}">Mark Teacher</a>
                                    </li>
                                    @endcan
                                    @can('teachers.view')
                                    <li class="{{ request()->routeIs('teacher-attendances.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('teacher-attendances.index') }}">Teacher Attendance</a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        @can('exams.view')
                            @php $examMenuActive = request()->routeIs('exams.*') || request()->routeIs('exam-marks.*') || request()->routeIs('results.*') || request()->routeIs('exam-schedule.*'); @endphp
                            <li class="dropdown {{ $examMenuActive ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown {{ $examMenuActive ? 'toggled' : '' }}">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Examinations</span>
                                </a>
                                <ul class="dropdown-menu" style="{{ $examMenuActive ? 'display: block;' : '' }}">
                                    @can('exams.edit')
                                    <li class="{{ request()->routeIs('exams.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('exams.index') }}">Manage Exams</a>
                                    </li>
                                    <li class="{{ request()->routeIs('exam-schedule.*') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('exam-schedule.index') }}">Exam Timetable</a>
                                    </li>
                                    @endcan
                                    @can('marks.create')
                                    <li class="{{ request()->routeIs('exam-marks.create') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('exam-marks.create') }}">Marks Entry</a>
                                    </li>
                                    @endcan
                                    @can('marks.view')
                                    <li class="{{ request()->routeIs('results.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('results.index') }}">Class Results</a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        @can('homework.view')
                        <li class="{{ request()->routeIs('homework.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('homework.index') }}">
                                <i class="fas fa-book-open"></i>
                                <span>Homework</span>
                            </a>
                        </li>
                        @endcan

                        {{-- Finance --}}
                        <li class="menu-header">Finance</li>

                        @can('fees.view')
                            @php $feeMenuActive = request()->routeIs('fee-types.*') || request()->routeIs('fee-structures.*') || request()->routeIs('student-fees.*'); @endphp
                            <li class="dropdown {{ $feeMenuActive ? 'active' : '' }}">
                                <a href="#" class="nav-link has-dropdown {{ $feeMenuActive ? 'toggled' : '' }}">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Fee Management</span>
                                </a>
                                <ul class="dropdown-menu" style="{{ $feeMenuActive ? 'display: block;' : '' }}">
                                    @can('fees.edit')
                                    <li class="{{ request()->routeIs('fee-types.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('fee-types.index') }}">Fee Types</a>
                                    </li>
                                    <li class="{{ request()->routeIs('fee-structures.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('fee-structures.index') }}">Fee Structure</a>
                                    </li>
                                    @endcan
                                    <li class="{{ request()->routeIs('student-fees.index') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('student-fees.index') }}">Student Fees</a>
                                    </li>
                                    @can('fees.create')
                                    <li class="{{ request()->routeIs('student-fees.create') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('student-fees.create') }}">Generate Fee</a>
                                    </li>
                                    <li class="{{ request()->routeIs('student-fees.bulk-create') ? 'active' : '' }}">
                                        <a class="nav-link" href="{{ route('student-fees.bulk-create') }}">Bulk Generate</a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        @can('accounting.view')
                        <li class="{{ request()->routeIs('accounting.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('accounting.index') }}">
                                <i class="fas fa-coins"></i>
                                <span>Accounting</span>
                            </a>
                        </li>
                        @endcan

                        @can('reports.view')
                        <li class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar"></i>
                                <span>Reports</span>
                            </a>
                        </li>
                        @endcan

                        {{-- Services --}}
                        <li class="menu-header">Services</li>

                        @can('library.view')
                        <li class="{{ request()->routeIs('library.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('library.index') }}">
                                <i class="fas fa-book"></i>
                                <span>Library</span>
                            </a>
                        </li>
                        @endcan

                        @can('settings.view')
                        <li class="{{ request()->routeIs('transport.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('transport.index') }}">
                                <i class="fas fa-bus"></i>
                                <span>Transport</span>
                            </a>
                        </li>
                        @endcan

                        {{-- Communication --}}
                        <li class="menu-header">Communication</li>

                        @can('notices.view')
                        <li class="{{ request()->routeIs('notices.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('notices.index') }}">
                                <i class="fas fa-bullhorn"></i>
                                <span>Notice Board</span>
                            </a>
                        </li>
                        @endcan

                        @can('notices.view')
                        <li class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('events.index') }}">
                                <i class="fas fa-calendar-star"></i>
                                <span>Events & Calendar</span>
                            </a>
                        </li>
                        @endcan

                        @hasrole('parent')
                        <li class="menu-header">My Children</li>
                        <li class="{{ request()->routeIs('parent.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('parent.dashboard') }}">
                                <i class="fas fa-child"></i>
                                <span>Parent Dashboard</span>
                            </a>
                        </li>
                        @endhasrole

                        @can('settings.view')
                        <li class="menu-header">System</li>
                        <li class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('settings.index') }}">
                                <i class="fas fa-cog"></i>
                                <span>School Settings</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('activity-logs.index') }}">
                                <i class="fas fa-history"></i>
                                <span>Activity Logs</span>
                            </a>
                        </li>
                        @endcan

                    </ul>

                    {{-- Sidebar user card --}}
                    <div class="mt-4 mb-3 p-3 hide-sidebar-mini">
                        <div class="sidebar-user-card">
                            <div class="suc-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <div class="suc-info">
                                <div class="suc-name">{{ Auth::user()->name }}</div>
                                <div class="suc-role">{{ ucfirst(str_replace(['-', '_'], ' ', Auth::user()->getRoleNames()->first() ?? 'User')) }}</div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                                @csrf
                                <button type="submit" class="suc-logout" title="Logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
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
                    Copyright &copy; {{ date('Y') }} &nbsp;<div class="bullet"></div>&nbsp; <a href="#">School ERP</a> &mdash; Design By <a href="#">Izhar Baloch</a>
                </div>
                <div class="footer-right">
                    <span class="text-muted" style="font-size:.78rem">v1.0</span>
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

    <!-- JS Libraries -->
    <script src="{{ asset('assets/dashboard/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/cleave-js/dist/cleave.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/cleave-js/dist/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/dashboard/modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
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
