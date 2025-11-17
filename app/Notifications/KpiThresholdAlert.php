<?php

namespace App\Notifications;

use App\Models\Kpi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KpiThresholdAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected Kpi $kpi;
    protected string $severity;
    protected string $message;
    protected bool $isPeriodic;

    /**
     * Create a new notification instance.
     */
    public function __construct(Kpi $kpi, string $severity, string $message, bool $isPeriodic = false)
    {
        $this->kpi = $kpi;
        $this->severity = $severity;
        $this->message = $message;
        $this->isPeriodic = $isPeriodic;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Por defecto, usar base de datos y correo
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $planName = $this->kpi->plan ? $this->kpi->plan->name : 'Sin plan';
        $planUrl = $this->kpi->plan ? route('plans.show', $this->kpi->plan) : route('plans.index');
        
        $subject = $this->isPeriodic 
            ? "⚠️ Alerta Periódica: KPI '{$this->kpi->name}' en estado crítico"
            : "⚠️ Alerta de KPI: '{$this->kpi->name}' cambió de estado";

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line($this->message)
            ->line("**Plan:** {$planName}")
            ->line("**KPI:** {$this->kpi->name}")
            ->line("**Estado:** " . strtoupper($this->kpi->status))
            ->line("**Valor Actual:** {$this->kpi->current_value} {$this->kpi->unit}")
            ->line("**Objetivo:** {$this->kpi->target_value} {$this->kpi->unit}")
            ->line("**Cumplimiento:** " . number_format($this->kpi->percentage ?? 0, 2) . "%")
            ->action('Ver Plan', $planUrl)
            ->line('Por favor, revisa este KPI y toma las acciones necesarias.');

        if ($this->severity === 'critical') {
            $mailMessage->error();
        } else {
            $mailMessage->warning();
        }

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'kpi_threshold_alert',
            'kpi_id' => $this->kpi->id,
            'kpi_name' => $this->kpi->name,
            'plan_id' => $this->kpi->plan_id,
            'plan_name' => $this->kpi->plan ? $this->kpi->plan->name : null,
            'severity' => $this->severity,
            'status' => $this->kpi->status,
            'message' => $this->message,
            'is_periodic' => $this->isPeriodic,
            'current_value' => $this->kpi->current_value,
            'target_value' => $this->kpi->target_value,
            'percentage' => $this->kpi->percentage,
            'unit' => $this->kpi->unit,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
