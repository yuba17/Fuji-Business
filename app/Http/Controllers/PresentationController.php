<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;
// use Barryvdh\DomPDF\Facade\Pdf; // Requiere: composer require barryvdh/laravel-dompdf

class PresentationController extends Controller
{
    /**
     * Mostrar presentación de un plan
     */
    public function show(Plan $plan): View
    {
        $this->authorize('view', $plan);
        
        $plan->load([
            'planType',
            'area',
            'manager',
            'director',
            'sections' => function($q) {
                $q->orderBy('order');
            },
            'kpis' => function($q) {
                $q->where('is_active', true);
            },
            'milestones' => function($q) {
                $q->orderBy('target_date');
            },
            'risks' => function($q) {
                $q->orderBy('category', 'desc');
            },
        ]);

        // Calcular número de slides
        $totalSlides = 1; // Slide inicial
        $totalSlides += $plan->sections->count(); // Una slide por sección
        $totalSlides += 1; // Slide de KPIs
        $totalSlides += 1; // Slide de Milestones
        $totalSlides += 1; // Slide de Riesgos
        $totalSlides += 1; // Slide final

        return view('presentation.show', compact('plan', 'totalSlides'))->with('plan', $plan)->with('totalSlides', $totalSlides);
    }

    /**
     * Exportar presentación a PDF
     * Nota: Requiere instalar barryvdh/laravel-dompdf: composer require barryvdh/laravel-dompdf
     */
    public function exportPdf(Plan $plan)
    {
        $this->authorize('view', $plan);
        
        // Verificar si dompdf está disponible
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return redirect()->back()
                ->with('error', 'La funcionalidad de exportación a PDF requiere instalar: composer require barryvdh/laravel-dompdf');
        }
        
        $plan->load([
            'planType',
            'area',
            'manager',
            'director',
            'sections' => function($q) {
                $q->orderBy('order');
            },
            'kpis' => function($q) {
                $q->where('is_active', true);
            },
            'milestones' => function($q) {
                $q->orderBy('target_date');
            },
            'risks' => function($q) {
                $q->orderBy('category', 'desc');
            },
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('presentation.pdf', compact('plan'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('presentacion-' . $plan->slug . '.pdf');
    }
}
