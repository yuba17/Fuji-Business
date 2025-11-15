<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Presentación - {{ $plan->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .slide {
            background: white;
            margin-bottom: 20px;
            padding: 40px;
            page-break-after: always;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .slide:last-child {
            page-break-after: auto;
        }
        h1 {
            color: #dc2626;
            font-size: 36px;
            margin-bottom: 20px;
        }
        h2 {
            color: #ea580c;
            font-size: 28px;
            margin-bottom: 15px;
        }
        h3 {
            color: #1f2937;
            font-size: 22px;
            margin-bottom: 10px;
        }
        .cover {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 600px;
        }
        .cover h1 {
            font-size: 48px;
            margin-bottom: 30px;
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .kpi-card {
            border-left: 4px solid #2563eb;
            padding: 15px;
            background: #f9fafb;
        }
        .milestone-item {
            border-left: 4px solid #9333ea;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9fafb;
        }
        .risk-item {
            border-left: 4px solid #ea580c;
            padding: 15px;
            margin-bottom: 15px;
            background: #f9fafb;
        }
    </style>
</head>
<body>
    <!-- Portada -->
    <div class="slide cover">
        <h1>{{ $plan->name }}</h1>
        <p style="font-size: 24px; color: #6b7280;">{{ $plan->description }}</p>
        @if($plan->planType)
            <p style="font-size: 18px; color: #9ca3af; margin-top: 20px;">{{ $plan->planType->name }}</p>
        @endif
    </div>

    <!-- Secciones -->
    @foreach($plan->sections as $section)
        <div class="slide">
            <h2>{{ $section->title }}</h2>
            <div style="font-size: 16px; line-height: 1.6; color: #374151;">
                {!! nl2br(e($section->content)) !!}
            </div>
        </div>
    @endforeach

    <!-- KPIs -->
    @if($plan->kpis->count() > 0)
        <div class="slide">
            <h2>Indicadores Clave (KPIs)</h2>
            <div class="kpi-grid">
                @foreach($plan->kpis->take(6) as $kpi)
                    <div class="kpi-card">
                        <h3>{{ $kpi->name }}</h3>
                        <p style="font-size: 24px; font-weight: bold; color: #2563eb;">
                            {{ number_format($kpi->current_value, 2) }} {{ $kpi->unit }}
                        </p>
                        <p style="font-size: 14px; color: #6b7280;">
                            Objetivo: {{ number_format($kpi->target_value, 2) }} {{ $kpi->unit }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Milestones -->
    @if($plan->milestones->count() > 0)
        <div class="slide">
            <h2>Hitos del Plan</h2>
            @foreach($plan->milestones->take(8) as $milestone)
                <div class="milestone-item">
                    <h3>{{ $milestone->name }}</h3>
                    <p style="color: #6b7280;">{{ $milestone->description }}</p>
                    <p style="margin-top: 10px;">
                        <strong>Fecha:</strong> {{ $milestone->target_date->format('d/m/Y') }} | 
                        <strong>Estado:</strong> {{ $milestone->status_label }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Riesgos -->
    @if($plan->risks->count() > 0)
        <div class="slide">
            <h2>Riesgos Identificados</h2>
            @foreach($plan->risks->take(6) as $risk)
                <div class="risk-item">
                    <h3>{{ $risk->title }}</h3>
                    <p style="color: #6b7280;">{{ Str::limit($risk->description, 200) }}</p>
                    <p style="margin-top: 10px;">
                        <strong>Categoría:</strong> {{ ucfirst($risk->category) }} | 
                        <strong>Probabilidad:</strong> {{ ucfirst($risk->probability) }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Slide Final -->
    <div class="slide cover">
        <h1>Gracias</h1>
        <p style="font-size: 24px; color: #6b7280;">Strategos - Gestión Estratégica</p>
        <p style="font-size: 18px; color: #9ca3af; margin-top: 20px;">{{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>

