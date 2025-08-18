<?php

namespace App\Services;

use App\Models\Audit;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\Question;
use App\Models\AuditChecklist;
use Illuminate\Support\Facades\DB;

class ChecklistGeneratorService
{
    /**
     * Generate a dynamic checklist for an audit
     */
    public function generateChecklist(Audit $audit, string $departmentName, int $generatedBy): AuditChecklist
    {
        return DB::transaction(function () use ($audit, $departmentName, $generatedBy) {
            // Create or get the checklist
            $checklist = $this->createOrGetChecklist($audit, $departmentName);
            
            // Create audit checklist record
            $auditChecklist = AuditChecklist::create([
                'audit_id' => $audit->id,
                'checklist_id' => $checklist->id,
                'generated_by' => $generatedBy,
                'department_name' => $departmentName,
                'status' => 'generated',
            ]);
            
            // Generate checklist items from questions
            $this->generateChecklistItems($checklist, $departmentName);
            
            return $auditChecklist;
        });
    }

    /**
     * Create or get existing checklist for audit
     */
    private function createOrGetChecklist(Audit $audit, string $departmentName): Checklist
    {
        $checklistTitle = "Audit Checklist - {$audit->site_name} - {$departmentName}";
        $checklistSlug = "audit-{$audit->id}-{$departmentName}";
        
        return Checklist::firstOrCreate(
            ['slug' => $checklistSlug],
            [
                'title' => $checklistTitle,
                'slug' => $checklistSlug,
                'description' => "Dynamic checklist generated for audit: {$audit->site_name} - {$departmentName}",
            ]
        );
    }

    /**
     * Generate checklist items from questions
     */
    private function generateChecklistItems(Checklist $checklist, string $departmentName): void
    {
        $questions = Question::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        $items = [];
        $displayOrder = 1;

        foreach ($questions as $question) {
            $items[] = [
                'checklist_id' => $checklist->id,
                'question_id' => $question->id,
                'section' => $question->section,
                'question_text' => $question->question_text,
                'custom_text' => $question->question_text,
                'question_type' => $question->question_type,
                'options' => $question->options,
                'help_text' => $question->help_text,
                'display_order' => $displayOrder++,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ChecklistItem::insert($items);
    }

    /**
     * Regenerate checklist items for customization
     */
    public function regenerateChecklistItems(Checklist $checklist): void
    {
        // Delete existing items
        $checklist->items()->delete();
        
        // Regenerate from questions
        $this->generateChecklistItems($checklist, $checklist->title);
    }

    /**
     * Get checklist items for customization
     */
    public function getChecklistItemsForCustomization(Checklist $checklist): array
    {
        return $checklist->items()
            ->with('question')
            ->orderBy('display_order')
            ->get()
            ->toArray();
    }

    /**
     * Update checklist item customization
     */
    public function updateChecklistItemCustomization(int $itemId, array $data): void
    {
        ChecklistItem::where('id', $itemId)->update([
            'custom_text' => $data['custom_text'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'updated_at' => now(),
        ]);
    }

    /**
     * Generate checklist from audit data
     */
    public function generateFromAudit(Audit $audit, string $departmentName, int $generatedBy): AuditChecklist
    {
        return $this->generateChecklist($audit, $departmentName, $generatedBy);
    }

    /**
     * Complete checklist generation
     */
    public function completeChecklist(AuditChecklist $auditChecklist): void
    {
        $auditChecklist->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Get checklist statistics
     */
    public function getChecklistStats(AuditChecklist $auditChecklist): array
    {
        $items = $auditChecklist->checklist->items;
        
        return [
            'total_items' => $items->count(),
            'completed_items' => $items->where('status', 'completed')->count(),
            'pending_items' => $items->where('status', 'pending')->count(),
            'in_progress_items' => $items->where('status', 'in_progress')->count(),
            'not_applicable_items' => $items->where('status', 'not_applicable')->count(),
        ];
    }
}
