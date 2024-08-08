<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Receipt | Management POS</title>
    <style>
        * {
            font-size: 14px;
            font-family: 'Times New Roman';
        }

        td, th, tr, table {
            border-top: 1px solid black;
            border-collapse: collapse;
            padding: 4px;
        }

        td.description, th.description {
            width: 30px;
            max-width: 30px;
        }

        td.quantity, th.quantity {
            width: 100px;
            max-width: 20px;
            word-break: break-all;
            text-align: center;
        }
        td.menu, th.menu {
            width: 100px;
            max-width: 90px;
            word-break: break-all;
            text-align: center;
        }

        td.price, th.price {
            width: 100px;
            max-width: 120px;
            word-break: break-all;
            text-align: center;
        }

        td.sub-total, th.sub-total {
            width: 10px;
            max-width: 120px;
        }

        td.pb01, th.pb01 {
            width: 10px;
            max-width: 20px;
            word-break: break-all;
            text-align: center;
        }

        td.total, th.total {
            width: 80px;
            max-width: 80px;
            word-break: break-all;
            text-align: center;
        }

        .centered {
            text-align: center;
            align-content: center;
            margin: 0;
            font-weight: 400;
        }

        .ticket {
            width: 100%;
            text-align: center;
        }

        img {
            width: 100px;
        }

        @media print {
            .hidden-print, .hidden-print * {
                display: none !important;
            }
        }

        @page {
            size: 70mm 220mm;
            margin: 5;
        }

        .head__text {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            padding: 0;
        }

        .line {
            width: 100%;
            border-top: 1px dashed #3f3f3f;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .invoiceNumber {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding-left: 5px;
            padding-right: 5px;
            text-align: left;
            margin-top: 3px;
            margin-bottom: 3px;
        }

        .invoiceNumber > div {
            font-weight: 400;
        }
    </style>
</head>
<body>

    <div class="ticket">
        {{-- <img src="{{ asset('assets/images/logo/apotek.jpg') }}" alt="Logo"> --}}
        <h2 class="head__text">A2 Coffee & Eatry
            <span class="centered">
                <br>Tanggerang
                <br>
            </span>
        </h2>
        <div class="line"></div>

        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Kasir:
                <span style="float: right; margin-right: 15px;">{{ Auth::user()->username ?? '-' }} </span>
            </div>
        </div>

        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Waktu Buka:
                <span style="float: right; margin-right: 15px;">{{ $stores->open_store ?? '-' }} </span>
            </div>
        </div>

        @foreach ($groupedData as $paymentMethod => $data)
        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                {{ $paymentMethod }}:
                <span style="float: right; margin-right: 15px;">Rp.{{ number_format($data['total'],0) }}</span>
            </div>
        </div>
        @endforeach
        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Total Keseluruhan:
                <span style="float: right; margin-right: 15px;">Rp.{{ number_format($totalAll, 0) }}</span>
            </div>
        </div>

        <div class="line"></div>

        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Pajak:
                <span style="float: right; margin-right: 15px;">Rp.{{ number_format($pb01, 0) }}</span>
            </div>
        </div>
        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Biaya Layanan:
                <span style="float: right; margin-right: 15px;">Rp.{{ number_format($service, 0) }}</span>
            </div>
        </div>
        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Transaksi:
                <span style="float: right; margin-right: 15px;">{{ number_format($totalTransaction, 0) }}</span>
            </div>
        </div>
        
        <div class="line"></div>

        @foreach ($productQuantities as $productName => $qty)
            <div class="invoiceNumber">
                <div style="margin-left: 2px;">
                    {{ $productName }}:
                    <span style="float: right; margin-right: 15px;">{{ number_format($qty, 0) }}</span>
                </div>
            </div>
        @endforeach

        <div style="margin-bottom: 5px"></div>

        <div class="line"></div>
        <p class="centered" style="margin-top: 10px; font-weight: 600;">Laporan Tutup Kasir!</p>
        <div class="line"></div>
    </div>
</body>
</html>
