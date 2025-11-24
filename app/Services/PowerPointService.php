<?php

namespace App\Services;

use App\Models\Plan;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Border;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Shape\Table;
use PhpOffice\PhpPresentation\Shape\AutoShape;
use PhpOffice\PhpPresentation\DocumentLayout;

/**
 * Servicio para generar presentaciones PowerPoint corporativas profesionales
 * 
 * Este servicio encapsula toda la lógica de generación de PowerPoint
 * con diseños corporativos consistentes y profesionales.
 */
class PowerPointService
{
    // Colores corporativos Fujitsu/FujiOffers
    private const COLOR_PRIMARY = 'E11D48';      // Rojo primario
    private const COLOR_SECONDARY = 'F97316';    // Naranja secundario
    private const COLOR_WHITE = 'FFFFFF';
    private const COLOR_GRAY_DARK = '374151';
    private const COLOR_GRAY_MEDIUM = '6B7280';
    private const COLOR_GRAY_LIGHT = 'F9FAFB';
    private const COLOR_GRAY_BORDER = 'E5E7EB';
    private const COLOR_SUCCESS = '10B981';
    private const COLOR_WARNING = 'F59E0B';
    private const COLOR_ERROR = 'DC2626';
    
    // Tipografía corporativa
    private const FONT_PRIMARY = 'Arial';  // En producción, usar fuente corporativa si está disponible
    private const FONT_SECONDARY = 'Arial';
    
    // Dimensiones estándar (16:9)
    private const SLIDE_WIDTH = 960;
    private const SLIDE_HEIGHT = 540;
    
    private PhpPresentation $presentation;
    private Color $colorPrimary;
    private Color $colorSecondary;
    private Color $colorWhite;
    private Color $colorGrayDark;
    private Color $colorGrayMedium;
    private Color $colorGrayLight;
    
    public function __construct()
    {
        $this->presentation = new PhpPresentation();
        $this->presentation->getLayout()->setDocumentLayout(DocumentLayout::LAYOUT_SCREEN_16X9, true);
        
        // Inicializar colores
        $this->colorPrimary = new Color(self::COLOR_PRIMARY);
        $this->colorSecondary = new Color(self::COLOR_SECONDARY);
        $this->colorWhite = new Color(self::COLOR_WHITE);
        $this->colorGrayDark = new Color(self::COLOR_GRAY_DARK);
        $this->colorGrayMedium = new Color(self::COLOR_GRAY_MEDIUM);
        $this->colorGrayLight = new Color(self::COLOR_GRAY_LIGHT);
    }
    
    /**
     * Generar presentación completa de un plan
     */
    public function generatePlanPresentation(Plan $plan): PhpPresentation
    {
        // Configurar propiedades del documento
        $this->presentation->getDocumentProperties()
            ->setCreator('Fuji Business')
            ->setTitle($plan->name)
            ->setSubject('Presentación del Plan Estratégico')
            ->setDescription($plan->description ?? '')
            ->setCreated(time());
        
        // Cargar datos del plan
        $plan->load([
            'planType',
            'area',
            'manager',
            'director',
            'sections' => fn($q) => $q->orderBy('order'),
            'kpis' => fn($q) => $q->where('is_active', true)->orderBy('order'),
            'milestones' => fn($q) => $q->orderBy('target_date'),
            'tasks' => fn($q) => $q->with(['assignedTo', 'createdBy'])->orderBy('due_date'),
            'risks' => fn($q) => $q->orderBy('category', 'desc'),
            'clients',
            'projects' => fn($q) => $q->with(['client', 'manager'])->orderBy('start_date'),
        ]);
        
        // Slide 1: Portada profesional
        $this->createTitleSlide($plan);
        
        // Slide 2: Agenda/Índice (opcional pero profesional)
        $this->createAgendaSlide($plan);
        
        // Slides de secciones
        foreach ($plan->sections as $section) {
            $this->createContentSlide($section->title, $section->content);
        }
        
        // Slide de KPIs con diseño mejorado
        if ($plan->kpis->isNotEmpty()) {
            $this->createKpisSlide($plan->kpis);
        }
        
        // Slide de Milestones con timeline visual
        if ($plan->milestones->isNotEmpty()) {
            $this->createMilestonesSlide($plan->milestones);
        }
        
        // Slide de Riesgos con matriz visual
        if ($plan->risks->isNotEmpty()) {
            $this->createRisksSlide($plan->risks);
        }
        
        // Slide de Resumen Ejecutivo
        $this->createExecutiveSummarySlide($plan);
        
        // Slide final
        $this->createThankYouSlide();
        
        return $this->presentation;
    }
    
