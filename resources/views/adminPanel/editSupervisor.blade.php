@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit Supervisor</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#">Supervisor</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
            
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-user-edit me-1"></i>
                    Update Supervisor Details
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.supervisor.update', $supervisor['id']) }}">
                        @csrf
                        
                        <input type="hidden" name="row" value="{{ $supervisor['row'] }}">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ $supervisor['name'] }}"
                                    placeholder="Enter supervisor name"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ $supervisor['email'] }}"
                                    placeholder="Enter email address"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <input type="text"
                                    name="role"
                                    value="{{ $supervisor['role'] }}"
                                    class="form-control"
                                    readonly>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.supervisors') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@include('includes.footer')