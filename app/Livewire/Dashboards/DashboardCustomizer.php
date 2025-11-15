<?php

namespace App\Livewire\Dashboards;

use App\Models\Dashboard;
use App\Models\DashboardWidget;
use Livewire\Component;

class DashboardCustomizer extends Component
{
    public $dashboardId = null;
    public $dashboardName = '';
    public $dashboardDescription = '';
    public $availableWidgets = [];
    public $currentWidgets = [];
    public $editingWidget = null;
    public $showAddWidgetModal = false;

    protected $listeners = ['widgetUpdated' => 'loadWidgets'];

    public function mount($dashboardId = null)
    {
        $this->availableWidgets = [
            'kpi_summary' => ['name' => 'Resumen de KPIs', 'icon' => 'fa-chart-line', 'default_width' => 4, 'default_height' => 3],
            'plan_status' => ['name' => 'Estado de Planes', 'icon' => 'fa-clipboard-list', 'default_width' => 4, 'default_height' => 3],
            'risk_heatmap' => ['name' => 'Mapa de Calor de Riesgos', 'icon' => 'fa-fire', 'default_width' => 6, 'default_height' => 4],
            'roadmap_timeline' => ['name' => 'Línea de Tiempo del Roadmap', 'icon' => 'fa-road', 'default_width' => 6, 'default_height' => 4],
            'recent_decisions' => ['name' => 'Decisiones Recientes', 'icon' => 'fa-gavel', 'default_width' => 4, 'default_height' => 3],
            'team_workload' => ['name' => 'Carga del Equipo', 'icon' => 'fa-users', 'default_width' => 4, 'default_height' => 3],
        ];

        if ($dashboardId) {
            $this->dashboardId = $dashboardId;
            $this->loadDashboard();
        } else {
            // Crear un nuevo dashboard personalizado
            $this->createNewDashboard();
        }
    }

    public function createNewDashboard()
    {
        $dashboard = Dashboard::create([
            'name' => 'Mi Dashboard Personalizado',
            'description' => '',
            'user_id' => auth()->id(),
            'type' => 'custom',
            'is_default' => false,
        ]);

        $this->dashboardId = $dashboard->id;
        $this->dashboardName = $dashboard->name;
        $this->dashboardDescription = $dashboard->description ?? '';
        $this->loadWidgets();
    }

    public function loadDashboard()
    {
        $dashboard = Dashboard::findOrFail($this->dashboardId);
        
        if ($dashboard->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para editar este dashboard');
        }

        $this->dashboardName = $dashboard->name;
        $this->dashboardDescription = $dashboard->description ?? '';
        $this->loadWidgets();
    }

    public function loadWidgets()
    {
        if (!$this->dashboardId) {
            return;
        }

        $this->currentWidgets = DashboardWidget::where('dashboard_id', $this->dashboardId)
            ->orderBy('order')
            ->get()
            ->map(function ($widget) {
                return [
                    'id' => $widget->id,
                    'type' => $widget->widget_type,
                    'title' => $widget->title,
                    'width' => $widget->width,
                    'height' => $widget->height,
                    'order' => $widget->order,
                    'is_visible' => $widget->is_visible,
                    'config' => $widget->config ?? [],
                ];
            })
            ->toArray();
    }

    public function saveDashboard()
    {
        if (!$this->dashboardId) {
            return;
        }

        $dashboard = Dashboard::findOrFail($this->dashboardId);
        $dashboard->update([
            'name' => $this->dashboardName,
            'description' => $this->dashboardDescription,
        ]);

        session()->flash('success', 'Dashboard guardado correctamente');
    }

    public function addWidget($widgetType)
    {
        if (!$this->dashboardId || !isset($this->availableWidgets[$widgetType])) {
            return;
        }

        $widgetInfo = $this->availableWidgets[$widgetType];
        $maxOrder = DashboardWidget::where('dashboard_id', $this->dashboardId)->max('order') ?? 0;

        DashboardWidget::create([
            'dashboard_id' => $this->dashboardId,
            'widget_type' => $widgetType,
            'title' => $widgetInfo['name'],
            'width' => $widgetInfo['default_width'],
            'height' => $widgetInfo['default_height'],
            'order' => $maxOrder + 1,
            'is_visible' => true,
            'config' => [],
        ]);

        $this->loadWidgets();
        $this->showAddWidgetModal = false;
        session()->flash('success', 'Widget agregado correctamente');
    }

    public function removeWidget($widgetId)
    {
        $widget = DashboardWidget::findOrFail($widgetId);
        if ($widget->dashboard->user_id !== auth()->id()) {
            abort(403);
        }
        $widget->delete();
        $this->loadWidgets();
        session()->flash('success', 'Widget eliminado correctamente');
    }

    public function updateWidgetOrder($widgetIds)
    {
        foreach ($widgetIds as $index => $widgetId) {
            DashboardWidget::where('id', $widgetId)
                ->where('dashboard_id', $this->dashboardId)
                ->update(['order' => $index + 1]);
        }
        $this->loadWidgets();
    }

    public function toggleWidgetVisibility($widgetId)
    {
        $widget = DashboardWidget::findOrFail($widgetId);
        if ($widget->dashboard->user_id !== auth()->id()) {
            abort(403);
        }
        $widget->update(['is_visible' => !$widget->is_visible]);
        $this->loadWidgets();
    }

    public function render()
    {
        return view('livewire.dashboards.dashboard-customizer');
    }
}
