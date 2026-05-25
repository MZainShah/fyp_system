<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Login - Supervisor Portal</title>
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            body { background-color: #f8f9fa; }
            .btn-google {
                background-color: #db4437;
                color: white;
                transition: 0.3s;
                border-radius: 5px;
                padding: 12px;
                font-weight: 500;
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
            }
            .btn-google:hover {
                background-color: #c53929;
                color: white;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            }
        </style>
    </head>
    <body>
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Supervisor Login</h3></div>
                                    <div class="card-body">
                                        
                                        @if(session('error'))
                                            <div class="alert alert-danger border-0 mb-4 shadow-sm">
                                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                            </div>
                                        @endif

                                        <p class="text-center text-muted small mb-4">Please use your registered University Google account to access the dashboard.</p>
                                        
                                        <a href="{{ url('auth/google') }}" class="btn-google">
                                            <i class="fab fa-google me-3"></i> Continue with Google
                                        </a>

                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small text-muted">Authorized Personnel Only</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>