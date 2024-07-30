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
                No. Invoice:
                <span style="float: right; margin-right: 15px;">#{{ $orders->no_invoice }}</span>
            </div>
        </div>


        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Metode Pembayaran:
                <span style="float: right; margin-right: 15px;"> {{ $orders->payment_method }}</span>
            </div>
        </div>

        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Datetime:
                <span style="float: right; margin-right: 15px;">{{ $orders->created_at }}</span>
            </div>
        </div>
        <div class="line"></div>

        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Pelanggan:
                <span style="float: right; margin-right: 15px;">{{ $orders->customer_name ?? '-' }} </span>
            </div>
        </div>

        <div class="invoiceNumber">
            <div style="margin-left: 2px;">
                Kasir:
                <span style="float: right; margin-right: 15px;">{{ $orders->cashier_name ?? '-' }} </span>
            </div>
        </div>

        <div style="margin-bottom: 5px"></div>
        <table>
            <thead>
                <tr>
                    <th class="menu">Menu</th>
                    <th class="description">Qty</th>
                    <th class="price">Price</th>
                </tr>
            </thead>
            <tbody>

            @php

            $totalPrice = 0;
            @endphp
                @foreach ($orders->orderProducts as $orderPivot)

                    <tr>
                        <td class="menu"> {{ $orderPivot->name }} </td>
                        <td class="description" style="text-align: center">{{ $orderPivot->qty }}</td>
                        <td class="price" style="text-align: right">Rp.{{ number_format($orderPivot->selling_price * $orderPivot->qty,0) }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td class="sub-total"> &nbsp;</td>
                    <td class="pb01">&nbsp;</td>
                    <td class="total" style="text-align: right">&nbsp;</td>
                </tr>

                <tr>
                    <td class="pb01" style="text-align: left" colspan="2">Sub Total</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->subtotal,0) }}</td>
                </tr>

                {{-- @if ($orders->type_discount == 'price')
                <tr style="margin-top: 20px !important;">
                    <td class="pb01"  style="text-align: left"colspan="2">Discount</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->price_discount,0) }}</td>
                </tr>
                @else
                <tr style="margin-top: 20px !important;">
                    <td class="pb01" style="text-align: left" colspan="2">Discount ({{ $orders->percent_discount }})</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->subtotal * $orders->percent_discount /100,0) }}</td>
                </tr>
                @endif --}}
                @if ($orders->metode_pembayaran == "Cash")
                <tr>
                    <td class="pb01" style="text-align: left" colspan="2">Kembalian</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->kembalian,0) }}</td>
                </tr>
                @endif
                @if ($orders->pb01 != 0)
                <tr style="margin-top: 20px !important;">
                    <td class="pb01" style="text-align: left" colspan="2">Service</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->service,0) }}</td>
                </tr>

                <tr style="margin-top: 20px !important;">
                    <td class="pb01" style="text-align: left" colspan="2">PB01</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->pb01,0) }}</td>
                </tr>

                <tr>
                    <td class="pb01" style="text-align: left" colspan="2">Kembalian</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->kembalian,0) }}</td>
                </tr>
                <tr>
                    <td class="pb01" style="text-align: left" colspan="2">Total</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->total,0) }}</td>
                </tr>
                @else
                <tr>
                    <td class="pb01" style="text-align: left" colspan="2">Total</td>
                    <td class="total" style="text-align: right">Rp.{{ number_format($orders->total)}}</td>
                </tr>
                @endif

            </tbody>
        </table>

        <div class="line"></div>
        <p class="centered" style="margin-top: 10px; font-weight: 600;">Bill</p>
        <p class="centered" style="margin-top: 10px; font-weight: 600;">Terimakasih Atas Kunjungan Anda </p>
        <p class="centered" style="margin-top: 10px; font-weight: 600;">Silahkan Datang Kembali!</p>
        <div class="line"></div>
    </div>
</body>
</html>
