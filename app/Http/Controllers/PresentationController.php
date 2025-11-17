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
     * Nota: Requiere instalar phpoffice/phppresentation: composer require phpoffice/phppresentation
     */
    public function exportPpt(Plan $plan)
    {
        $this->authorize('view', $plan);
        
        // Verificar si PhpPresentation está disponible
        if (!class_exists('PhpOffice\PhpPresentation\PhpPresentation')) {
            return redirect()->back()
                ->with('error', 'La funcionalidad de exportación a PowerPoint requiere instalar: composer require phpoffice/phppresentation');
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

        $objPHPPowerPoint = new \PhpOffice\PhpPresentation\PhpPresentation();
        
        // Configurar propiedades del documento
        $objPHPPowerPoint->getDocumentProperties()
            ->setCreator('Strategos')
            ->setTitle($plan->name)
            ->setSubject('Presentación del Plan')
            ->setDescription($plan->description)
            ->setCreated(time());

        // Slide 1: Portada
        $slide = $objPHPPowerPoint->createSlide();
        $shape = $slide->createRichTextShape()
            ->setHeight(600)
            ->setWidth(960)
            ->setOffsetX(0)
            ->setOffsetY(0);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(\PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(\PhpOffice\PhpPresentation\Style\Alignment::VERTICAL_CENTER);
        $textRun = $shape->createTextRun($plan->name);
        $textRun->getFont()->setBold(true)->setSize(48);
        $textRun = $shape->createTextRun("\n\n" . $plan->description);
        $textRun->getFont()->setSize(24);

        // Slides de Secciones
        foreach ($plan->sections as $section) {
            $slide = $objPHPPowerPoint->createSlide();
            $shape = $slide->createRichTextShape()
                ->setHeight(600)
                ->setWidth(960)
                ->setOffsetX(0)
                ->setOffsetY(0);
            $textRun = $shape->createTextRun($section->title);
            $textRun->getFont()->setBold(true)->setSize(36);
            $textRun = $shape->createTextRun("\n\n" . strip_tags($section->content));
            $textRun->getFont()->setSize(18);
        }

        // Slide de KPIs
        if ($plan->kpis->count() > 0) {
            $slide = $objPHPPowerPoint->createSlide();
            $shape = $slide->createRichTextShape()
                ->setHeight(600)
                ->setWidth(960)
                ->setOffsetX(0)
                ->setOffsetY(0);
            $textRun = $shape->createTextRun('Indicadores Clave (KPIs)');
            $textRun->getFont()->setBold(true)->setSize(36);
            
            foreach ($plan->kpis->take(6) as $kpi) {
                $textRun = $shape->createTextRun("\n\n" . $kpi->name . ": " . number_format($kpi->current_value, 2) . " " . $kpi->unit);
                $textRun->getFont()->setSize(18);
            }
        }

        // Slide de Milestones
        if ($plan->milestones->count() > 0) {
            $slide = $objPHPPowerPoint->createSlide();
            $shape = $slide->createRichTextShape()
                ->setHeight(600)
                ->setWidth(960)
                ->setOffsetX(0)
                ->setOffsetY(0);
            $textRun = $shape->createTextRun('Hitos del Plan');
            $textRun->getFont()->setBold(true)->setSize(36);
            
            foreach ($plan->milestones->take(8) as $milestone) {
                $textRun = $shape->createTextRun("\n\n" . $milestone->name . " - " . ($milestone->target_date ? $milestone->target_date->format('d/m/Y') : 'Sin fecha'));
                $textRun->getFont()->setSize(18);
            }
        }

        // Slide de Riesgos
        if ($plan->risks->count() > 0) {
            $slide = $objPHPPowerPoint->createSlide();
            $shape = $slide->createRichTextShape()
                ->setHeight(600)
                ->setWidth(960)
                ->setOffsetX(0)
                ->setOffsetY(0);
            $textRun = $shape->createTextRun('Riesgos Identificados');
            $textRun->getFont()->setBold(true)->setSize(36);
            
            foreach ($plan->risks->take(6) as $risk) {
                $textRun = $shape->createTextRun("\n\n" . $risk->name . " - " . ucfirst($risk->category));
                $textRun->getFont()->setSize(18);
            }
        }

        // Slide Final
        $slide = $objPHPPowerPoint->createSlide();
        $shape = $slide->createRichTextShape()
            ->setHeight(600)
            ->setWidth(960)
            ->setOffsetX(0)
            ->setOffsetY(0);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(\PhpOffice\PhpPresentation\Style\Alignment::HORIZONTAL_CENTER);
        $shape->getActiveParagraph()->getAlignment()->setVertical(\PhpOffice\PhpPresentation\Style\Alignment::VERTICAL_CENTER);
        $textRun = $shape->createTextRun('Gracias');
        $textRun->getFont()->setBold(true)->setSize(48);
        $textRun = $shape->createTextRun("\n\nStrategos - Gestión Estratégica");
        $textRun->getFont()->setSize(24);

        // Guardar el archivo
        $oWriterPPTX = \PhpOffice\PhpPresentation\IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
        
        $filename = 'presentacion-' . $plan->slug . '.pptx';
        $tempFile = tempnam(sys_get_temp_dir(), 'ppt_');
        $oWriterPPTX->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
