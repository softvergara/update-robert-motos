<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .ticket {
            width: 288px;
            /* 76mm a 96dpi ≈ 288px */
            margin: 0 auto;
            padding: 0;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 8px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
        }

        .header p {
            font-size: 12px;
            margin: 2px 0;
        }

        .productos table {
            width: 100%;
            border-collapse: collapse;
        }

        .productos th,
        .productos td {
            text-align: left;
            padding: 3px 0;
            font-size: 11px;
        }

        .productos th {
            border-bottom: 1px dashed #000;
        }

        .totales {
            margin-top: 12px;
            text-align: right;
            font-size: 12px;
        }

        .totales p {
            margin: 3px 0;
        }

        .precio {
            text-align: right !important;
            padding: 3px 0;
            font-size: 11px;
        }

        .rules {
            text-align: left;
            padding: 2px 0;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <!-- Encabezado -->
        <div class="header">
            <h1>{{ $empresa->razon_social }}</h1>
            <p>NIT: {{ $empresa->num_id }}</p>
            <p>Dirección: {{ $empresa->direccion }}</p>
            <p>Teléfono: {{ $empresa->telefono }}</p>
            <p>Email: {{ $empresa->correo }}</p>
        </div>

        <!-- Ticket -->
        <p>
            <strong>Factura #:</strong> {{ str_pad($venta->idVenta, 4, '0', STR_PAD_LEFT); }}<br>
            <strong>Fecha:</strong> {{ $venta->fecha_v }}
        </p>

        <!-- Cliente -->
        <p>
            <strong>Cliente:</strong> {{ $venta->cliente->nombres_cl }}<br>
            <strong>Documento:</strong> {{ $venta->cliente->dni_cl }}
        </p>

        <!-- Productos -->
        <div class="productos">
            <table>
                <thead>
                    <tr>
                        <th>Producto(s)</th>
                        <th>Cant.</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->descripcion_pd }}</td>
                        <td>{{ $detalle->cantidad_v }}</td>
                        <td class="precio">${{ number_format($detalle->valor_v, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="totales">
            <p>Subtotal: ${{ number_format($subtotal, 0, ',', '.') }}</p>
            <p>IVA (19%): ${{ number_format($iva, 0, ',', '.') }}</p>
            <p><strong>Total:</strong> ${{ number_format($total, 0, ',', '.') }}</p>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>Gracias por su compra</p>
            <p class="rules">Persona natural y asimilidas, régimen ordinario de tributación y responsable de impuesto a las ventas</p>
            <p class="rules">*NO ACEPTAMOS DEVOLUCIONES DE ARTICULOS EN MAL ESTADO O MODIFICADOS.</p>
            <p class="rules">*PRODUCTOS ELECTRICOS NO TIENEN GARANTIAS.</p>
        </div>
    </div>
</body>

</html>