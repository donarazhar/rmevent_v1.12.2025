<?php

namespace Database\Seeders;

use App\Models\EventRegistration;
use App\Models\Expense;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            SliderSeeder::class,
            FAQSeeder::class,
            PostSeeder::class,
            EventSeeder::class,
            EventRegistrationSeeder::class,
            FeedbackSeeder::class,
            CommitteeSeeder::class,
            JobDescriptionSeeder::class,
            PerformanceEvaluationSeeder::class,
            ProjectTimelineSeeder::class,
            MilestoneSeeder::class,
            ProgressReportSeeder::class,
            BudgetSeeder::class,
            BudgetItemSeeder::class,
            BudgetAllocationSeeder::class,
            SponsorshipSeeder::class,
            IncomeSeeder::class,
            ExpenseSeeder::class,
            SOPSeeder::class,
            WorkInstructionSeeder::class,
            TemplateSeeder::class,
            DocumentSeeder::class,
            ProposalSeeder::class,
            MeetingMinuteSeeder::class,
            ContractSeeder::class,
            OfficialLetterSeeder::class,
        ]);
    }
}
