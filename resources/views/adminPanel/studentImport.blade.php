@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Import Students</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#">Students</a></li>
                <li class="breadcrumb-item active">Import</li>
            </ol>

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">Total Students</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-file-import me-1"></i>
                    Bulk Student Import
                </div>
                <div class="card-body">
                    <p class="text-muted">Please select a valid Excel file (.xlsx or .xls) to import student data into the system.</p>

                    <form action="{{ url('/admin/import-students') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Upload Excel File</label>
                                <div class="input-group">
                                    <input type="file"
                                        name="excel"
                                        class="form-control"
                                        id="inputGroupFile02"
                                        required
                                        accept=".xlsx, .xls">
                                    <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                </div>
                                <div class="form-text">Only .xlsx and .xls files are supported.</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload"></i> Import Students
                            </button>
                            <a href="{{ url('/admin/students') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </main>
    @include('includes.footer')