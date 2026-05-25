@include('includes.header')
@include('includes.supervisor_sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <h1 class="fw-bold mb-0">View Documentation</h1>
                    <p class="text-muted">Manage SRS/SDD links, feedback, and grading in one place.</p>
                </div>
                <div>
                    <!-- Download Marks Sheet Button -->
                    <a href="{{ route('supervisor.downloadMarks') }}" class="btn btn-success shadow-sm px-4 fw-bold">
                        <i class="fas fa-file-excel me-2"></i> Download Marks Sheet
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Roll Number</th>
                                    <th>Student Name</th>
                                    <th>SRS / SDD Links</th>
                                    <th>Current Remarks</th>
                                    <th>Marks</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentationList as $doc)
                                @php 
                                    // Modal ID banane ke liye roll number se spaces aur dashes khatam kar rahe hain
                                    $safeId = preg_replace('/[^A-Za-z0-9]/', '', $doc['roll']); 
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $doc['roll'] }}</td>
                                    <td>{{ $doc['name'] }}</td>
                                    <td>
                                        <a href="{{ $doc['srs'] }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fab fa-google-drive"></i> SRS
                                        </a>
                                        <a href="{{ $doc['sdd'] }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fab fa-google-drive"></i> SDD
                                        </a>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $doc['remarks'] ? Str::limit($doc['remarks'], 30) : 'No remarks' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark">{{ $doc['marks'] ?: '0' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Evaluate Button -->
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal{{ $safeId }}">
                                            <i class="fas fa-edit"></i> Evaluate
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals Section: Table ke bahar taake background black na ho -->
    @foreach($documentationList as $doc)
        @php $safeId = preg_replace('/[^A-Za-z0-9]/', '', $doc['roll']); @endphp
        <div class="modal fade" id="modal{{ $safeId }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <form action="{{ route('supervisor.updateEvaluation') }}" method="POST">
                        @csrf
                        <input type="hidden" name="roll_number" value="{{ $doc['roll'] }}">
                        
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold">Evaluation: {{ $doc['name'] }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Supervisor Remarks</label>
                                <textarea name="remarks" class="form-control border-2" rows="4" placeholder="Sir, enter your feedback...">{{ $doc['remarks'] }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">Marks</label>
                                <input type="number" name="marks" class="form-control border-2" value="{{ $doc['marks'] }}" placeholder="Enter marks">
                            </div>
                        </div>
                        
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Save Evaluation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    </main>
    @include('includes.footer')
</div>