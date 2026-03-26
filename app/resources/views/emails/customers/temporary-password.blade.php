<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso cliente</title>
</head>
<body style="margin:0;padding:0;background:#f6f8fb;font-family:Arial,sans-serif;color:#212529;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f8fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#212529;padding:18px 24px;">
                            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="IT Secur" style="height:34px;width:auto;display:block;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <h2 style="margin:0 0 12px;font-size:20px;color:#212529;">Hola{{ $customer->name ? ', '.$customer->name : '' }}</h2>
                            <p style="margin:0 0 12px;line-height:1.5;">
                                Ya tienes acceso al portal de clientes. Te compartimos una contraseña temporal para iniciar sesión.
                            </p>
                            <p style="margin:0 0 6px;line-height:1.5;"><strong>Usuario:</strong> {{ strtolower($customer->email) }}</p>
                            <p style="margin:0 0 16px;line-height:1.5;"><strong>Contraseña temporal:</strong> {{ $temporaryPassword }}</p>
                            <p style="margin:0 0 18px;line-height:1.5;">
                                Al ingresar por primera vez se te pedirá cambiar esta contraseña de manera obligatoria.
                            </p>
                            <a href="{{ route('login') }}" style="display:inline-block;padding:10px 16px;background:#266bf9;color:#ffffff;text-decoration:none;border-radius:4px;">Iniciar sesión</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
