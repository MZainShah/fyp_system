@include('includes.header')
@include('includes.supervisor_sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-4 fw-bold">Student Directory</h1>
                    <p class="text-muted">Tracking your complete assigned batch from Allocation Sheet.</p>
                </div>
                <div class="badge bg-dark p-2">{{ count($directoryList) }} Students Assigned</div>
            </div>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-list-ol me-2"></i>Batch Progress Tracker</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Roll Number</th>
                                    <th>Student Name</th>
                                    <th>Submission Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($directoryList as $student)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $student['roll'] }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td>
                                        @if($student['status'] == 'Not Submitted Yet')
                                            <span class="badge bg-secondary opacity-75">Not Submitted</span>
                                        @elseif($student['status'] == 'Approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($student['status'] == 'Revision Needed')
                                            <span class="badge bg-warning text-dark">Revision Sent</span>
                                        @else
                                            <span class="badge bg-info text-dark">{{ $student['status'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($student['status'] == 'Not Submitted Yet')
                                            <button class="btn btn-sm btn-outline-danger shadow-sm">
                                                <i class="fas fa-bell me-1"></i> Send Reminder
                                            </button>
                                        @else
                                            <span class="text-success small fw-bold"><i class="fas fa-check-double"></i> Submitted</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="50" class="mb-3 opacity-50">
                                        <p class="text-muted">Sir, no students are allocated to you in the Allocation Sheet.</p>
                                    </td>
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
    .badge { padding: 0.5em 0.8em; border-radius: 6px; }
    .table-hover tbody tr:hover { background-color: rgba(78, 115, 223, 0.05); }
</style>