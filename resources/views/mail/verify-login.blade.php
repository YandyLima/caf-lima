<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Estilos generales */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.4;
            color: #333;
        }
        /* Estilos específicos para dispositivos móviles */
        @media (max-width: 600px) {
            /* Cambiar estilos para dispositivos móviles aquí */
            table,
            tr,
            td {
                display: block;
                width: 100%;
            }
            /* Esconder elementos que no deben ser mostrados en dispositivos móviles */
            .hide-on-mobile {
                display: none !important;
            }
            /* Estilos específicos para enlaces */
            a {
                display: block;
                text-align: center;
                margin: 10px auto;
                padding: 10px 20px;
                background-color: #333;
                color: #fff;
                text-decoration: none;
            }
        }
    </style>
</head>
<body>
<table style="margin: 0 auto; align-content: center; border: 0; width: 600px">
    <tr>
        <td>
            <h1>Código de inicio de sesión</h1>
            <p>Ingresa el siguiente código para iniciar sesión en la plataforma</p>
        </td>
    </tr>
    <tr>
        <td>
            <h3>
                {{ $data['code'] }}
            </h3>
        </td>
    </tr>
</table>
</body>
</html>
