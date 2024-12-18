<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            background-color: #ffffff;
            margin: 0 auto;
            padding: 30px;
            max-width: 650px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid #e1e1e1;
        }

        .header {
            text-align: center;
            padding-bottom: 30px;
        }

        .header img {
            width: 120px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #333;
            font-size: 22px;
            margin: 0;
            font-weight: bold;
        }

        .content p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .pin-code {
            text-align: center;
            font-size: 28px;
            color: #333;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 13px;
            margin-top: 30px;
        }

        .footer p {
            margin: 5px 0;
        }

        .note {
            font-size: 14px;
            color: #555;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Header -->
    <div class="header">
        <img src="{{ asset('edusoft.jpg') }}" alt="Logo">
        <h1>¿Estás intentando restablecer tu contraseña?</h1>
    </div>

    <!-- Content -->
    <div class="content">
        <p>Hola, <strong>{{ $user->name }}</strong>,</p>
        <p>Recibimos una solicitud para restablecer tu contraseña. Si no realizaste esta solicitud, puedes ignorar este
            mensaje.</p>
        <p>Si fuiste tú, por favor introduce el siguiente código de verificación para continuar con el proceso de
            restablecimiento de contraseña:</p>

        <!-- PIN Code -->
        <div class="pin-code">{{ $pin }}</div>
        <div class="note">
            <p>Este código es válido por <strong>{{ $expiresIn }}</strong> minutos. Por favor, no lo compartas con
                nadie.</p>
            <p>Si no solicitaste esta verificación, simplemente ignora este mensaje.</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Si no solicitaste este cambio, simplemente ignora este correo electrónico.</p>
        <p>Gracias por utilizar nuestro servicio.</p>
        <p>Atentamente, el equipo de {{ config('app.name') }}</p>
    </div>
</div>
</body>
</html>