    /**
     * Crear slide de portada profesional
     */
    private function createTitleSlide(Plan $plan): void
    {
        $slide = $this->presentation->createSlide();
        
        // Fondo con degradado corporativo
        $bgShape = $slide->createAutoShape(AutoShape::TYPE_RECTANGLE);
        $bgShape->setHeight(self::SLIDE_HEIGHT)
                ->setWidth(self::SLIDE_WIDTH)
                ->setOffsetX(0)
                ->setOffsetY(0);
        $bgShape->getFill()
            ->setFillType(Fill::FILL_GRADIENT_LINEAR)
            ->setRotation(90)
            ->setStartColor($this->colorPrimary)
            ->setEndColor($this->colorSecondary);
        $bgShape->getBorder()->setLineStyle(Border::LINE_NONE);
        
        // Elemento decorativo superior (barra blanca semitransparente)
        $decorBar = $slide->createAutoShape(AutoShape::TYPE_RECTANGLE);
        $decorBar->setHeight(120)
                 ->setWidth(self::SLIDE_WIDTH)
                 ->setOffsetX(0)
                 ->setOffsetY(0);
        $decorBar->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB(self::COLOR_WHITE);
        $decorBar->getFill()->getStartColor()->setAlpha(15);
        $decorBar->getBorder()->setLineStyle(Border::LINE_NONE);
        
        // Título principal (centrado, grande, bold)
        $titleShape = $slide->createRichTextShape()
            ->setHeight(140)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(180);
        $titleShape->getActiveParagraph()
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        
        $titleRun = $titleShape->createTextRun($plan->name);
        $titleRun->getFont()
            ->setBold(true)
            ->setSize(52)
            ->setColor($this->colorWhite)
            ->setName(self::FONT_PRIMARY);
        
        // Subtítulo/Descripción (si existe)
        if ($plan->description) {
            $descShape = $slide->createRichTextShape()
                ->setHeight(60)
                ->setWidth(760)
                ->setOffsetX(100)
                ->setOffsetY(340);
            $descShape->getActiveParagraph()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $descRun = $descShape->createTextRun($plan->description);
            $descRun->getFont()
                ->setSize(20)
                ->setColor($this->colorWhite)
                ->setName(self::FONT_PRIMARY);
        }
        
        // Información del plan (tipo, área, manager) - en la parte inferior
        $infoY = 420;
        $infoShape = $slide->createRichTextShape()
            ->setHeight(80)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY($infoY);
        $infoShape->getActiveParagraph()
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $infoText = [];
        if ($plan->planType) {
            $infoText[] = $plan->planType->name;
        }
        if ($plan->area) {
            $infoText[] = $plan->area->name;
        }
        if ($plan->manager) {
            $infoText[] = 'Manager: ' . $plan->manager->name;
        }
        
        if (!empty($infoText)) {
            $infoRun = $infoShape->createTextRun(implode(' • ', $infoText));
            $infoRun->getFont()
                ->setSize(18)
                ->setColor($this->colorWhite)
                ->setName(self::FONT_PRIMARY);
        }
        
        // Logo/Watermark (opcional - si tienes logo corporativo)
        // $logoShape = $slide->createDrawingShape();
        // $logoShape->setPath(public_path('images/logo.png'))
        //           ->setHeight(60)
        //           ->setOffsetX(50)
        //           ->setOffsetY(470);
    }
    
