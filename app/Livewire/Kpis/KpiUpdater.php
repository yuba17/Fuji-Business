<?php

namespace App\Livewire\Kpis;

use App\Models\Kpi;
use App\Models\KpiHistory;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class KpiUpdater extends Component
{
    public Kpi $kpi;
    public $newValue;
    public $notes;
    public $showModal = false;

    protected $rules = [
        'newValue' => 'required|numeric',
        'notes' => 'nullable|string|max:500',
    ];

    public function mount(Kpi $kpi)
    {
        $this->kpi = $kpi;
        $this->newValue = $kpi->current_value;
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->newValue = $this->kpi->current_value;
        $this->notes = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['newValue', 'notes']);
    }

    public function updateValue()
    {
        $this->validate();

        if (!Auth::user()->can('update', $this->kpi)) {
            session()->flash('error', 'No tienes permisos para actualizar este KPI');
            return;
        }

        $oldValue = $this->kpi->current_value;
        
        // Actualizar valor del KPI
        $this->kpi->update([
            'current_value' => $this->newValue,
            'last_updated_at' => now(),
        ]);

        // Actualizar estado basado en umbrales
        $this->kpi->updateStatus();

        // Crear entrada en el historial
        KpiHistory::create([
            'kpi_id' => $this->kpi->id,
            'value' => $this->newValue,
            'recorded_at' => now(),
            'updated_by' => Auth::id(),
            'notes' => $this->notes,
        ]);

        $this->kpi->refresh();
        $this->closeModal();
        
        session()->flash('success', 'KPI actualizado correctamente');
        $this->dispatch('kpiUpdated');
    }

    public function render()
    {
        return view('livewire.kpis.kpi-updater');
    }
}
