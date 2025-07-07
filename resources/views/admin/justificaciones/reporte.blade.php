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

    @if(!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin']))
        <p style="text-align: center; margin-top: -20px; margin-bottom: 20px;">
            <strong>Del {{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }}
            al {{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}</strong>
        </p>
    @endif

    @if(!empty($claseNombre))
    <p style="text-align: center; margin-bottom: 10px;">
        <strong>Clase:</strong> {{ $claseNombre }}
    </p>
    @endif


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
