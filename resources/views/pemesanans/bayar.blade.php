<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Cukur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h4 class="mb-3">Pembayaran Pemesanan Cukur</h4>
                <p>Total: <strong>Rp {{ number_format($pemesanan->harga) }}</strong></p>

                <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    window.location.href = '/pemesanans'; // redirect setelah sukses
                },
                onPending: function(result){
                    alert("Pembayaran menunggu, silakan selesaikan pembayaran.");
                    console.log(result);
                },
                onError: function(result){
                    alert("Pembayaran gagal.");
                    console.log(result);
                }
            });
        });
    </script>

<script type="text/javascript">
    snap.pay("{{ $snapToken }}", {
        onSuccess: function(result) {
            window.location.href = "/pemesanans/";
        },
        onPending: function(result) {
            alert("Menunggu pembayaran!");
        },
        onError: function(result) {
            alert("Pembayaran gagal.");
        }
    });
</script>


</body>
</html>
