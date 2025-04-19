<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Cukur - Berkah Barbershop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f8f9fc, #e2e6f0);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-wrapper {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            position: relative;
        }
        .form-wrapper h2 {
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #3756d4, #1c3eaa);
        }
        .illustration {
            width: 100px;
            margin: 0 auto 1.5rem auto;
            display: block;
        }
        .back-link {
            margin-top: 2rem;
            display: inline-block;
            color: #6c757d;
            text-decoration: none;
        }
        .back-link:hover {
            color: #4e73df;
        }
    </style>
</head>
<body>

<div class="form-wrapper">
    <img src="https://cdn-icons-png.flaticon.com/512/2977/2977659.png" class="illustration" alt="Barbershop Icon">

    <h2 class="text-center"><i class="bi bi-scissors me-2"></i>Pesan Jadwal Cukur</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/pemesanans">
        @csrf
        <div class="mb-3">
            <label for="jadwal" class="form-label">Pilih Tanggal & Jam</label>
            <input type="datetime-local" class="form-control" name="jadwal" id="jadwal" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-check-circle me-1"></i> Pesan Sekarang
        </button>
    </form>

    <div class="text-center">
        <a href="/dashboard" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>