    /**
     * Crear slide de agenda/índice
     */
    private function createAgendaSlide(Plan $plan): void
    {
        $slide = $this->presentation->createSlide();
        
        // Fondo claro
        $this->setSlideBackground($slide, self::COLOR_GRAY_LIGHT);
        
        // Header con barra corporativa
        $this->createSlideHeader($slide, '📋 Agenda');
        
        // Lista de secciones
        $yPos = 140;
        $itemHeight = 50;
        $spacing = 10;
        
        foreach ($plan->sections as $index => $section) {
            // Tarjeta de sección
            $cardShape = $slide->createAutoShape(AutoShape::TYPE_ROUNDED_RECTANGLE);
            $cardShape->setHeight($itemHeight)
                      ->setWidth(900)
                      ->setOffsetX(30)
                      ->setOffsetY($yPos);
            $cardShape->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($index % 2 === 0 ? self::COLOR_WHITE : self::COLOR_GRAY_LIGHT);
            $cardShape->getBorder()
                ->setLineStyle(Border::LINE_SINGLE)
                ->getColor()->setRGB(self::COLOR_GRAY_BORDER);
            $cardShape->getBorder()->setLineWidth(1);
            
            // Número de sección con círculo
            $numberCircle = $slide->createAutoShape(AutoShape::TYPE_OVAL);
            $numberCircle->setHeight(35)
                         ->setWidth(35)
                         ->setOffsetX(50)
                         ->setOffsetY($yPos + 7);
            $numberCircle->getFill()
                ->setFillType(Fill::FILL_GRADIENT_LINEAR)
                ->setRotation(90)
                ->setStartColor($this->colorPrimary)
                ->setEndColor($this->colorSecondary);
            $numberCircle->getBorder()->setLineStyle(Border::LINE_NONE);
            
            $numberText = $slide->createRichTextShape()
                ->setHeight(35)
                ->setWidth(35)
                ->setOffsetX(50)
                ->setOffsetY($yPos + 7);
            $numberText->getActiveParagraph()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $numberRun = $numberText->createTextRun($index + 1);
            $numberRun->getFont()
                ->setBold(true)
                ->setSize(18)
                ->setColor($this->colorWhite)
                ->setName(self::FONT_PRIMARY);
            
            // Título de sección
            $sectionText = $slide->createRichTextShape()
                ->setHeight($itemHeight - 10)
                ->setWidth(800)
                ->setOffsetX(100)
                ->setOffsetY($yPos + 5);
            $sectionRun = $sectionText->createTextRun($section->title);
            $sectionRun->getFont()
                ->setSize(18)
                ->setColor($this->colorGrayDark)
                ->setBold(true)
                ->setName(self::FONT_PRIMARY);
            
            $yPos += $itemHeight + $spacing;
        }
    }
    
