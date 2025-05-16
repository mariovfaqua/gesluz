<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Pedido</title>
</head>
<body>
    <h1>Hola {{ $user->name }},</h1>

    <p>Gracias por tu pedido. Lo hemos recibido correctamente y estamos procesándolo.</p>

    <h3>Resumen del pedido:</h3>
    <ul>
        <li>Número de pedido: {{ $order->id }}</li>
        <li>Fecha: {{ $order->fecha->format('d/m/Y H:i') }}</li>
        <li>Total: €{{ number_format($order->precio_total, 2) }}</li>
    </ul>

    <p>Nos pondremos en contacto contigo si necesitamos más información.</p>

    <p>Gracias por confiar en nosotros.</p>
</body>
</html>