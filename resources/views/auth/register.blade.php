<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Barbershop App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #4e73df, #224abe);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: none;
            text-align: center;
            padding: 2rem 1rem 1rem;
        }

        .card-header h3 {
            font-weight: 600;
            color: #4e73df;
        }

        .form-control {
            border-radius: 0.5rem;
        }

        .btn-primary {
            background-color: #4e73df;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
        }

        .login-icon {
            font-size: 3rem;
            color: #4e73df;
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
        }

        .alert {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="card p-4">
    <div class="card-header">
        <h3 class="mt-2">Daftar Akun</h3>
    </div>
    <div class="card-body">

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input name="name" type="text" class="form-control" id="name" placeholder="Nama lengkap kamu">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input name="email" type="email" class="form-control" id="email" placeholder="Alamat email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input name="password" type="password" class="form-control" id="password" placeholder="Buat password">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Daftar</button>
            </div>
        </form>

        <div class="login-link">
            <a href="/login" class="text-primary">Sudah punya akun? Masuk di sini</a>
        </div>
    </div>
</div>

</body>
</html>
