<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Transactions - Midtrans Style</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      margin: 0;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      background: linear-gradient(to bottom, #003c8f, #1976d2);
      color: #fff;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px 0;
    }
    .sidebar .nav-link {
      color: #fff;
      padding: 10px 25px;
      display: flex;
      align-items: center;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
      background-color: rgba(255, 255, 255, 0.1);
    }
    .sidebar .nav-link i {
      margin-right: 10px;
    }
    .main-content {
      margin-left: 250px;
      padding: 20px;
    }
    .topbar {
      background-color: #fff;
      padding: 15px 25px;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .table-card {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .badge {
      padding: 6px 12px;
      font-size: 0.85rem;
      border-radius: 20px;
    }

    .badge-status {
      padding: 4px 10px;
      font-size: 0.85rem;
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-weight: 500;
    }

    .badge-status .dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
    }

    .badge-success {
      background-color: #e6f4ea;
      color: #2e7d32;
    }

    .badge-success .dot {
      background-color: #2e7d32;
    }

    .badge-warning {
      background-color: #fff8e1;
      color: #f9a825;
    }

    .badge-warning .dot {
      background-color: #f9a825;
    }

    .badge-danger {
      background-color: #fdecea;
      color: #d32f2f;
    }

    .badge-danger .dot {
      background-color: #d32f2f;
    }

    .badge-secondary {
      background-color: #e0e0e0;
      color: #424242;
    }

    .badge-secondary .dot {
      background-color: #424242;
    }

  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h5 class="text-center mb-4">Berkah Barbershop</h5>
    <a href="/admin/dashboard" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="/admin/transactions" class="nav-link active"><i class="bi bi-receipt"></i> Transactions</a>
    <hr class="border-light mx-3">
    <a href="/admin/logout" class="nav-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Bar -->
    <div class="topbar">
      <div><strong>Transaction List</strong></div>
      <div class="text-muted">{{ session('admin')->email ?? 'admin@barbershop.com' }}</div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ url('/admin/transactions') }}" class="mt-4 d-flex flex-wrap gap-3 align-items-center">
      <div class="input-group" style="max-width: 300px;">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search here">
      </div>
      <select class="form-select" name="field" style="max-width: 150px;">
        <option value="order_id" {{ request('field') == 'order_id' ? 'selected' : '' }}>Order ID</option>
        <option value="status" {{ request('field') == 'status' ? 'selected' : '' }}>Status</option>
        <option value="email" {{ request('field') == 'email' ? 'selected' : '' }}>Customer</option>
      </select>
      <input type="date" class="form-control" name="date_start" value="{{ request('date_start') }}" style="max-width: 180px;">
      <input type="date" class="form-control" name="date_end" value="{{ request('date_end') }}" style="max-width: 180px;">
      <button class="btn btn-outline-secondary">Apply</button>
    </form>

    <!-- Table Card -->
    <div class="table-card mt-4">
      <h6 class="mb-3">Showing {{ count($transactions) }} results</h6>
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>SCHEDULE DATE</th>
            <th>TRANSACTION ID</th>
            <th>PAYMENT TYPE</th>
            <th>CHANNEL</th>
            <th>STATUS</th>
            <th>AMOUNT</th>
            <th>CUSTOMER</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($transactions as $tx)
          <tr>
            <td>{{ \Carbon\Carbon::parse($tx->jadwal)->format('d M Y, H:i') }}</td>
            <td>{{ $tx->kode_invoice }}</td>
            <td>{{ $tx->payment_type }}</td>
            <td>{{ $tx->bank_channel }}</td>
            <td>
            @php
              $status = strtolower($tx->status);
              switch ($status) {
                case 'settlement':
                  $badgeClass = 'success';
                  break;
                case 'pending':
                  $badgeClass = 'danger';
                  break;
                case 'expired':
                  $badgeClass = 'warning';
                  break;
                default:
                  $badgeClass = 'secondary';
              }
            @endphp
            <span class="badge-status badge-{{ $badgeClass }}">
              <span class="dot"></span> {{ ucfirst($status) }}
            </span>
            </td>

            <td>Rp{{ number_format($tx->harga, 0, ',', '.') }}</td>
            <td><b>{{ $tx->name }}</b><br><small>{{ $tx->email }}</small></td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted">No results found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
