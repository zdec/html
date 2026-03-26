<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background:#f6f8fb;font-family:Arial,sans-serif;color:#212529;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f8fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="680" cellspacing="0" cellpadding="0" style="background:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td style="background:#212529;padding:18px 24px;">
                            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="IT Secur" style="height:34px;width:auto;display:block;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <h2 style="margin:0 0 10px;font-size:22px;color:#212529;">{{ $title }}</h2>
                            <p style="margin:0 0 16px;line-height:1.5;">{{ $message }}</p>
                            <p style="margin:0 0 4px;line-height:1.5;"><strong>Orden:</strong> #{{ $order->id }}</p>
                            <p style="margin:0 0 4px;line-height:1.5;"><strong>Estado actual:</strong> {{ ucfirst($status) }}</p>
                            <p style="margin:0 0 16px;line-height:1.5;"><strong>Total:</strong> ${{ number_format($order->total, 0, ',', ',') }}</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="6" style="border-collapse:collapse;border:1px solid #e9ecef;">
                                <thead>
                                <tr style="background:#f8f9fa;">
                                    <th align="left" style="border-bottom:1px solid #e9ecef;">Producto</th>
                                    <th align="center" style="border-bottom:1px solid #e9ecef;">Cantidad</th>
                                    <th align="right" style="border-bottom:1px solid #e9ecef;">Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td style="border-bottom:1px solid #f1f3f5;">{{ $item->product->name }}</td>
                                        <td align="center" style="border-bottom:1px solid #f1f3f5;">{{ $item->qty }}</td>
                                        <td align="right" style="border-bottom:1px solid #f1f3f5;">${{ number_format($item->subtotal, 0, ',', ',') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <p style="margin:18px 0 0;">
                                <a href="{{ route('login') }}" style="display:inline-block;padding:10px 16px;background:#266bf9;color:#ffffff;text-decoration:none;border-radius:4px;">Ver mi cuenta</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
