@include('includes.header')
@include('includes.student_sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-4 fw-bold text-dark">My Project Documentation</h1>
                    <p class="text-muted">Track your SRS/SDD submission and supervisor feedback.</p>
                </div>
                @if($mySubmission)
                    <!-- Is button ko clickable bana diya gaya hai -->
                    <button type="button" class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#editSubmissionModal">
                        <i class="fas fa-edit me-1"></i> Edit Submission
                    </button>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(!$mySubmission)
                <!-- Form for New Submission (Same as before) -->
                <div class="card shadow-sm border-0 border-top border-primary border-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4 text-primary"><i class="fas fa-file-upload me-2"></i> Submit Documentation Links</h5>
                        <form action="{{ route('student.srssdd.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Student Name</label>
                                    <input type="text" class="form-control bg-light border-0" value="{{ $name }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold mb-1">Project Title</label>
                                    <input type="text" class="form-control bg-light border-0" value="{{ $projectTitle }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-primary mb-1">SRS Google Drive Link</label>
                                    <input type="url" name="srs_link" class="form-control shadow-sm" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-primary mb-1">SDD Google Drive Link</label>
                                    <input type="url" name="sdd_link" class="form-control shadow-sm" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Submit Project</button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Dashboard Section -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold text-primary"><i class="fas fa-project-diagram me-2"></i> Documentation Details</h6>
                                <span class="badge bg-dark px-3 py-2">Score: {{ $mySubmission['marks'] ?? 'N/A' }}</span>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-4 py-3 bg-light-subtle" style="width: 35%;">Project Title</th>
                                            <td class="fw-bold text-uppercase">{{ $projectTitle }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 py-3 bg-light-subtle">SRS Document</th>
                                            <td><a href="{{ $mySubmission['srs'] }}" target="_blank" class="fw-bold text-primary"><i class="fab fa-google-drive me-1"></i>View SRS</a></td>
                                        </tr>
                                        <tr>
                                            <th class="ps-4 py-3 bg-light-subtle">SDD Document</th>
                                            <td><a href="{{ $mySubmission['sdd'] }}" target="_blank" class="fw-bold text-info"><i class="fab fa-google-drive me-1"></i>View SDD</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3 border-bottom text-dark fw-bold">
                                <i class="fas fa-comment-dots me-2"></i> Supervisor Feedback
                            </div>
                            <div class="card-body p-4">
                                <div class="p-3 rounded bg-light border-start border-primary border-4 shadow-sm">
                                    <p class="mb-0 italic">{{ $mySubmission['remarks'] ?? 'No feedback yet.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT MODAL (POPUP) -->
                <div class="modal fade" id="editSubmissionModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Update Documentation Links</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('student.srssdd.update') }}" method="POST">
                                @csrf
                                <div class="modal-body p-4">
                                    <div class="mb-3">
                                        <label class="fw-bold mb-1 text-dark">Update SRS Link</label>
                                        <input type="url" name="srs_link" class="form-control" value="{{ $mySubmission['srs'] }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold mb-1 text-dark">Update SDD Link</label>
                                        <input type="url" name="sdd_link" class="form-control" value="{{ $mySubmission['sdd'] }}" required>
                                    </div>
                                    <p class="small text-muted"><i class="fas fa-info-circle me-1"></i> Sir, editing will only update your links. Marks and remarks will remain unchanged.</p>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary fw-bold px-4">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
    @include('includes.footer')
</div>