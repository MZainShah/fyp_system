@include('includes.header')
@include('includes.student_sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4 fw-bold">My Project</h1>
            <p class="text-muted">Manage your Final Year Project submission and track progress.</p>

            <div class="row">
                <div class="col-lg-8">
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                    @endif

                    {{-- 1. VIEW MODE: Agar data pehle se submit hai --}}
                    @if($existingData)
                    <div id="viewMode" class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-primary"><i class="fas fa-folder-open me-2"></i>Project Details</h6>
                            <button onclick="toggleEdit()" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit me-1"></i> Edit Project
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <tr class="border-bottom">
                                    <th class="bg-light ps-4" style="width: 30%;">Project Title</th>
                                    <td class="ps-4 fw-bold">{{ $existingData['title'] }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="bg-light ps-4">Project Status</th>
                                    <td class="ps-4">
                                        @if($existingData['status'] == 'Approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($existingData['status'] == 'Pending')
                                            <span class="badge bg-warning text-dark">Pending Review</span>
                                        @else
                                            <span class="badge bg-danger">{{ $existingData['status'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-light ps-4">Drive Link</th>
                                    <td class="ps-4">
                                        <a href="{{ $existingData['link'] }}" target="_blank" class="btn btn-sm btn-link text-decoration-none">
                                            <i class="fab fa-google-drive me-1"></i> Open Google Drive Folder
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif

                    {{-- 2. EDIT/SUBMISSION MODE: Form jo toggle hoga --}}
                    <div id="editMode" class="card shadow-sm border-0 mb-4" style="{{ $existingData ? 'display:none;' : '' }}">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold text-primary">
                                <i class="fas fa-edit me-2"></i>{{ $existingData ? 'Edit Submission' : 'Initial Submission' }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('student.project.submit') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Project Title</label>
                                    <input type="text" name="title" value="{{ $existingData['title'] ?? '' }}" class="form-control form-control-lg" placeholder="Enter project title" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-danger">Google Drive Folder Link</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fab fa-google-drive"></i></span>
                                        <input type="url" name="drive_link" value="{{ $existingData['link'] ?? '' }}" class="form-control" placeholder="https://drive.google.com/..." required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Short Description / Abstract</label>
                                    <textarea name="description" class="form-control" rows="4">{{ $existingData['desc'] ?? '' }}</textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i> Save & Submit
                                    </button>
                                    @if($existingData)
                                        <button type="button" onclick="toggleEdit()" class="btn btn-light px-4">Cancel</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- 3. NICHE WALA KHALI HISSA: Timeline & Remarks --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="fw-bold mb-3">Project Progress & Feedback</h5>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 border-end">
                                            <h6 class="text-muted small text-uppercase fw-bold">Supervisor Remarks</h6>
                                            <div class="p-3 bg-light rounded mt-2">
                                                @if(!empty($existingData['remarks']))
            <p class="mb-0 italic">"{{ $existingData['remarks'] }}"</p>
        @else
            <p class="mb-0 text-muted small"><i class="fas fa-info-circle me-1"></i> No comments from supervisor yet.</p>
        @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 ps-4">
                                            <h6 class="text-muted small text-uppercase fw-bold">Submission Timeline</h6>
                                            <ul class="list-unstyled mt-3 small">
                                                <li class="mb-2 text-success"><i class="fas fa-check-circle me-2"></i> Account Verified</li>
                                                <li class="mb-2 {{ $existingData ? 'text-success' : 'text-muted' }}">
                                                    <i class="fas {{ $existingData ? 'fa-check-circle' : 'fa-circle' }} me-2"></i> Project Submitted
                                                </li>
                                                <li class="text-muted"><i class="far fa-circle me-2"></i> Supervisor Approval</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Side Info --}}
                <div class="col-lg-4">
                    <div class="card bg-light border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Submission Rules</h6>
                            <ul class="small text-muted ps-3">
                                <li>Only one submission is allowed per student.</li>
                                <li>Updating the link will overwrite the previous submission.</li>
                                <li>The status will reset to <b>Pending</b> after any update.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')
</div>

{{-- JS for Toggling --}}
<script>
    function toggleEdit() {
        var view = document.getElementById('viewMode');
        var edit = document.getElementById('editMode');
        if (view.style.display === "none") {
            view.style.display = "block";
            edit.style.display = "none";
        } else {
            view.style.display = "none";
            edit.style.display = "block";
        }
    }
</script>

<style>
    .bg-light { background-color: #f8f9fa !important; }
    .card { border-radius: 10px; }
    .badge { padding: 8px 12px; font-size: 0.85rem; }
</style>