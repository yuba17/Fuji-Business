<?php

namespace App\Jobs;

use App\Models\Kpi;
use App\Services\KpiCalculationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\KpiThresholdAlert;

class CheckKpiThresholds implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $kpiId;
    protected $kpiCalculationService;

    /**
     * Create a new job instance.
     */
    public function __construct(?int $kpiId = null)
    {
        $this->kpiId = $kpiId;
        $this->kpiCalculationService = app(KpiCalculationService::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->kpiId) {
            // Verificar un KPI específico
            $kpi = Kpi::find($this->kpiId);
            if ($kpi && $kpi->is_active) {
                $this->checkKpi($kpi);
            }
        } else {
            // Verificar todos los KPIs activos
            $kpis = Kpi::where('is_active', true)->get();
            
            foreach ($kpis as $kpi) {
                $this->checkKpi($kpi);
            }
        }
    }

    /**
     * Verificar umbrales de un KPI específico
     */
    protected function checkKpi(Kpi $kpi): void
    {
        try {
            // Actualizar el estado del KPI basado en umbrales
            $oldStatus = $kpi->status;
            $this->kpiCalculationService->updateStatus($kpi);
            $kpi->refresh();

            // Si el estado cambió a crítico (rojo o amarillo), enviar alerta
            if ($kpi->status !== $oldStatus && in_array($kpi->status, ['red', 'yellow'])) {
                $this->sendAlert($kpi, $oldStatus);
            }

            // Si el KPI ya estaba en estado crítico, verificar si necesita alerta periódica
            if (in_array($kpi->status, ['red', 'yellow'])) {
                $this->checkPeriodicAlert($kpi);
            }
        } catch (\Exception $e) {
            Log::error("Error checking KPI thresholds for KPI {$kpi->id}: " . $e->getMessage());
        }
    }

    /**
     * Enviar alerta cuando el estado cambia a crítico
     */
    protected function sendAlert(Kpi $kpi, ?string $oldStatus): void
    {
        $recipients = $this->getAlertRecipients($kpi);

        if (empty($recipients)) {
            return;
        }

        $severity = $kpi->status === 'red' ? 'critical' : 'warning';
        $message = $this->buildAlertMessage($kpi, $oldStatus, $severity);

        foreach ($recipients as $recipient) {
            try {
                $recipient->notify(new KpiThresholdAlert($kpi, $severity, $message));
            } catch (\Exception $e) {
                Log::error("Error sending KPI alert to user {$recipient->id}: " . $e->getMessage());
            }
        }

        Log::info("KPI threshold alert sent for KPI {$kpi->id} (Status: {$kpi->status})");
    }

    /**
     * Verificar si necesita enviar alerta periódica
     */
    protected function checkPeriodicAlert(Kpi $kpi): void
    {
        // Solo enviar alertas periódicas para KPIs en estado crítico (rojo)
        if ($kpi->status !== 'red') {
            return;
        }

        // Verificar última alerta enviada (almacenada en metadata)
        $metadata = $kpi->metadata ?? [];
        $lastAlertSent = $metadata['last_alert_sent'] ?? null;

        // Enviar alerta periódica cada 7 días si el KPI sigue en estado crítico
        if (!$lastAlertSent || now()->diffInDays($lastAlertSent) >= 7) {
            $recipients = $this->getAlertRecipients($kpi);

            if (!empty($recipients)) {
                $message = $this->buildAlertMessage($kpi, null, 'critical', true);
                
                foreach ($recipients as $recipient) {
                    try {
                        $recipient->notify(new KpiThresholdAlert($kpi, 'critical', $message, true));
                    } catch (\Exception $e) {
                        Log::error("Error sending periodic KPI alert to user {$recipient->id}: " . $e->getMessage());
                    }
                }

                // Actualizar metadata con la fecha de la última alerta
                $metadata['last_alert_sent'] = now()->toDateTimeString();
                $kpi->update(['metadata' => $metadata]);

                Log::info("Periodic KPI threshold alert sent for KPI {$kpi->id}");
            }
        }
    }

    /**
     * Obtener destinatarios de las alertas
     */
    protected function getAlertRecipients(Kpi $kpi): array
    {
        $recipients = [];

        // Agregar responsable del KPI
        if ($kpi->responsible) {
            $recipients[] = $kpi->responsible;
        }

        // Agregar manager del plan
        if ($kpi->plan && $kpi->plan->manager) {
            $recipients[] = $kpi->plan->manager;
        }

        // Agregar director del plan
        if ($kpi->plan && $kpi->plan->director) {
            $recipients[] = $kpi->plan->director;
        }

        // Eliminar duplicados
        return array_unique($recipients, SORT_REGULAR);
    }

    /**
     * Construir mensaje de alerta
     */
    protected function buildAlertMessage(Kpi $kpi, ?string $oldStatus, string $severity, bool $isPeriodic = false): string
    {
        $planName = $kpi->plan ? $kpi->plan->name : 'Sin plan';
        $percentage = $kpi->percentage ?? 0;
        $statusLabel = match($kpi->status) {
            'red' => 'Crítico',
            'yellow' => 'Advertencia',
            'green' => 'Normal',
            default => 'Desconocido',
        };

        if ($isPeriodic) {
            return "El KPI '{$kpi->name}' del plan '{$planName}' sigue en estado crítico. "
                 . "Cumplimiento actual: " . number_format($percentage, 2) . "%. "
                 . "Valor actual: {$kpi->current_value} {$kpi->unit} / Objetivo: {$kpi->target_value} {$kpi->unit}.";
        }

        $statusChange = $oldStatus ? "cambió de '{$oldStatus}' a '{$kpi->status}'" : "está en estado '{$kpi->status}'";
        
        return "El KPI '{$kpi->name}' del plan '{$planName}' {$statusChange}. "
             . "Cumplimiento actual: " . number_format($percentage, 2) . "%. "
             . "Valor actual: {$kpi->current_value} {$kpi->unit} / Objetivo: {$kpi->target_value} {$kpi->unit}.";
    }
}
