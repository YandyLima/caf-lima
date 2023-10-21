<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Electrónica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #FFFFFF;
            background-color: #f4f4f4;
            max-width: 800px;
            margin: auto;
        }
        header {
            padding: 10px;
            background-color: #522812;
            color: #fff;
            text-align: center;
        }
        header img {
            width: 100px;
        }
        .main-content {
            padding: 50px 0;
            background-size: cover;
            background-image: url("{{ asset('assets/img/background/coffee-mail.jpg') }}");
            width: 100%;
        }
        .card {
            padding: 15px;
            margin: 50px auto;
            max-width: 450px;
            min-height: 300px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            color: #444;
            border-radius: 10px;
            background-color: #f4f4f4;
        }
        .action {
            margin-top: 70px;
            text-align: center;
        }
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
        }
        a, a:link, a:visited, a:hover, a:active {
            color: white !important;
            text-decoration: none;
        }
        .footer {
            margin-top: 70px;
        }
        @media (max-width: 570px) {
            .card {
                max-width: 80%;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Factura Electrónica</h1>
    <img src="{{ asset('assets/img/logo.png') }}"  alt="">
</header>
<div class="main-content">
    <div class="card">
        <div class="content">
            <p>Estimado(a) {{ $sale->customer->name }},</p>
            <p>Adjunto encontrará su factura electrónica correspondiente a la compra realizada el día {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y')  }}.</p>
        </div>
        <div class="action">
            <a class="button" href="{{ $sale->url }}">Descargar Factura</a>
        </div>
        <div class="footer">
            <p>Saludos cordiales,</p>
            <p><b>El equipo de {{ config('app.name') }}</b></p>
        </div>
    </div>
</div>
</body>
</html>
