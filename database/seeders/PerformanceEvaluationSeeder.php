<?php

namespace Database\Seeders;

use App\Models\PerformanceEvaluation;
use App\Models\User;
use App\Models\CommitteeStructure;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PerformanceEvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang diperlukan
        $users = User::all();
        $structures = CommitteeStructure::all();
        $events = Event::all();

        if ($users->count() < 3) {
            $this->command->warn('Please seed Users first! Need at least 3 users.');
            return;
        }

        // Tentukan evaluator dan approver
        $evaluators = $users->take(2); // 2 evaluator pertama
        $approver = $users->skip(2)->first() ?? $users->first();

        // Users yang akan dievaluasi (skip evaluator dan approver)
        $evaluatedUsers = $users->skip(3)->take(5);

        if ($evaluatedUsers->isEmpty()) {
            $evaluatedUsers = collect([$users->last()]);
        }

        $evaluations = [];
        $evaluationNumber = 1;

        foreach ($evaluatedUsers as $user) {
            // Generate multiple evaluations per user (3-4 evaluations)
            $numberOfEvaluations = rand(3, 4);

            for ($i = 0; $i < $numberOfEvaluations; $i++) {
                $evaluator = $evaluators->random();
                $structure = $structures->isNotEmpty() ? $structures->random() : null;
                $event = $events->isNotEmpty() && rand(0, 1) ? $events->random() : null;

                // Random period type (must match migration enum)
                $periodTypes = ['monthly', 'quarterly', 'event', 'annual'];
                $periodType = $periodTypes[array_rand($periodTypes)];

                // Generate period dates (going back in time for each evaluation)
                $weeksAgo = ($i + 1) * 8; // 8 weeks apart
                $periodEnd = Carbon::now()->subWeeks($weeksAgo);

                $periodStart = match ($periodType) {
                    'monthly' => $periodEnd->copy()->subMonth(),
                    'quarterly' => $periodEnd->copy()->subMonths(3),
                    'annual' => $periodEnd->copy()->subYear(),
                    'event' => $periodEnd->copy()->subWeeks(2),
                };

                // Generate random scores (with some variation but realistic)
                $baseScore = rand(30, 48) / 10; // 3.0 to 4.8
                $variation = 0.5;

                $taskCompletionScore = max(0, min(5, $baseScore + (rand(-5, 5) / 10)));
                $qualityScore = max(0, min(5, $baseScore + (rand(-5, 5) / 10)));
                $teamworkScore = max(0, min(5, $baseScore + (rand(-5, 5) / 10)));
                $initiativeScore = max(0, min(5, $baseScore + (rand(-5, 5) / 10)));
                $leadershipScore = max(0, min(5, $baseScore + (rand(-5, 5) / 10)));
                $disciplineScore = max(0, min(5, $baseScore + (rand(-5, 5) / 10)));

                $overallScore = round(($taskCompletionScore + $qualityScore + $teamworkScore +
                    $initiativeScore + $leadershipScore + $disciplineScore) / 6, 2);

                // Generate task and attendance stats
                $totalTasks = rand(10, 30);
                $tasksCompleted = rand(ceil($totalTasks * 0.7), $totalTasks);
                $totalDays = $periodStart->diffInDays($periodEnd);
                $attendanceDays = rand(ceil($totalDays * 0.8), $totalDays);

                // Determine status (most recent might be draft/submitted)
                // Migration status options: draft, submitted, reviewed, approved, published
                if ($i === 0) {
                    // Most recent evaluation
                    $statusOptions = ['draft', 'submitted', 'approved'];
                    $status = $statusOptions[array_rand($statusOptions)];
                } else {
                    // Older evaluations are mostly approved
                    $status = rand(0, 10) < 9 ? 'approved' : 'submitted';
                }

                $evaluation = [
                    'user_id' => $user->id,
                    'evaluator_id' => $evaluator->id,
                    'structure_id' => $structure?->id,
                    'event_id' => $event?->id,
                    'evaluation_code' => 'EVAL-' . str_pad($evaluationNumber, 4, '0', STR_PAD_LEFT),
                    'period_type' => $periodType,
                    'period_start' => $periodStart,
                    'period_end' => $periodEnd,
                    'task_completion_score' => round($taskCompletionScore, 2),
                    'quality_score' => round($qualityScore, 2),
                    'teamwork_score' => round($teamworkScore, 2),
                    'initiative_score' => round($initiativeScore, 2),
                    'leadership_score' => round($leadershipScore, 2),
                    'discipline_score' => round($disciplineScore, 2),
                    'overall_score' => $overallScore,
                    'strengths' => $this->generateStrengths($overallScore),
                    'weaknesses' => $this->generateWeaknesses($overallScore),
                    'recommendations' => $this->generateRecommendations($overallScore),
                    'achievements' => $this->generateAchievements(),
                    'improvement_areas' => $this->generateImprovementAreas($overallScore),
                    'tasks_completed' => $tasksCompleted,
                    'tasks_assigned' => $totalTasks,
                    'attendance_days' => $attendanceDays,
                    'total_days' => $totalDays,
                    'status' => $status,
                    'evaluator_comments' => $this->generateComments($overallScore),
                    'created_at' => $periodEnd->copy()->addDays(2),
                    'updated_at' => $periodEnd->copy()->addDays(2),
                ];

                // Add submission and approval dates based on status
                if (in_array($status, ['submitted', 'approved'])) {
                    $evaluation['submitted_at'] = $periodEnd->copy()->addDays(3);
                }

                if ($status === 'approved') {
                    $evaluation['approved_by'] = $approver->id;
                    $evaluation['approved_at'] = $periodEnd->copy()->addDays(5);
                }

                $evaluations[] = $evaluation;
                $evaluationNumber++;
            }
        }

        // Insert all evaluations
        foreach ($evaluations as $evaluationData) {
            PerformanceEvaluation::create($evaluationData);
            $this->command->info("Created evaluation: {$evaluationData['evaluation_code']} for user ID {$evaluationData['user_id']}");
        }

        $this->command->info('Performance Evaluations seeded successfully!');
        $this->command->info("Total evaluations created: " . count($evaluations));
    }

    private function generateStrengths($score): string
    {
        $strengths = [
            'Sangat proaktif dalam mengambil inisiatif dan menyelesaikan tugas tepat waktu',
            'Memiliki kemampuan komunikasi yang baik dengan tim dan stakeholder',
            'Konsisten dalam memberikan hasil kerja yang berkualitas tinggi',
            'Mampu bekerja di bawah tekanan dan mengelola prioritas dengan baik',
            'Menunjukkan dedikasi tinggi terhadap pekerjaan dan organisasi',
            'Kreatif dalam mencari solusi untuk masalah yang kompleks',
            'Memiliki skill teknis yang kuat dan terus berkembang',
            'Dapat diandalkan dan bertanggung jawab terhadap tugas yang diberikan',
        ];

        if ($score >= 4.0) {
            return implode('. ', array_slice($strengths, 0, rand(3, 4))) . '.';
        } elseif ($score >= 3.0) {
            return implode('. ', array_slice($strengths, 0, rand(2, 3))) . '.';
        } else {
            return implode('. ', array_slice($strengths, 0, rand(1, 2))) . '.';
        }
    }

    private function generateWeaknesses($score): string
    {
        $weaknesses = [
            'Perlu meningkatkan manajemen waktu untuk beberapa tugas kompleks',
            'Terkadang kurang proaktif dalam berkomunikasi dengan tim',
            'Dapat lebih detail dalam dokumentasi pekerjaan',
            'Perlu lebih sering mengambil inisiatif dalam meeting tim',
            'Manajemen stress perlu ditingkatkan pada situasi deadline ketat',
            'Dapat lebih aktif dalam sharing knowledge dengan tim',
        ];

        if ($score < 3.5) {
            return implode('. ', array_slice($weaknesses, 0, rand(3, 4))) . '.';
        } elseif ($score < 4.0) {
            return implode('. ', array_slice($weaknesses, 0, rand(2, 3))) . '.';
        } else {
            return implode('. ', array_slice($weaknesses, 0, rand(1, 2))) . '.';
        }
    }

    private function generateRecommendations($score): string
    {
        if ($score >= 4.5) {
            return 'Sangat direkomendasikan untuk promosi atau peningkatan tanggung jawab. Dapat menjadi mentor untuk anggota tim lainnya.';
        } elseif ($score >= 4.0) {
            return 'Direkomendasikan untuk program pelatihan leadership dan diberikan project yang lebih challenging untuk pengembangan karir.';
        } elseif ($score >= 3.5) {
            return 'Performa baik dan stabil. Direkomendasikan untuk mengikuti pelatihan soft skills dan time management.';
        } elseif ($score >= 3.0) {
            return 'Perlu pendampingan lebih intensif dan pelatihan untuk meningkatkan skill tertentu. Follow-up evaluasi dalam 2 bulan.';
        } else {
            return 'Membutuhkan performance improvement plan dan monitoring ketat. Review progress setiap bulan.';
        }
    }

    private function generateAchievements(): string
    {
        $achievements = [
            'Berhasil menyelesaikan project utama sebelum deadline',
            'Memberikan kontribusi signifikan dalam meningkatkan proses kerja tim',
            'Menyelesaikan sertifikasi profesional yang relevan dengan pekerjaan',
            'Membantu onboarding anggota tim baru dengan baik',
            'Mencapai target KPI yang ditetapkan dengan konsisten',
            'Mengidentifikasi dan memperbaiki beberapa bottleneck dalam proses kerja',
        ];

        return implode('. ', array_slice($achievements, 0, rand(2, 4))) . '.';
    }

    private function generateImprovementAreas($score): string
    {
        $areas = [
            'Meningkatkan kemampuan presentasi dan public speaking',
            'Mengembangkan skill technical yang lebih advanced',
            'Meningkatkan inisiatif dalam memberikan ide dan saran',
            'Lebih aktif dalam dokumentasi dan knowledge sharing',
            'Meningkatkan kolaborasi cross-functional dengan tim lain',
            'Mengembangkan kemampuan problem solving yang lebih sistematis',
        ];

        if ($score < 4.0) {
            return implode('. ', array_slice($areas, 0, rand(3, 4))) . '.';
        } else {
            return implode('. ', array_slice($areas, 0, rand(1, 2))) . '.';
        }
    }

    private function generateComments($score): string
    {
        if ($score >= 4.5) {
            return 'Performance luar biasa di periode ini. Terus pertahankan dan jadilah role model bagi tim.';
        } elseif ($score >= 4.0) {
            return 'Performance sangat baik. Terus tingkatkan dan kembangkan potensi yang ada.';
        } elseif ($score >= 3.5) {
            return 'Performance baik dan memenuhi ekspektasi. Ada beberapa area yang bisa ditingkatkan lebih lanjut.';
        } elseif ($score >= 3.0) {
            return 'Performance memenuhi standar minimum. Perlu fokus pada improvement di beberapa area.';
        } else {
            return 'Performance di bawah ekspektasi. Diperlukan action plan untuk improvement segera.';
        }
    }
}
