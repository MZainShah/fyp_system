<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Student Login - FYP Portal</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            body { background-color: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
            .card { border: none; border-radius: 1rem; box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
            .btn-google { background-color: #ea4335; color: white; font-weight: 500; border-radius: 0.5rem; transition: 0.3s; }
            .btn-google:hover { background-color: #d33828; color: white; transform: translateY(-2px); }
            .iub-logo { width: 120px; margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class="card p-4 text-center">
            <div class="card-body">
                <img src="https://www.iub.edu.pk/images/logo.png" alt="IUB Logo" class="iub-logo">
                
                <h3 class="fw-bold mb-2">Student Portal</h3>
                <p class="text-muted mb-4 small">FYP Management System</p>

                @if(session('error'))
                    <div class="alert alert-danger py-2 small border-0 mb-4">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ route('student.login.google') }}" class="btn btn-google py-2 border-0">
                        <i class="fab fa-google me-2"></i> Continue with IUB Email
                    </a>
                </div>

                <div class="mt-4 pt-2 border-top">
                    <p class="text-muted extra-small" style="font-size: 0.75rem;">
                        Please use your official <b>rollnumber@iub.edu.pk</b> to login.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>