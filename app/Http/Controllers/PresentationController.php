<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        // Preparar notas para cada slide (pueden venir de metadata o ser vacías)
        $slideNotes = [];
        $slideNotes[1] = $plan->metadata['presentation_notes']['cover'] ?? '';
        $slideNumber = 2;
        foreach ($plan->sections as $section) {
            $slideNotes[$slideNumber] = $section->metadata['presentation_notes'] ?? '';
            $slideNumber++;
        }
        $slideNotes[$slideNumber] = $plan->metadata['presentation_notes']['kpis'] ?? '';
        $slideNumber++;
        $slideNotes[$slideNumber] = $plan->metadata['presentation_notes']['milestones'] ?? '';
        $slideNumber++;
        $slideNotes[$slideNumber] = $plan->metadata['presentation_notes']['risks'] ?? '';
        $slideNumber++;
        $slideNotes[$slideNumber] = $plan->metadata['presentation_notes']['final'] ?? '';

        return view('presentation.show', compact('plan', 'totalSlides', 'slideNotes'));
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

    /**
     * Exportar presentación a PowerPoint
     * Usa el servicio PowerPointService para generar presentaciones corporativas profesionales
     */
    public function exportPpt(Plan $plan)
    {
        $this->authorize('view', $plan);
        
        // Verificar si PhpPresentation está disponible
        if (!class_exists('PhpOffice\PhpPresentation\PhpPresentation')) {
            return redirect()->back()
                ->with('error', 'La funcionalidad de exportación a PowerPoint requiere instalar: composer require phpoffice/phppresentation');
        }
        
        try {
            // Usar el servicio de PowerPoint para generar la presentación
            $powerPointService = new \App\Services\PowerPointService();
            $powerPointService->generatePlanPresentation($plan);
            
            // Guardar a archivo temporal y descargar
            $tempFile = $powerPointService->saveToTempFile();
            $filename = 'presentacion-' . $plan->slug . '.pptx';
            
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Error generando PowerPoint: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al generar la presentación: ' . $e->getMessage());
        }
    }
}
