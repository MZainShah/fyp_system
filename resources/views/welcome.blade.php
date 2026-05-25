<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow: hidden;
        }

        .welcome-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem;
            border-radius: 30px;
            text-align: center;
            color: white;
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
            max-width: 900px;
            width: 90%;
        }

        .role-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: block;
            height: 100%;
            border: none;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            background: #f8f9ff;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }

        /* Role Specific Colors */
        .admin-icon { background: #ffe8e8; color: #ff4757; }
        .student-icon { background: #e8f0ff; color: #2e86de; }
        .supervisor-icon { background: #e8fff0; color: #2ecc71; }

        .role-title {
            color: #2d3436;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .role-desc {
            color: #636e72;
            font-size: 0.9rem;
        }

        .system-logo {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }

        .subtitle {
            font-weight: 300;
            margin-bottom: 3rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="welcome-container animate__animated animate__fadeIn">
    <div class="system-logo"><i class="fas fa-graduation-cap me-2"></i>PORTAL</div>
    <p class="subtitle">Sir, please select your portal to continue</p>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ url('/admin/login') }}" class="role-card shadow-sm">
                <div class="icon-circle admin-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h4 class="role-title">Admin</h4>
                <p class="role-desc">Manage notices, users, and system settings.</p>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ url('/student/login') }}" class="role-card shadow-sm">
                <div class="icon-circle student-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h4 class="role-title">Student</h4>
                <p class="role-desc">Check notice board and project updates.</p>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ url('/supervisor/login') }}" class="role-card shadow-sm">
                <div class="icon-circle supervisor-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h4 class="role-title">Supervisor</h4>
                <p class="role-desc">Monitor student progress and feedback.</p>
            </a>
        </div>
    </div>
    
    <div class="mt-5 small opacity-75">
        &copy; {{ date('Y') }} Management System. All rights reserved.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>