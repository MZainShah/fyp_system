<!-- include header here -->
@include('includes.header')
<!-- include sidebar here -->
@include('includes.supervisor_sidebar') <!-- Ensure this is the correct supervisor sidebar -->

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 py-4">
            <!-- Header Section Updated for Supervisor -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-0">Supervisor Notice Board</h2>
                    <p class="text-muted small mb-0">Sir, yahan supervisors ke liye university ki latest updates hongi.</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-success px-3 py-2">Supervisor Access</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-9 col-lg-11">
                    <!-- Informative Alert -->
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 12px; background-color: #f0fff4;">
                        <div class="bg-success text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <strong class="d-block text-success">Supervisor Instructions</strong>
                            <small class="text-muted">Apne relevant notices aur guidelines yahan se check karein.</small>
                        </div>
                    </div>

                    @include('includes.notice_list_design')
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')
</div>