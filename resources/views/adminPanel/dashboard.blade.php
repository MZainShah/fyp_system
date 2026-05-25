@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4 shadow-sm border-0">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small text-white-50 text-uppercase fw-bold">Total Notices</div>
                                <h2 class="fw-bold mb-0 mt-1">{{ $totalNotices }}</h2>
                            </div>
                            <i class="fas fa-bullhorn fa-2x text-white-50"></i>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between bg-dark bg-opacity-10 border-0">
                            <a class="small text-white stretched-link text-decoration-none" href="{{ url('admin/all-notices') }}">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4 shadow-sm border-0">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small text-white-50 text-uppercase fw-bold">System Status</div>
                                <h4 class="fw-bold mb-0 mt-2">Administrator</h4>
                            </div>
                            <i class="fas fa-user-shield fa-2x text-white-50"></i>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between bg-dark bg-opacity-10 border-0">
                            <span class="small text-white">System Secured</span>
                            <div class="small text-white"><i class="fas fa-shield-alt"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4 shadow-sm border-0">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <div class="small text-white-50 text-uppercase fw-bold">Database Status</div>
                                <h4 class="fw-bold mb-0 mt-2">Live Connected</h4>
                            </div>
                            <i class="fas fa-database fa-2x text-white-50"></i>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between bg-dark bg-opacity-10 border-0">
                            <span class="small text-white">Google Sheet Live</span>
                            <div class="small text-white"><i class="fas fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
    <div class="card bg-danger text-white mb-4 shadow-sm border-0">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-white-50 text-uppercase fw-bold">Student Center</div>
                <h4 class="fw-bold mb-0 mt-2">Marksheet Section</h4>
            </div>
            <i class="fas fa-file-download fa-2x text-white-50"></i>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between bg-dark bg-opacity-10 border-0">
            <a class="small text-white text-decoration-none fw-bold d-flex align-items-center justify-content-between w-100 py-1" href="{{ url('admin/download-marksheet') }}">
                <span><i class="fas fa-file-excel me-2"></i> Download All Marksheets</span>
                <i class="fas fa-download"></i>
            </a>
        </div>
    </div>
</div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <span>
                        <i class="fas fa-table me-1 text-primary"></i>
                        <strong>Recent Notices Log (Live Stream)</strong>
                    </span>
                    <span class="badge bg-primary">Top 5 Newest</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle" id="datatablesSimple" width="100%" cellspacing="0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Published By</th>
                                    <th>Notice Title</th>
                                    <th>Message Preview</th>
                                    <th>Document Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentNotices as $notice)
                                <tr>
                                    <td>{{ date('d-M-Y', strtotime($notice[0])) }}</td>
                                    <td><i class="fas fa-user-circle me-1 text-primary"></i> {{ $notice[1] }}</td>
                                    <td class="fw-bold text-dark">{{ $notice[2] }}</td>
                                    <td>{{ Str::limit($notice[3], 70, '...') }}</td>
                                    <td>
                                        @if(!empty($notice[4]))
                                            <a href="{{ $notice[4] }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">
                                                <i class="fas fa-external-link-alt small"></i> View Doc
                                            </a>
                                        @else
                                            <span class="text-muted small">No File</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-50"></i>
                                        Sir, Google Sheet mein koi record nahi mila.
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