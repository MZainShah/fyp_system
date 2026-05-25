<!-- include header here -->
@include('includes.header')
<!-- include sidebar here -->
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    DataTable Example
                </div>
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @forelse($supervisors as $index => $supervisor)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $supervisor['name'] }}</td>
                                <td>{{ $supervisor['email'] }}</td>
                                <td>{{ $supervisor['role'] }}</td>
                                <td>{{ $supervisor['created_at'] }}</td>
                                <td>
                                    <a href="{{ route('admin.supervisor.edit', $supervisor['id']) }}"
                                        class="btn btn-sm"
                                        style="font-size: 11px; background-color: yellow; color: black;">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.supervisor.delete', $supervisor['id']) }}"
                                        method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm"
                                            style="font-size: 11px; background-color: red; color: white;">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No supervisors found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')