    /**
     * Crear slide de contenido (secciones)
     */
    private function createContentSlide(string $title, ?string $content): void
    {
        $slide = $this->presentation->createSlide();
        
        // Fondo claro
        $this->setSlideBackground($slide, self::COLOR_GRAY_LIGHT);
        
        // Barra lateral decorativa
        $sideBar = $slide->createAutoShape(AutoShape::TYPE_RECTANGLE);
        $sideBar->setHeight(self::SLIDE_HEIGHT)
                ->setWidth(12)
                ->setOffsetX(0)
                ->setOffsetY(0);
        $sideBar->getFill()
            ->setFillType(Fill::FILL_GRADIENT_LINEAR)
            ->setRotation(90)
            ->setStartColor($this->colorPrimary)
            ->setEndColor($this->colorSecondary);
        $sideBar->getBorder()->setLineStyle(Border::LINE_NONE);
        
        // Header con título
        $this->createSlideHeader($slide, $title, false);
        
        // Contenido
        if ($content) {
            $cleanContent = strip_tags($content);
            $contentShape = $slide->createRichTextShape()
                ->setHeight(380)
                ->setWidth(900)
                ->setOffsetX(50)
                ->setOffsetY(140);
            $contentShape->getActiveParagraph()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);
            
            // Dividir contenido en párrafos y formatear
            $paragraphs = explode("\n", $cleanContent);
            foreach ($paragraphs as $index => $paragraph) {
                if (trim($paragraph)) {
                    if ($index > 0) {
                        $contentShape->createParagraph();
                    }
                    $textRun = $contentShape->createTextRun(trim($paragraph));
                    $textRun->getFont()
                        ->setSize(18)
                        ->setColor($this->colorGrayDark)
                        ->setName(self::FONT_PRIMARY);
                }
            }
        }
    }
    
    /**
     * Crear slide de KPIs con diseño profesional
     */
    private function createKpisSlide($kpis): void
    {
        $slide = $this->presentation->createSlide();
        
        $this->setSlideBackground($slide, self::COLOR_GRAY_LIGHT);
        $this->createSlideHeader($slide, '📊 Indicadores Clave (KPIs)');
        
        // Crear tabla profesional
        $kpisToShow = $kpis->take(6);
        $table = $slide->createTableShape($kpisToShow->count() + 1, 4);
        $table->setHeight(400)
              ->setWidth(900)
              ->setOffsetX(30)
              ->setOffsetY(120);
        
        // Encabezado de tabla
        $headerRow = $table->createRow();
        $headerRow->setHeight(55);
        
        $headers = ['KPI', 'Valor Actual', 'Objetivo', 'Estado'];
        foreach ($headers as $idx => $headerText) {
            $headerCell = $headerRow->getCell($idx);
            $headerCell->getFill()
                ->setFillType(Fill::FILL_GRADIENT_LINEAR)
                ->setRotation(90)
                ->setStartColor($this->colorPrimary)
                ->setEndColor($this->colorSecondary);
            $headerCell->getActiveParagraph()
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            
            $headerRun = $headerCell->createTextRun($headerText);
            $headerRun->getFont()
                ->setBold(true)
                ->setSize(16)
                ->setColor($this->colorWhite)
                ->setName(self::FONT_PRIMARY);
        }
        
        // Filas de datos
        $rowIndex = 0;
        foreach ($kpisToShow as $kpi) {
            $row = $table->createRow();
            $row->setHeight(60);
            
            $rowColor = $rowIndex % 2 === 0 ? self::COLOR_WHITE : self::COLOR_GRAY_LIGHT;
            
            // Nombre del KPI
            $cell1 = $row->getCell(0);
            $cell1->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($rowColor);
            $cell1->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $cell1->createTextRun($kpi->name)->getFont()
                ->setSize(15)
                ->setColor($this->colorGrayDark)
                ->setBold(true)
                ->setName(self::FONT_PRIMARY);
            
            // Valor actual
            $cell2 = $row->getCell(1);
            $cell2->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($rowColor);
            $cell2->getActiveParagraph()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $cell2->createTextRun(number_format($kpi->current_value, 2) . ' ' . $kpi->unit)->getFont()
                ->setSize(15)
                ->setColor($this->colorGrayDark)
                ->setName(self::FONT_PRIMARY);
            
            // Objetivo
            $cell3 = $row->getCell(2);
            $cell3->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($rowColor);
            $cell3->getActiveParagraph()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $cell3->createTextRun($kpi->target_value ? number_format($kpi->target_value, 2) . ' ' . $kpi->unit : '-')->getFont()
                ->setSize(15)
                ->setColor($this->colorGrayDark)
                ->setName(self::FONT_PRIMARY);
            
            // Estado con color
            $cell4 = $row->getCell(3);
            $cell4->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($rowColor);
            $cell4->getActiveParagraph()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            
            $isAchieved = $kpi->current_value >= ($kpi->target_value ?? 0);
            $statusColor = $isAchieved ? new Color(self::COLOR_SUCCESS) : new Color(self::COLOR_WARNING);
            $statusText = $isAchieved ? '✅ Cumplido' : '⚠️ En progreso';
            
            $cell4->createTextRun($statusText)->getFont()
                ->setSize(14)
                ->setColor($statusColor)
                ->setBold(true)
                ->setName(self::FONT_PRIMARY);
            
            $rowIndex++;
        }
    }
    
    /**
     * Crear slide de Milestones con diseño visual
     */
    private function createMilestonesSlide($milestones): void
    {
        $slide = $this->presentation->createSlide();
        
        $this->setSlideBackground($slide, self::COLOR_GRAY_LIGHT);
        $this->createSlideHeader($slide, '🎯 Hitos del Plan');
        
        $milestonesToShow = $milestones->take(6);
        $yPos = 130;
        $cardHeight = 70;
        $spacing = 12;
        
        foreach ($milestonesToShow as $index => $milestone) {
            // Tarjeta con diseño moderno
            $cardShape = $slide->createAutoShape(AutoShape::TYPE_ROUNDED_RECTANGLE);
            $cardShape->setHeight($cardHeight)
                      ->setWidth(900)
                      ->setOffsetX(30)
                      ->setOffsetY($yPos);
            $cardShape->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($index % 2 === 0 ? self::COLOR_WHITE : self::COLOR_GRAY_LIGHT);
            $cardShape->getBorder()
                ->setLineStyle(Border::LINE_SINGLE)
                ->getColor()->setRGB(self::COLOR_GRAY_BORDER);
            $cardShape->getBorder()->setLineWidth(2);
            
            // Indicador de fecha (barra lateral de color)
            $dateBar = $slide->createAutoShape(AutoShape::TYPE_RECTANGLE);
            $dateBar->setHeight($cardHeight)
                    ->setWidth(8)
                    ->setOffsetX(30)
                    ->setOffsetY($yPos);
            $dateBar->getFill()
                ->setFillType(Fill::FILL_GRADIENT_LINEAR)
                ->setRotation(90)
                ->setStartColor($this->colorPrimary)
                ->setEndColor($this->colorSecondary);
            $dateBar->getBorder()->setLineStyle(Border::LINE_NONE);
            
            // Contenido de la tarjeta
            $cardText = $slide->createRichTextShape()
                ->setHeight($cardHeight - 10)
                ->setWidth(850)
                ->setOffsetX(50)
                ->setOffsetY($yPos + 5);
            
            $milestoneText = $milestone->name;
            if ($milestone->target_date) {
                $milestoneText .= ' - ' . $milestone->target_date->format('d/m/Y');
            }
            
            $textRun = $cardText->createTextRun($milestoneText);
            $textRun->getFont()
                ->setSize(18)
                ->setColor($this->colorGrayDark)
                ->setBold(true)
                ->setName(self::FONT_PRIMARY);
            
            if ($milestone->description) {
                $descRun = $cardText->createTextRun("\n" . substr(strip_tags($milestone->description), 0, 120));
                $descRun->getFont()
                    ->setSize(14)
                    ->setColor($this->colorGrayMedium)
                    ->setName(self::FONT_PRIMARY);
            }
            
            $yPos += $cardHeight + $spacing;
        }
    }
    
    /**
     * Crear slide de Riesgos con diseño visual
     */
    private function createRisksSlide($risks): void
    {
        $slide = $this->presentation->createSlide();
        
        $this->setSlideBackground($slide, self::COLOR_GRAY_LIGHT);
        $this->createSlideHeader($slide, '⚠️ Riesgos Identificados');
        
        $risksToShow = $risks->take(6);
        $yPos = 130;
        $cardHeight = 80;
        $spacing = 12;
        
        $riskColors = [
            'high' => self::COLOR_ERROR,
            'medium' => self::COLOR_WARNING,
            'low' => self::COLOR_SUCCESS,
        ];
        
        foreach ($risksToShow as $index => $risk) {
            $riskColor = $riskColors[strtolower($risk->category)] ?? self::COLOR_GRAY_MEDIUM;
            
            // Tarjeta con borde de color según categoría
            $cardShape = $slide->createAutoShape(AutoShape::TYPE_ROUNDED_RECTANGLE);
            $cardShape->setHeight($cardHeight)
                      ->setWidth(900)
                      ->setOffsetX(30)
                      ->setOffsetY($yPos);
            $cardShape->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB(self::COLOR_WHITE);
            $cardShape->getBorder()
                ->setLineStyle(Border::LINE_SINGLE)
                ->getColor()->setRGB($riskColor);
            $cardShape->getBorder()->setLineWidth(4);
            
            // Barra lateral de color
            $sideBar = $slide->createAutoShape(AutoShape::TYPE_RECTANGLE);
            $sideBar->setHeight($cardHeight)
                    ->setWidth(8)
                    ->setOffsetX(30)
                    ->setOffsetY($yPos);
            $sideBar->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($riskColor);
            $sideBar->getBorder()->setLineStyle(Border::LINE_NONE);
            
            // Contenido
            $cardText = $slide->createRichTextShape()
                ->setHeight($cardHeight - 10)
                ->setWidth(850)
                ->setOffsetX(50)
                ->setOffsetY($yPos + 5);
            
            $riskText = $risk->name . ' - ' . ucfirst($risk->category);
            $textRun = $cardText->createTextRun($riskText);
            $textRun->getFont()
                ->setSize(18)
                ->setColor($this->colorGrayDark)
                ->setBold(true)
                ->setName(self::FONT_PRIMARY);
            
            if ($risk->description) {
                $descRun = $cardText->createTextRun("\n" . substr(strip_tags($risk->description), 0, 130));
                $descRun->getFont()
                    ->setSize(14)
                    ->setColor($this->colorGrayMedium)
                    ->setName(self::FONT_PRIMARY);
            }
            
            $yPos += $cardHeight + $spacing;
        }
    }
    
    /**
     * Crear slide de Resumen Ejecutivo
     */
    private function createExecutiveSummarySlide(Plan $plan): void
    {
        $slide = $this->presentation->createSlide();
        
        $this->setSlideBackground($slide, self::COLOR_GRAY_LIGHT);
        $this->createSlideHeader($slide, '📈 Resumen Ejecutivo');
        
        // Estadísticas en tarjetas
        $stats = [
            ['label' => 'KPIs', 'value' => $plan->kpis->count(), 'icon' => '📊'],
            ['label' => 'Hitos', 'value' => $plan->milestones->count(), 'icon' => '🎯'],
            ['label' => 'Tareas', 'value' => $plan->tasks->count(), 'icon' => '✅'],
            ['label' => 'Riesgos', 'value' => $plan->risks->count(), 'icon' => '⚠️'],
        ];
        
        $this->createStatsCards($slide, $stats, 130);
    }
    
    /**
     * Crear slide de agradecimiento
     */
    private function createThankYouSlide(): void
    {
        $slide = $this->presentation->createSlide();
        
        // Fondo con degradado corporativo
        $bgShape = $slide->createAutoShape(AutoShape::TYPE_RECTANGLE);
        $bgShape->setHeight(self::SLIDE_HEIGHT)
                ->setWidth(self::SLIDE_WIDTH)
                ->setOffsetX(0)
                ->setOffsetY(0);
        $bgShape->getFill()
            ->setFillType(Fill::FILL_GRADIENT_LINEAR)
            ->setRotation(90)
            ->setStartColor($this->colorPrimary)
            ->setEndColor($this->colorSecondary);
        $bgShape->getBorder()->setLineStyle(Border::LINE_NONE);
        
        // Texto de agradecimiento
        $thanksShape = $slide->createRichTextShape()
            ->setHeight(150)
            ->setWidth(860)
            ->setOffsetX(50)
            ->setOffsetY(200);
        $thanksShape->getActiveParagraph()
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        
        $thanksRun = $thanksShape->createTextRun('Gracias');
        $thanksRun->getFont()
            ->setBold(true)
            ->setSize(64)
            ->setColor($this->colorWhite)
            ->setName(self::FONT_PRIMARY);
        
        $subtitleRun = $thanksShape->createTextRun("\n\nFuji Business - Gestión Estratégica");
        $subtitleRun->getFont()
            ->setSize(24)
            ->setColor($this->colorWhite)
            ->setName(self::FONT_PRIMARY);
    }
    
    /**
     * Helper: Establecer fondo de slide
     */
    private function setSlideBackground($slide, string $color): void
    {
        $bgShape = $slide->createAutoShape(AutoShape::TYPE_RECTANGLE);
        $bgShape->setHeight(self::SLIDE_HEIGHT)
                ->setWidth(self::SLIDE_WIDTH)
                ->setOffsetX(0)
                ->setOffsetY(0);
        $bgShape->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB($color);
        $bgShape->getBorder()->setLineStyle(Border::LINE_NONE);
    }
    
    /**
     * Helper: Crear header de slide con barra corporativa
     */
    private function createSlideHeader($slide, string $title, bool $withGradient = true): void
    {
        // Barra superior
        $topBar = $slide->createAutoShape(AutoShape::TYPE_RECTANGLE);
        $topBar->setHeight(100)
               ->setWidth(self::SLIDE_WIDTH)
               ->setOffsetX(0)
               ->setOffsetY(0);
        
        if ($withGradient) {
            $topBar->getFill()
                ->setFillType(Fill::FILL_GRADIENT_LINEAR)
                ->setRotation(90)
                ->setStartColor($this->colorPrimary)
                ->setEndColor($this->colorSecondary);
        } else {
            $topBar->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB(self::COLOR_PRIMARY);
        }
        
        $topBar->getBorder()->setLineStyle(Border::LINE_NONE);
        
        // Título
        $titleShape = $slide->createRichTextShape()
            ->setHeight(80)
            ->setWidth(900)
            ->setOffsetX(30)
            ->setOffsetY(10);
        
        $titleRun = $titleShape->createTextRun($title);
        $titleRun->getFont()
            ->setBold(true)
            ->setSize(36)
            ->setColor($this->colorWhite)
            ->setName(self::FONT_PRIMARY);
    }
    
    /**
     * Helper: Crear tarjetas de estadísticas
     */
    private function createStatsCards($slide, array $stats, int $startY): void
    {
        $cardWidth = 280;
        $cardHeight = 140;
        $spacing = 20;
        $cardsPerRow = 3;
        
        $xPos = 30;
        $yPos = $startY;
        
        foreach ($stats as $index => $stat) {
            if ($index > 0 && $index % $cardsPerRow === 0) {
                $xPos = 30;
                $yPos += $cardHeight + $spacing;
            }
            
            $cardShape = $slide->createAutoShape(AutoShape::TYPE_ROUNDED_RECTANGLE);
            $cardShape->setHeight($cardHeight)
                      ->setWidth($cardWidth)
                      ->setOffsetX($xPos)
                      ->setOffsetY($yPos);
            $cardShape->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB(self::COLOR_WHITE);
            $cardShape->getBorder()
                ->setLineStyle(Border::LINE_SINGLE)
                ->getColor()->setRGB(self::COLOR_GRAY_BORDER);
            $cardShape->getBorder()->setLineWidth(2);
            
            // Contenido de la tarjeta
            $cardText = $slide->createRichTextShape()
                ->setHeight($cardHeight - 20)
                ->setWidth($cardWidth - 20)
                ->setOffsetX($xPos + 10)
                ->setOffsetY($yPos + 10);
            
            $iconRun = $cardText->createTextRun($stat['icon'] . ' ' . $stat['label']);
            $iconRun->getFont()
                ->setSize(16)
                ->setColor($this->colorGrayDark)
                ->setBold(true)
                ->setName(self::FONT_PRIMARY);
            
            $valueRun = $cardText->createTextRun("\n" . $stat['value']);
            $valueRun->getFont()
                ->setSize(40)
                ->setColor($this->colorPrimary)
                ->setBold(true)
                ->setName(self::FONT_PRIMARY);
            
            $xPos += $cardWidth + $spacing;
        }
    }
    
    /**
     * Guardar presentación a archivo temporal
     */
    public function saveToTempFile(): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'ppt_');
        $writer = \PhpOffice\PhpPresentation\IOFactory::createWriter($this->presentation, 'PowerPoint2007');
        $writer->save($tempFile);
        return $tempFile;
    }
}

