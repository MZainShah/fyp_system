<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link" href="/supervisor/dashboard">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    
                    <div class="sb-sidenav-menu-heading">Addons</div>
                    <a class="nav-link {{ Request::is('supervisor/student-directory') ? 'active' : '' }}" href="{{ route('supervisor.studentDirectory') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                        My All Students
                    </a>
                    <a class="nav-link {{ Request::is('supervisor/view-documentation') ? 'active' : '' }}" href="{{ route('supervisor.viewDocs') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-signature text-primary"></i></div>
                        View Documentation
                    </a>
                    <a class="nav-link" href="{{ route('supervisor.noticeboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                        Notice Board
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                {{ session('supervisor_name') }}
            </div>
        </nav>
    </div>