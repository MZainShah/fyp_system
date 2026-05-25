@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Student Supervisor Allocation</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Run Allocation</li>
            </ol>

            <div class="card mb-4 col-xl-12">
                <div class="card-header">
                    <i class="fas fa-random me-1"></i>
                    Allocation Engine
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm mb-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Click the button below to automatically pair students with supervisors and update the Google Sheet.
                    </p>

                    <form method="POST" action="/admin/run-allocation">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="fas fa-play-circle me-1"></i> Run Allocation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')
</div>