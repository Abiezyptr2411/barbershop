<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Berkah Barbershop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fc;
        }

        .navbar {
            background: #4e73df;
        }

        .navbar-brand {
            font-weight: 600;
            color: #fff;
        }

        .hover-shadow:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px);
            transition: all 0.3s ease-in-out;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 1rem;
        }

        .logout-btn {
            color: white;
            text-decoration: none;
        }

        .logout-btn:hover {
            color: #ddd;
        }

        .card-stats {
            border: none;
            border-radius: 1rem;
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: white;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .card-stats i {
            font-size: 2rem;
        }

        .promo-card {
            background: url('https://images.unsplash.com/photo-1599566147214-ce487862ea4f') no-repeat center center;
            background-size: cover;
            border-radius: 1rem;
            color: white;
            padding: 2rem;
            position: relative;
        }

        .promo-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            border-radius: 1rem;
        }

        .promo-card-content {
            position: relative;
            z-index: 1;
        }

        .testimonial {
            font-style: italic;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Berkah Barbershop</a>
        <div class="ms-auto">
            <a href="/logout" class="logout-btn"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Selamat datang, {{ session('user')->name }}! 👋</h2>
        <p class="text-muted">Kelola layanan barbershop kamu dengan mudah dan cepat.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card-stats d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">{{ $totalTiket ?? 0 }}</h5>
                    <small>Tiket Pemesanan</small>
                </div>
                <i class="bi bi-ticket-perforated"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stats d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">{{ $jadwalHariIni ?? 0 }}</h5>
                    <small>Jadwal Hari Ini</small>
                </div>
                <i class="bi bi-calendar-event"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stats d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0">{{ $totalPelanggan ?? 0 }}</h5>
                    <small>Total Pelanggan</small>
                </div>
                <i class="bi bi-people"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stats d-flex align-items-center justify-content-between bg-danger">
                <div>
                    <h5 class="mb-0">{{ $sisaAntrian ?? 0 }}</h5>
                    <small>Sisa Antrian Hari Ini</small>
                </div>
                <i class="bi bi-person-lines-fill"></i>
            </div>
        </div>
    </div>

    <h4 class="section-title">🧾 Menu</h4>
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <a href="/pemesanans/create" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow rounded-4 p-3 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                            <i class="bi bi-scissors fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-dark">Pesan Cukur</h6>
                            <small class="text-muted">Buat jadwal cukur</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-3">
            <a href="/pemesanans" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow rounded-4 p-3 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                            <i class="bi bi-ticket fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-dark">Tiket Saya</h6>
                            <small class="text-muted">Lihat daftar pemesanan</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <h4 class="section-title">🎉 Promo Spesial</h4>
    <div class="promo-card mb-4">
        <div class="promo-card-content">
            <h3 class="fw-bold">Diskon 20% untuk Pelanggan Baru!</h3>
            <p class="mb-0">Ayo cukur gaya baru, tampil makin keren.</p>
        </div>
    </div>

    <h4 class="section-title">💬 Testimoni Pelanggan</h4>
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <p class="testimonial">“Pelayanan ramah dan hasil cukur rapi banget! Langganan terus deh.”</p>
                <small class="text-muted">– Raka P.</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <p class="testimonial">“Tempatnya nyaman, stylish, dan potongannya kekinian.”</p>
                <small class="text-muted">– Budi S.</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <p class="testimonial">“Suka banget sama konsep online booking-nya. Praktis!”</p>
                <small class="text-muted">– Indra M.</small>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#d4edda',
        color: '#155724',
    });
</script>
@endif

</body>
</html>
