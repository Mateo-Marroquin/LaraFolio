<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Técnico de {{ $username }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #0284c7;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }

        .subtitle {
            font-size: 13px;
            color: #64748b;
            margin: 4px 0 0 0;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .meta-td {
            font-size: 14px;
            vertical-align: top;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0284c7;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .repo-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .repo-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 12px;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #e2e8f0;
        }

        .repo-table td {
            font-size: 12px;
            padding: 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .chart-box {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <table width="100%">
        <tr>
            <td>
                <h1 class="title">LaraFolio - Resumen Técnico</h1>
                <p class="subtitle">Análisis automatizado de perfil de desarrollador</p>
            </td>
            <td style="text-align: right; font-size: 12px; color: #64748b;">
                <strong>Fecha de generación:</strong> {{ $date }}
            </td>
        </tr>
    </table>
</div>

<table class="meta-table">
    <tr>
        <td class="meta-td" width="60%">
            <h2 style="margin:0 0 8px 0; font-size: 18px; color:#0f172a;">{{ $profileInfo->name ?? $username }}</h2>
            <p style="margin:0 0 5px 0;"><strong>Usuario de GitHub:</strong> {{ $username }}</p>
            @if($profileInfo->bio)
                <p style="margin:5px 0 0 0; font-size: 13px; color:#475569; font-style: italic;">
                    "{{ $profileInfo->bio }}"</p>
            @endif
        </td>
        <td class="meta-td" width="40%" style="background-color: #fafafa; padding: 12px; border-radius: 8px;">
            <p style="margin: 0 0 6px 0;"><strong>Repositorios Públicos:</strong> {{ $repositories->count() }}</p>
            <p style="margin: 0 0 6px 0;"><strong>Estrellas Acumuladas:</strong> {{ $repositories->sum('stars_count') }}
            </p>
            <p style="margin: 0;"><strong>Forks Totales:</strong> {{ $repositories->sum('forks_count') }}</p>
        </td>
    </tr>
</table>

<div class="section-title">Distribución de Lenguajes (Código Fuente)</div>
<table width="100%" style="margin-bottom: 20px;">
    <tr>
        <td width="55%" class="chart-box">
            <img src="{{ $quickChartUrl }}" width="350" alt="Gráfica de Lenguajes">
        </td>
        <td width="45%" style="vertical-align: middle;">
            <table width="100%" style="font-size: 12px; border-spacing: 0 6px;">
                @foreach($languages->take(6) as $lang)
                    <tr>
                        <td style="font-weight: bold; color:#334155;">{{ $lang->name }}</td>
                        <td style="text-align: right; color:#64748b;">
                            @if($lang->total_bytes >= 1024 * 1024)
                                {{ number_format($lang->total_bytes / (1024 * 1024), 2) }} MB
                            @else
                                {{ number_format($lang->total_bytes / 1024, 2) }} KB
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>

<div class="section-title">Catálogo de Repositorios Analizados</div>
<table class="repo-table">
    <thead>
    <tr>
        <th width="30%">Nombre del Proyecto</th>
        <th width="50%">Descripción</th>
        <th width="20%">Lenguaje Principal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($repositories->sortByDesc('github_updated_at')->take(8) as $repo)
        <tr>
            <td style="font-weight: bold; color: #0f172a;">{{ $repo->name }}</td>
            <td style="color: #475569;">{{ $repo->description ?? 'Sin descripción disponible.' }}</td>
            <td style="color: #0284c7; font-weight: 500;">{{ $repo->primary_language ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    Reporte analítico generado de manera automática por LaraFolio. San Luis Potosí, México.
</div>

</body>
</html>
