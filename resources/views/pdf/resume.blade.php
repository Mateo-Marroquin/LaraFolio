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
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }

        .subtitle {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 0 0;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .meta-td {
            font-size: 13px;
            vertical-align: top;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0284c7;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .repo-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .repo-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
            padding: 6px;
            border: 1px solid #e2e8f0;
        }

        .repo-table td {
            font-size: 11px;
            padding: 6px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .chart-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

<div class="header">
    <table width="100%">
        <tr>
            <td>
                <h1 class="title">LaraFolio - Resumen Técnico</h1>
                <p class="subtitle">Análisis automatizado de rendimiento de código</p>
            </td>
            <td style="text-align: right; font-size: 11px; color: #64748b;">
                <strong>Generado:</strong> {{ $date }}
            </td>
        </tr>
    </table>
</div>

<table class="meta-table">
    <tr>
        <td class="meta-td" width="60%">
            <h2 style="margin:0 0 6px 0; font-size: 16px; color:#0f172a;">{{ $profileInfo->name ?? $username }}</h2>
            <p style="margin:0 0 4px 0;"><strong>Usuario de GitHub:</strong> {{ $username }}</p>
            @if($profileInfo->bio)
                <p style="margin:4px 0 0 0; font-size: 12px; color:#475569; font-style: italic;">
                    "{{ $profileInfo->bio }}"</p>
            @endif
        </td>
        <td class="meta-td" width="40%"
            style="background-color: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #f1f5f9;">
            <p style="margin: 0 0 4px 0;"><strong>{{ $repoLabel }}</strong> {{ $repositories->count() }}</p>
            <p style="margin: 0 0 4px 0;"><strong>Estrellas Totales:</strong> {{ $repositories->sum('stars_count') }}
            </p>
            <p style="margin: 0;"><strong>Forks Totales:</strong> {{ $repositories->sum('forks_count') }}</p>
        </td>
    </tr>
</table>

<div class="section-title">Distribución de Lenguajes (Bytes de código)</div>
<table width="100%" style="margin-bottom: 15px;">
    <tr>
        <td width="50%" class="chart-container">
            <img src="{{ $quickChartUrl }}" width="280" alt="Dona de Lenguajes">
        </td>
        <td width="50%" style="vertical-align: middle; padding-left: 10px;">
            <table width="100%" style="font-size: 11px; border-spacing: 0 4px;">
                @foreach($languages->take(5) as $lang)
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

<div class="section-title">Ritmo de Actividad Diaria — <span style="text-transform: capitalize;">{{ $monthName }}</span>
</div>
<div class="chart-container" style="margin-bottom: 25px;">
    <p style="font-size: 11px; color:#64748b; margin: 0 0 8px 0; text-align: left;">
        Frecuencia de interacciones y contribuciones directas registradas por día del mes en los servidores de GitHub.
    </p>
    <img src="{{ $lineChartUrl }}" width="520" alt="Línea de Actividad">
</div>

<div class="section-title">Proyectos Destacados Analizados</div>
<table class="repo-table">
    <thead>
    <tr>
        <th width="30%">Repositorio</th>
        <th width="50%">Descripción</th>
        <th width="20%">Lenguaje Principal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($repositories->sortByDesc('github_updated_at')->take(4) as $repo)
        <tr>
            <td style="font-weight: bold; color: #0f172a;">
                {{ $repo->name }}
                @if($repo->is_private)
                    <span
                        style="color: #ef4444; font-size: 9px; font-weight: normal; margin-left: 4px;">(Privado)</span>
                @endif
            </td>
            <td style="color: #475569;">{{ $repo->description ?? 'Sin descripción disponible.' }}</td>
            <td style="color: #0284c7; font-weight: 500;">{{ $repo->primary_language ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="footer">
    Reporte analítico confidencial automatizado por LaraFolio. San Luis Potosí, México.
</div>

</body>
</html>
