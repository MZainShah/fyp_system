@include('includes.header')
@include('includes.supervisor_sidebar') {{-- Make sure aapka sidebar include sahi ho --}}

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-4 fw-bold text-dark">Supervisor Portal</h1>
                    <p class="text-muted">Welcome back, Sir! Here is an overview of your assigned students.</p>
                </div>
                <div class="text-end">
                    <div class="badge bg-primary p-2 mb-1">{{ date('l, d M Y') }}</div>
                    <div class="small text-muted"><i class="fas fa-user-tie me-1"></i> {{ session('supervisor_name') }}</div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-primary text-uppercase mb-1">Total Students</div>
                        <div class="h4 mb-0 fw-bold text-dark">{{ $stats['total'] }}</div>
                    </div>
                    <div class="ms-2">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-warning text-uppercase mb-1">Pending Reviews</div>
                        <div class="h4 mb-0 fw-bold text-dark">{{ sprintf('%02d', $stats['pending']) }}</div>
                    </div>
                    <div class="ms-2">
                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

                <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-success text-uppercase mb-1">Approved Projects</div>
                        <div class="h4 mb-0 fw-bold text-dark">{{ sprintf('%02d', $stats['approved']) }}</div>
                    </div>
                    <div class="ms-2">
                        <i class="fas fa-check-double fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions Today Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-info text-uppercase mb-1">Submissions Today</div>
                        <div class="h4 mb-0 fw-bold text-dark">{{ sprintf('%02d', $stats['today']) }}</div>
                    </div>
                    <div class="ms-2">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-list me-2"></i>Assigned Students & Projects</h6>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Search student...">
                        <button class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Student Info</th>
                                    <th>Project Title</th>
                                    <th>Submission Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
    @forelse($myStudents as $student)
    <tr>
        <td class="ps-4">
            <div class="fw-bold text-dark">{{ $student['name'] }}</div>
            <div class="small text-muted">{{ $student['roll'] }}</div>
        </td>
        <td><div class="text-truncate" style="max-width: 250px;">{{ $student['title'] }}</div></td>
        <td><span class="small">{{ $student['date'] }}</span></td>
        <td>
            @if($student['status'] == 'Approved')
                <span class="badge bg-success">Approved</span>
            @else
                <span class="badge bg-warning text-dark">{{ $student['status'] }}</span>
            @endif
        </td>
        <td class="text-center">
            <button class="btn btn-sm btn-primary px-3 shadow-sm" 
                    onclick="openReviewModal({{ json_encode($student) }})">
                <i class="fas fa-eye me-1"></i> Review
            </button>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center py-4 text-muted">Sir, no students have submitted projects yet.</td>
    </tr>
    @endforelse
</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')
</div>

<style>
    .card { transition: transform 0.2s; border-radius: 12px; }
    .card:hover { transform: translateY(-2px); }
    .table thead th { font-size: 0.85rem; letter-spacing: 0.5px; border: none; }
    .badge { padding: 8px 12px; font-size: 0.75rem; border-radius: 6px; }
    .text-gray-300 { color: #dddfeb; }
    .border-primary { border-color: #4e73df !important; }
    .border-success { border-color: #1cc88a !important; }
    .border-warning { border-color: #f6c23e !important; }
    .border-info { border-color: #36b9cc !important; }
</style>


<!-- Review Project Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Review Project Submission</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reviewForm" action="{{ route('supervisor.updateStatus') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Student Info (Read-Only) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">Student Name</label>
                            <input type="text" id="modal_student_name" class="form-control bg-light" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Roll Number</label>
                            <input type="text" id="modal_roll" name="roll" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold">Project Title</label>
                        <input type="text" id="modal_title" class="form-control bg-light" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold">Project Description</label>
                        <textarea id="modal_desc" class="form-control bg-light" rows="3" readonly placeholder="No description provided by student."></textarea>
                    </div>

                    <!-- Drive Link Section -->
                    <div class="mb-3 text-center py-2 bg-light rounded border">
                        <label class="small fw-bold d-block mb-2">Project Drive Link</label>
                        <a href="#" id="modal_link" target="_blank" class="btn btn-outline-primary btn-sm px-4">
                            <i class="fas fa-external-link-alt me-1"></i> Open Drive Folder
                        </a>
                    </div>

                    <hr class="my-4">

                    <!-- Status Update -->
                    <div class="mb-3">
                        <label class="small fw-bold text-primary">Update Status</label>
                        <select name="status" id="modal_status" class="form-select border-primary shadow-sm">
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Revision Needed">Revision Needed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>

                    <!-- Supervisor Remarks -->
                    <div class="mb-3">
                        <label class="small fw-bold">Supervisor Remarks</label>
                        <textarea name="remarks" id="modal_remarks" class="form-control" rows="3" placeholder="Write your feedback here..."></textarea>
                    </div>

                    <!-- Marks Section (Internal) -->
                    <!-- <div class="mb-3">
                        <label class="small fw-bold text-success">Internal Marks (0-100)</label>
                        <input type="number" name="marks" id="modal_marks" class="form-control border-success shadow-sm" 
                               min="0" max="100" placeholder="Yahan marks enter karein...">
                        <div class="mt-1">
                            <small class="text-muted italic" style="font-size: 0.75rem;">
                                <i class="fas fa-info-circle me-1"></i> Sir, ye marks sirf aapke record ke liye hain (Google Sheet mein save honge).
                            </small>
                        </div>
                    </div> -->
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Save Decisions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * Modal load karne ka function
     * @param {Object} student - Student ka data jo row se pass ho raha hai
     */
    function openReviewModal(student) {
        // Form fields fill karna
        document.getElementById('modal_student_name').value = student.name || '';
        document.getElementById('modal_roll').value = student.roll || '';
        document.getElementById('modal_title').value = student.title || '';
        document.getElementById('modal_desc').value = student.desc || '';
        document.getElementById('modal_link').href = student.link || '#';
        
        // Dropdown aur Textarea set karna
        document.getElementById('modal_status').value = student.status || 'Pending';
        document.getElementById('modal_remarks').value = student.remarks || '';
        
        // Marks fill karna (agar pehle se save hain toh nazar ayenge)
        // document.getElementById('modal_marks').value = student.marks || ''; 
        
        // Modal show karna
        var myModal = new bootstrap.Modal(document.getElementById('reviewModal'));
        myModal.show();
    }
</script>