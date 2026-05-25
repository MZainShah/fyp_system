@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Edit Student</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('/admin/students') }}">Students</a></li>
                <li class="breadcrumb-item active">Edit Details</li>
            </ol>

            <div class="card mb-4 col-xl-12">
                <div class="card-header">
                    <i class="fas fa-user-edit me-1"></i>
                    Update Student Information
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.update', $student['id']) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="rowIndex" value="{{ $student['rowIndex'] }}">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control" 
                                       value="{{ $student['name'] }}" 
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Roll Number</label>
                                <input type="text" 
                                       name="roll" 
                                       class="form-control" 
                                       value="{{ $student['roll'] }}" 
                                       required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Role</label>
                                <input type="text" 
                                       name="role" 
                                       class="form-control" 
                                       value="{{ $student['role'] }}" 
                                       required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-1"></i> Update Student
                            </button>
                            <a href="{{ url('admin/students/list') }}" class="btn btn-secondary px-4">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')