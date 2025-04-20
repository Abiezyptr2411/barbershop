<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Midtrans Style</title>
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
    .summary-card {
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .summary-value {
      font-size: 24px;
      font-weight: bold;
    }
    .summary-label {
      font-size: 14px;
      color: gray;
    }
    .alert-orange {
      background-color: #ff6f00;
      color: white;
      padding: 10px 20px;
      border-radius: 4px;
      margin-top: 20px;
    }
    .alert-blue {
      background-color: #e3f2fd;
      padding: 10px 20px;
      border-radius: 4px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h5 class="text-center mb-4">Berkah Barbershop</h5>
  <a href="/admin/dashboard" class="nav-link active"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="/admin/transactions" class="nav-link"><i class="bi bi-receipt"></i> Transactions</a>
  <hr class="border-light mx-3">
  <a href="/admin/logout" class="nav-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <!-- Top Bar -->
  <div class="topbar">
    <div><strong>Dashboard</strong></div>
    <div class="text-muted">admin@barbershop.com</div>
  </div>

  <!-- Alerts -->
  <div class="alert-orange mt-3">
    <i class="bi bi-exclamation-triangle-fill"></i> One more step! Complete your registration <a href="#" class="text-white fw-bold">here</a>
  </div>
  <div class="alert-blue mt-2">
    <i class="bi bi-bell-fill"></i> Participate in our research and enjoy a special incentive
  </div>

  <!-- Summary Cards -->
  <div class="row mt-4 g-3">
    <div class="col-md-6">
      <div class="summary-card">
        <div class="summary-value">Rp. {{ number_format($totalVolume, 0, ',', '.') }}</div>
        <div class="summary-label">Total Volume <small>(Month to Date)</small></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="summary-card">
        <div class="summary-value">{{ $totalTransactions }}</div>
        <div class="summary-label">Total Transactions <small>(Month to Date)</small></div>
      </div>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="card mt-4">
    <div class="card-body">
      <div id="transactionChart" style="height: 400px;"></div>
      <!-- <div id="chartData"
           data-labels='@json($chartData->pluck("date"))'
           data-values='@json($chartData->pluck("total"))'>
      </div> -->
      <div id="chartData"
     data-labels='@json($chartData->pluck("bank_channel"))'
     data-values='@json($chartData->pluck("total"))'>
</div>

    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
  $(document).ready(function () {
    const labels = $('#chartData').data('labels');
    const values = $('#chartData').data('values');

    const numericValues = values.map(value => parseFloat(value)); // <- penting!

    console.log('Labels:', labels);
    console.log('Values:', numericValues);

    Highcharts.chart('transactionChart', {
      chart: {
        type: 'line'
      },
      title: {
        text: 'Transaction Volume (Based On Bank Channel)'
      },
      xAxis: {
        categories: labels,
        title: {
          text: 'Bank Channel'
        }
      },
      yAxis: {
        title: {
          text: 'Total Transaksi (Rp)'
        },
        labels: {
          formatter: function () {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(this.value);
          }
        }
      },
      tooltip: {
        formatter: function () {
          return `<b>${this.x}</b>: Rp ${new Intl.NumberFormat('id-ID').format(this.y)}`;
        }
      },
      series: [{
        name: 'Total',
        data: numericValues,
        color: '#1976d2'
      }],
      credits: {
        enabled: false
      }
    });
  });
</script>


</body>
</html>
