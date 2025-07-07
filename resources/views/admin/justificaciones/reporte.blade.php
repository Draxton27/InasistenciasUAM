<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Justificaciones</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h1 { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Reporte General de Justificaciones</h1>

    <p><strong>Total Justificaciones:</strong> {{ $total }}</p>
    <p><strong>Aprobadas:</strong> {{ $aprobadas }} ({{ $porcentajeAprobadas }}%)</p>
    <p><strong>Rechazadas:</strong> {{ $rechazadas }} ({{ $porcentajeRechazadas }}%)</p>

    <h3>Justificaciones por Tipo</h3>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($porTipo as $tipo => $cantidad)
                <tr>
                    <td>{{ ucfirst($tipo) }}</td>
                    <td>{{ $cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
