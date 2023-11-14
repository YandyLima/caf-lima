<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 30px;
        }
        .main-container h2 {
            text-transform: uppercase;
            text-align: center;
        }
        .header-container {
            margin-top: 50px;
            display: block;
        }
        .header-container .information {
            display: inline-block;
            text-align: left;
            width: 60%;
        }
        .header-container .logo {
            display: inline-block;
            text-align: right;
        }
        .details-container {
            margin-top: 30px;
            display: block;
        }
        .details-container .details-client {
            display: inline-block;
            text-align: left;
            width: 45%;
        }
        .details-container .details-sale {
            margin-left: 4%;
            width: 50%;
            display: inline-block;
            text-align: right;
        }
        .details-container p {
            text-align: left;
            font-weight: bold;
        }
        .details-container span {
            font-weight: normal;
        }
        .sales-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sales-table thead {
            background-color: #333;
            color: white;
            text-transform: uppercase;
            padding: 10px;
        }
        .sales-table th, td {
            padding: 10px;
        }
        .sales-table > thead > tr > th:nth-child(n+3) {
            text-align: right;
        }
        .sales-table > tbody > tr > td:nth-child(n+3) {
            text-align: right;
        }
        .sales-table > tfoot > tr > th, .factura > tfoot > tr > th:nth-child(n+3) {
            font-size: 20px;
            text-align: right;
        }
        .footer-container {
            text-align: center;
            margin-top: 5em;
        }
    </style>
</head>
<body>
<div class="main-container">
    <h2>Factura</h2>
    <div class="header-container">
        <div class="information">
            <h1>Café Lima</h1>
            <p>Nit: {{ $sale->customer->nit ?? 'CF' }}</p>
            <p>{{ $address }}</p>
            <p>Teléfono: {{ $sale->customer->phone }}</p>
        </div>
        <div class="logo">
            <img src="{{ public_path('assets/img/lgo.cafe.png') }}" height="200px"/>
        </div>
    </div>
    <hr />
    <div class="details-container">
        <div class="details-client">
            <p>Nombre: <span>{{ $sale->customer->name }}</span></p>
            <p>NIT: <span>{{ $sale->customer->nit ?? 'CF' }}</span></p>
            <p>Dirección: <span>{{ $sale->customer->address }}</span></p>
        </div>
        <div class="details-sale">
            <p>N° de Factura: <span>{{ $sale->id }}</span></p>
            <p>N° de Autorización: <span>{{ $sale->authorization_number }}</span></p>
            <p>Serie: <span>D</span></p>
            <p>Fecha de Emisión: <span>{{ $sale->updated_at }}</span></p>
        </div>
    </div>
    <div class="sales-container">
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            @php $total = 0; @endphp
            @foreach($sale->sale_details as $detail)
                @php
                    $subtotal = $detail->product->price * $detail->amount;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $detail->amount }}</td>
                    <td>{{ $detail->product->name }}</td>
                    <td>Q{{ $detail->product->price }}</td>
                    <td>Q{{ $subtotal }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th>Total Factura</th>
                <th>Q{{ $total }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="footer-container">
        <h4>Café Lima</h4>
    </div>
</div>
</body>
</html>

