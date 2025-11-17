<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\PlanType;
use App\Models\PlanSection;

class PlanTemplateService
{
    /**
     * Crear secciones de plan desde un template
     */
    public function createSectionsFromTemplate(Plan $plan, PlanType $planType): void
    {
        $templateSections = $planType->template_sections ?? [];

        if (empty($templateSections)) {
            return;
        }

        foreach ($templateSections as $sectionData) {
            PlanSection::create([
                'plan_id' => $plan->id,
                'title' => $sectionData['title'] ?? '',
                'slug' => $sectionData['slug'] ?? \Illuminate\Support\Str::slug($sectionData['title'] ?? ''),
                'content' => $sectionData['content'] ?? '',
                'order' => $sectionData['order'] ?? 0,
                'is_required' => $sectionData['is_required'] ?? false,
                'type' => 'text',
            ]);
        }
    }

    /**
     * Obtener preview de secciones de un template
     */
    public function getTemplatePreview(PlanType $planType): array
    {
        return $planType->template_sections ?? [];
    }

    /**
     * Verificar si un plan type tiene template
     */
    public function hasTemplate(PlanType $planType): bool
    {
        $sections = $planType->template_sections ?? [];
        return !empty($sections);
    }
}

