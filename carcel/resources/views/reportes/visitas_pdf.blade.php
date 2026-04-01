<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h1 {
            text-align: center;
        }

        p {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #f59e0b;
            color: white;
            padding: 6px;
            border: 1px solid #ccc;
        }

        td {
            padding: 6px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>Carcel El Redentor - Reporte de Visitas</h1>
    <p>De: {{$fecha_inicio}} hasta: {{$fecha_fin}}</p>
    <p>Total: {{$visitas->count()}}</p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Prisionero</th>
                <th>Visitante</th>
                <th>Relacion</th>
                <th>Guardia</th>
                <th>Inicio</th>
                <th>Fin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitas as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->prisoner->full_name ?? '-'}}</td>
                    <td>{{$v->visitor->full_name ?? '-'}}</td>
                    <td>{{$v->relationship}}</td>
                    <td>{{$v->officer->name ?? '-'}}</td>
                    <td>{{\Carbon\Carbon::parse($v->start_time)->format('d/m/Y H:i')}}</td>
                    <td>{{\Carbon\Carbon::parse($v->end_time)->format('d/m/Y H:i')}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>