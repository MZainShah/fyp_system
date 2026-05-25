<!-- <h3>Add Student</h3>

@if(session('error'))
    <div style="color:red;">{{ session('error') }}</div>
@endif

<form action="{{ route('admin.students.store') }}" method="POST">
    @csrf

    <label>Name</label>
    <input type="text" name="name" required>

    <label>Roll Number</label>
    <input type="text" name="roll" required>

    <button type="submit">Add Student</button>
</form> -->


@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Add Student</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#">Students</a></li>
                <li class="breadcrumb-item active">Add Student</li>
            </ol>

            <div class="card mb-4 col-xl-12">
                <div class="card-header">
                    <i class="fas fa-plus me-1"></i>
                    Add Student
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control" 
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Roll Number</label>
                                <input type="text" 
                                       name="roll" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                Add Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')