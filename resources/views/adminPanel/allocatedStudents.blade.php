<!-- 
<div class="container mt-4">

<h3>Allocated Students - {{ $supervisorName }}</h3>

<table class="table table-bordered mt-3">

<thead>
<tr>
<th>Student Name</th>
<th>Roll Number</th>
</tr>
</thead>

<tbody>

@forelse($students as $student)

<tr>
<td>{{ $student['name'] }}</td>
<td>{{ $student['roll'] }}</td>
</tr>

@empty

<tr>
<td colspan="2">No students allocated.</td>
</tr>

@endforelse

</tbody>

</table>

</div> -->


@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Allocated Students</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('/admin/allocations') }}">Supervisor Allocations</a></li>
                <li class="breadcrumb-item active">{{ $supervisorName }}</li>
            </ol>

            <div class="card mb-4 col-xl-10">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-users me-1"></i>
                        Students Assigned to: <strong>{{ $supervisorName }}</strong>
                    </div>
                    <a href="{{ url('/admin/allocated-supervisors') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student Name</th>
                                <th>Roll Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr>
                                <td class="align-middle">{{ $student['name'] }}</td>
                                <td class="align-middle">
                                    <span class="badge bg-info text-dark">{{ $student['roll'] }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-1"></i> No students allocated to this supervisor.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')
</div>