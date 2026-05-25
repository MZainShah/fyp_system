@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Imported Students List</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#">Students</a></li>
                <li class="breadcrumb-item active">Students List</li>
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
                    <i class="fas fa-table me-1"></i>
                    Imported Students list
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Roll Number</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Student Name</th>
                                <th>Roll Number</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student['name'] }}</td>
                                <td>{{ $student['roll'] }}</td>
                                <td>{{ $student['email'] }}</td>
                                <td>{{ $student['role'] }}</td>
                                <td>
                                    <a href="{{ route('admin.students.edit', $student['id']) }}"
                                        class="btn btn-sm"
                                        style="font-size: 11px; background-color: yellow; color: black; border: 1px solid #d4d400;">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.students.delete', $student['id']) }}"
                                        method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-sm"
                                            style="font-size: 11px; background-color: red; color: white; border: none;">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')