<!-- <div class="container mt-4">

    <h3>Supervisor Allocations</h3>

    <table class="table table-bordered mt-3">

        <thead>
            <tr>
                <th>Supervisor Name</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

            @foreach($supervisors as $supervisor)

            <tr>

                <td>{{ $supervisor['name'] }}</td>

                <td>
                    <a href="{{ url('/allocation/students/'.$supervisor['id']) }}"
                        class="btn btn-primary">
                        View Allocated Students
                    </a>
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div> -->


@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Supervisor Allocations</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Supervisor List</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chalkboard-teacher me-1"></i>
                    Allocated Students by Supervisor
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Supervisor Name</th>
                                <th class="text-center" style="width: 250px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supervisors as $supervisor)
                            <tr>
                                <td class="align-middle">{{ $supervisor['name'] }}</td>
                                <td class="text-center">
                                    <a href="{{ route('allocated.students.list', $supervisor['id']) }}" 
                                       class="btn btn-primary btn-sm px-3 shadow-sm"
                                       style="font-size: 12px; border-radius: 4px;">
                                        <i class="fas fa-eye me-1"></i> View Allocated Students
                                    </a>
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
</div>