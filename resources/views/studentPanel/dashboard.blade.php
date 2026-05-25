@include('includes.header')
@include('includes.student_sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4 fw-bold">Student Dashboard</h1>
        <div class="text-muted mt-4">
            <i class="fas fa-calendar-alt me-1"></i> {{ date('D, d M Y') }}
        </div>
    </div>
    
    <p class="mb-4 text-muted">Final Year Project Management System</p>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small fw-bold text-primary text-uppercase mb-1">Student Profile</div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ session('student_name') }}</div>
                            <div class="text-muted small">{{ session('student_roll') }}</div>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small fw-bold text-success text-uppercase mb-1">Assigned Supervisor</div>
                            <div class="h5 mb-0 fw-bold text-dark">{{ session('supervisor_name') ?? 'Not Assigned Yet' }}</div>
                            <div class="text-muted small">FYP Mentor</div>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="small fw-bold text-warning text-uppercase mb-1">Project Phase</div>
                            <div class="h5 mb-0 fw-bold text-dark">
                                @if(isset($projectDetails) && $projectDetails)
            {{-- Agar data mil gaya toh status dikhao --}}
            <span class="text-primary">{{ $projectDetails['status'] }}</span>
        @else
            {{-- Agar data nahi mila toh --}}
            <span class="text-danger">Not Submitted</span>
        @endif
                            </div>
                            <div class="text-muted small">
                                @if(isset($projectDetails) && $projectDetails)
            Current Progress Phase
        @else
            Please submit your project link
        @endif
                            </div>
                        </div>
                        <div class="ms-2">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-bullhorn me-2"></i>Recent Announcements</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 shadow-none mb-0">
                        <strong>Notice:</strong> Please ensure your Project Title is submitted and approved by your supervisor before 15th May.
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-file-alt me-2"></i>Submission Status</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Deliverable</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
    <tr>
        <td class="ps-3">
            <!-- Sirf Title show hoga, marks yahan se remove kar diye hain -->
            <div class="fw-bold">{{ $projectDetails['title'] ?? 'FYPMS' }}</div>
            <div class="small text-muted">Final Year Project Phase I</div>
        </td>
        <td>15 May 2026</td>
        <td>
            @php
                $status = $projectDetails['status'] ?? 'Pending';
                // Status ke mutabiq colors ka logic
                $badgeColor = match($status) {
                    'Approved' => 'bg-success',
                    'Revision Needed' => 'bg-warning text-dark',
                    'Rejected' => 'bg-danger',
                    'Pending' => 'bg-primary',
                    default => 'bg-secondary'
                };
            @endphp
            <!-- Ye badge batayega ke supervisor ne kya update kiya hai -->
            <span class="badge {{ $badgeColor }}">{{ $status }}</span>
        </td>
        <td class="text-center">
            <a href="{{ route('student.project.view') }}" class="btn btn-sm btn-outline-primary">View Details</a>
        </td>
    </tr>
    <!-- SRS Row Static hi rahay gi -->
    <tr>
        <td class="ps-3 fw-bold">SRS Document</td>
        <td>30 June 2026</td>
        <td><span class="badge bg-secondary">Locked</span></td>
        <td class="text-center">
            <button class="btn btn-sm btn-light" disabled>Locked</button>
        </td>
    </tr>
</tbody>
            </table>
        </div>
    </div>
</div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 fw-bold"><i class="fas fa-link me-2"></i>Quick Resources</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action small">
                        <i class="fas fa-file-pdf text-danger me-2"></i> SRS Template Download
                    </a>
                    <a href="#" class="list-group-item list-group-item-action small">
                        <i class="fas fa-file-word text-primary me-2"></i> Thesis Format Guidelines
                    </a>
                    <a href="#" class="list-group-item list-group-item-action small">
                        <i class="fas fa-external-link-alt text-success me-2"></i> IUB FYP Portal Guide
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); }
    .border-primary { border-color: #4e73df !important; }
    .border-success { border-color: #1cc88a !important; }
    .border-warning { border-color: #f6c23e !important; }
</style>
    </main>
    @include('includes.footer')
</div>