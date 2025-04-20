<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Berkah Barbershop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }
        .brand-header {
            font-weight: bold;
            font-size: 24px;
            color: #2c3e50;
        }
        .login-wrapper {
            max-width: 420px;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
        }
        .login-btn {
            background-color: #0d6efd;
            border: none;
        }
        .login-btn:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="login-wrapper shadow">
        <div class="text-center mb-4">
            <div class="brand-header">Berkah Barbershop</div>
            <small class="text-muted">Login Admin Panel</small>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email atau Nama</label>
                <input type="text" name="email" class="form-control" placeholder="Masukkan email atau nama" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 login-btn">Masuk</button>
        </form>
    </div>

</body>
</html>
