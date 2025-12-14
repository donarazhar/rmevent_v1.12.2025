<?php

namespace Database\Seeders;

use App\Models\Milestone;
use App\Models\Event;
use App\Models\ProjectTimeline;
use App\Models\User;
use App\Models\CommitteeStructure;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MilestoneSeeder extends Seeder
{
    private $globalMilestoneCounter = 1;

    public function run(): void
    {
        // Ambil data yang diperlukan
        $events = Event::all();
        $users = User::all();
        $structures = CommitteeStructure::all();

        if ($events->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Events or Users not found. Please seed them first.');
            return;
        }

        foreach ($events as $event) {
            // Ambil timeline untuk event ini
            $timelines = ProjectTimeline::where('event_id', $event->id)->get();
            
            $this->createMilestonesForEvent($event, $timelines, $users, $structures);
        }

        $this->command->info('Milestones seeded successfully!');
    }

    private function createMilestonesForEvent($event, $timelines, $users, $structures)
    {
        $eventStartDate = Carbon::parse($event->start_date);

        // Milestone 1: Kick-off Meeting
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->first()->id ?? null,
            'name' => 'Kick-off Meeting',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Initial meeting to align all stakeholders and committee members',
            'target_date' => $eventStartDate->copy()->subDays(90),
            'actual_date' => $eventStartDate->copy()->subDays(89),
            'success_criteria' => [
                'All committee members present',
                'Event goals communicated clearly',
                'Timeline approved by all stakeholders',
                'Budget overview presented'
            ],
            'deliverables' => [
                'Meeting minutes',
                'Action item list',
                'Committee member assignment'
            ],
            'progress_percentage' => 100,
            'status' => 'completed',
            'priority' => 'urgent',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->first()->id ?? null,
            'completion_notes' => 'Meeting completed successfully with full attendance',
            'completion_proof' => [
                'documents/meeting-minutes-001.pdf',
                'photos/kickoff-meeting.jpg'
            ],
            'completed_by' => $users->random()->id,
            'completed_at' => $eventStartDate->copy()->subDays(89),
            'is_verified' => true,
            'verified_by' => $users->random()->id,
            'verified_at' => $eventStartDate->copy()->subDays(88),
            'verification_notes' => 'All deliverables submitted and approved',
            'order' => 1,
        ]);

        // Milestone 2: Venue Confirmation
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->skip(1)->first()->id ?? null,
            'name' => 'Venue Confirmation',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Secure and confirm event venue with all necessary facilities',
            'target_date' => $eventStartDate->copy()->subDays(75),
            'actual_date' => $eventStartDate->copy()->subDays(73),
            'success_criteria' => [
                'Venue contract signed',
                'Deposit paid',
                'Capacity meets requirements',
                'All facilities checked and confirmed'
            ],
            'deliverables' => [
                'Signed venue contract',
                'Payment receipt',
                'Venue layout plan',
                'Facility checklist'
            ],
            'progress_percentage' => 100,
            'status' => 'completed',
            'priority' => 'urgent',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->skip(1)->first()->id ?? null,
            'completion_notes' => 'Venue confirmed with 10% discount negotiated',
            'completion_proof' => [
                'contracts/venue-contract.pdf',
                'receipts/venue-deposit.pdf'
            ],
            'completed_by' => $users->random()->id,
            'completed_at' => $eventStartDate->copy()->subDays(73),
            'is_verified' => true,
            'verified_by' => $users->random()->id,
            'verified_at' => $eventStartDate->copy()->subDays(72),
            'order' => 2,
        ]);

        // Milestone 3: Sponsorship Target Achievement
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->skip(2)->first()->id ?? null,
            'name' => 'Sponsorship Target Achievement',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Secure sponsorship commitments to meet budget target',
            'target_date' => $eventStartDate->copy()->subDays(60),
            'actual_date' => null,
            'success_criteria' => [
                'Minimum 5 sponsors confirmed',
                'Total sponsorship value reaches 70% of target',
                'Sponsor agreements signed',
                'Payment schedules confirmed'
            ],
            'deliverables' => [
                'Sponsor list with commitment amounts',
                'Signed sponsorship agreements',
                'Sponsor benefit packages',
                'Marketing materials approval'
            ],
            'progress_percentage' => 65,
            'status' => 'in_progress',
            'priority' => 'high',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->skip(2)->first()->id ?? null,
            'order' => 3,
        ]);

        // Milestone 4: Marketing Campaign Launch
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->skip(3)->first()->id ?? null,
            'name' => 'Marketing Campaign Launch',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Launch comprehensive marketing campaign across all channels',
            'target_date' => $eventStartDate->copy()->subDays(45),
            'actual_date' => null,
            'success_criteria' => [
                'Social media campaign live',
                'Email marketing started',
                'Website updated',
                'Press release distributed',
                'Minimum 1000 initial impressions'
            ],
            'deliverables' => [
                'Campaign creative assets',
                'Social media content calendar',
                'Email templates',
                'Press kit',
                'Analytics dashboard'
            ],
            'progress_percentage' => 40,
            'status' => 'in_progress',
            'priority' => 'high',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->skip(3)->first()->id ?? null,
            'order' => 4,
        ]);

        // Milestone 5: Registration System Go-Live
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->skip(4)->first()->id ?? null,
            'name' => 'Registration System Go-Live',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Launch online registration system for participants',
            'target_date' => $eventStartDate->copy()->subDays(40),
            'actual_date' => null,
            'success_criteria' => [
                'System tested and bug-free',
                'Payment gateway integrated',
                'Confirmation emails working',
                'Mobile responsive',
                'Data privacy compliance verified'
            ],
            'deliverables' => [
                'Live registration URL',
                'User guide/FAQ',
                'Testing report',
                'Payment integration confirmation',
                'Privacy policy document'
            ],
            'progress_percentage' => 85,
            'status' => 'in_progress',
            'priority' => 'urgent',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->skip(4)->first()->id ?? null,
            'order' => 5,
        ]);

        // Milestone 6: Speaker/Performer Confirmation
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->skip(5)->first()->id ?? null,
            'name' => 'Speaker/Performer Confirmation',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Confirm all speakers/performers and finalize program',
            'target_date' => $eventStartDate->copy()->subDays(30),
            'actual_date' => null,
            'success_criteria' => [
                'All speakers/performers confirmed',
                'Contracts signed',
                'Technical requirements documented',
                'Schedule finalized',
                'Backup options identified'
            ],
            'deliverables' => [
                'Confirmed speaker/performer list',
                'Signed contracts',
                'Technical rider documents',
                'Event program/rundown',
                'Speaker bio and photos'
            ],
            'progress_percentage' => 20,
            'status' => 'pending',
            'priority' => 'high',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->random()->id ?? null,
            'order' => 6,
        ]);

        // Milestone 7: Vendor Finalization
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->skip(6)->first()->id ?? null,
            'name' => 'Vendor Finalization',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Confirm all vendors for catering, AV, decoration, etc.',
            'target_date' => $eventStartDate->copy()->subDays(21),
            'actual_date' => null,
            'success_criteria' => [
                'Catering vendor confirmed',
                'AV equipment vendor secured',
                'Decoration team finalized',
                'All contracts signed',
                'Delivery schedules confirmed'
            ],
            'deliverables' => [
                'Vendor contract package',
                'Menu/service specifications',
                'Equipment list',
                'Delivery timeline',
                'Emergency contact list'
            ],
            'progress_percentage' => 0,
            'status' => 'pending',
            'priority' => 'medium',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->random()->id ?? null,
            'order' => 7,
        ]);

        // Milestone 8: Final Rehearsal
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->skip(7)->first()->id ?? null,
            'name' => 'Final Rehearsal',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Conduct full rehearsal with all elements',
            'target_date' => $eventStartDate->copy()->subDays(3),
            'actual_date' => null,
            'success_criteria' => [
                'Full run-through completed',
                'All technical elements tested',
                'Timing confirmed',
                'Issues identified and resolved',
                'Contingency plans reviewed'
            ],
            'deliverables' => [
                'Rehearsal checklist',
                'Technical test results',
                'Issue log and resolution',
                'Updated rundown',
                'Team briefing notes'
            ],
            'progress_percentage' => 0,
            'status' => 'pending',
            'priority' => 'urgent',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->random()->id ?? null,
            'order' => 8,
        ]);

        // Milestone 9: Event Execution
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->last()->id ?? null,
            'name' => 'Event Execution',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Successful execution of the main event',
            'target_date' => $eventStartDate,
            'actual_date' => null,
            'success_criteria' => [
                'Event starts on time',
                'All program items executed',
                'No major incidents',
                'Participant satisfaction >80%',
                'All deliverables completed'
            ],
            'deliverables' => [
                'Event documentation',
                'Photo/video coverage',
                'Attendance records',
                'Incident reports (if any)',
                'Real-time monitoring logs'
            ],
            'progress_percentage' => 0,
            'status' => 'pending',
            'priority' => 'urgent',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->first()->id ?? null,
            'order' => 9,
        ]);

        // Milestone 10: Post-Event Report
        Milestone::create([
            'event_id' => $event->id,
            'timeline_id' => $timelines->last()->id ?? null,
            'name' => 'Post-Event Report & Evaluation',
            'code' => $this->generateMilestoneCode(),
            'description' => 'Complete comprehensive post-event report and evaluation',
            'target_date' => $eventStartDate->copy()->addDays(7),
            'actual_date' => null,
            'success_criteria' => [
                'Financial report completed',
                'Participant feedback collected',
                'Team debrief conducted',
                'Lessons learned documented',
                'Final report approved'
            ],
            'deliverables' => [
                'Final financial report',
                'Participant survey results',
                'Event success metrics',
                'Lessons learned document',
                'Recommendation for next event',
                'Media coverage report'
            ],
            'progress_percentage' => 0,
            'status' => 'pending',
            'priority' => 'medium',
            'responsible_person' => $users->random()->id,
            'structure_id' => $structures->first()->id ?? null,
            'order' => 10,
        ]);
    }

    /**
     * Generate unique milestone code
     */
    private function generateMilestoneCode(): string
    {
        $code = 'MLS-' . str_pad($this->globalMilestoneCounter, 3, '0', STR_PAD_LEFT);
        $this->globalMilestoneCounter++;
        return $code;
    }
}