<!DOCTYPE html>
<html>
<head>
    <title>Invoice Cukur</title>
    <style>
        /* Atur ukuran kertas saat print (58mm = 2.28 inch) */
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            width: 58mm;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 5px;
            margin: 0 auto;
            color: #000;
        }

        h2 {
            text-align: center;
            font-size: 14px;
            margin-bottom: 5px;
        }

        p {
            margin: 3px 0;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        @media print {
            button {
                display: none;
            }
            body {
                margin: 0;
                width: 58mm;
            }
        }
    </style>
</head>
<body>
    <h2>JAYA BARBERSHOP</h2>
    <div class="text-center">Jl. Cukur No.1, Jakarta</div>
    <div class="line"></div>

    <p>Kode Invoice : <span class="bold">{{ $p->kode_invoice }}</span></p>
    <p>Jadwal       : {{ date('d/m/Y H:i', strtotime($p->jadwal)) }}</p>
    <p>Harga        : Rp {{ number_format($p->harga) }}</p>

    <div class="line"></div>
    <p class="text-center">Terima kasih telah<br>mencukur di tempat kami!</p>
    <div class="line"></div>
    <p class="text-center">~ JAYA BARBERSHOP ~</p>

    <div class="text-center" style="margin-top: 5px;">
        <button onclick="window.print()">🖨️ Cetak Struk</button>
    </div>
</body>
</html>
