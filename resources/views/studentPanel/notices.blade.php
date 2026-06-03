<!-- include header here -->
@include('includes.header')
<!-- include sidebar here -->
@include('includes.student_sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 py-4">
            <!-- Header Section -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-0">Student Notice Board</h2>
                    <p class="text-muted small mb-0">Sir, here you will find the latest updates related to the university and projects.</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary px-3 py-2">Latest Updates</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-9 col-lg-11">
                    <!-- Informative Alert -->
                    <div class="alert alert-primary border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 12px; background-color: #f0f7ff;">
                        <div class="bg-primary text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <strong class="d-block text-primary">Announcement Guidelines</strong>
                            <small class="text-muted">Please read the notices given below carefully, and if there is any document link, make sure to check it as well.</small>
                        </div>
                    </div>

                    <!-- Yahan humara Shared Design File include hoga -->
                    @include('includes.notice_list_design')

                </div>
            </div>
        </div>
    </main>

    <!-- include footer here -->
    @include('includes.footer')
</div>