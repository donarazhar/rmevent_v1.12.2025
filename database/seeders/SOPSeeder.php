<?php

namespace Database\Seeders;

use App\Models\SOP;
use App\Models\User;
use Illuminate\Database\Seeder;

class SOPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for assignment
        $users = User::limit(5)->get();

        if ($users->count() < 1) {
            $this->command->warn('⚠️  No users found. Please run UserSeeder first.');
            return;
        }

        $creator = $users->first();
        $reviewer = $users->count() > 1 ? $users[1] : $creator;
        $approver = $users->count() > 2 ? $users[2] : $creator;

        $sops = [
            // 1. Published SOP - Event Registration
            [
                'sop_code' => 'SOP-001',
                'title' => 'Event Registration Procedure',
                'purpose' => 'To standardize the process of registering participants for events and ensure all necessary information is collected accurately.',
                'scope' => 'Applies to all event registrations conducted by the organization.',
                'category' => 'event_management',
                'content' => '<h2>Overview</h2><p>This SOP outlines the standard procedure for handling event registrations from initial submission to confirmation.</p>
                    <h2>Requirements</h2><ul>
                    <li>Valid identification document</li>
                    <li>Complete registration form</li>
                    <li>Payment confirmation (if applicable)</li>
                    </ul>
                    <h2>Process</h2><p>All registrations must be processed within 24 hours of submission. Confirmation emails should be sent immediately upon approval.</p>',
                'procedures' => [
                    ['step' => 1, 'description' => 'Receive registration form from participant', 'notes' => 'Verify all required fields are completed'],
                    ['step' => 2, 'description' => 'Validate participant information', 'notes' => 'Check for duplicate registrations'],
                    ['step' => 3, 'description' => 'Process payment (if required)', 'notes' => 'Match payment reference with registration'],
                    ['step' => 4, 'description' => 'Generate registration confirmation', 'notes' => 'Include QR code and event details'],
                    ['step' => 5, 'description' => 'Send confirmation email to participant', 'notes' => 'CC to event coordinator'],
                ],
                'responsibilities' => [
                    ['role' => 'Registration Officer', 'tasks' => 'Process incoming registrations and validate information'],
                    ['role' => 'Finance Officer', 'tasks' => 'Verify payment confirmations'],
                    ['role' => 'Event Coordinator', 'tasks' => 'Final approval and monitoring'],
                ],
                'related_forms' => [
                    ['name' => 'Event Registration Form', 'reference' => 'FRM-001'],
                    ['name' => 'Payment Confirmation Form', 'reference' => 'FRM-002'],
                ],
                'related_templates' => [
                    ['name' => 'Confirmation Email Template', 'reference' => 'TPL-001'],
                    ['name' => 'Registration Certificate', 'reference' => 'TPL-002'],
                ],
                'version' => '1.0',
                'effective_date' => now()->subMonths(6),
                'review_date' => now()->addMonths(6),
                'expiry_date' => now()->addYear(),
                'status' => 'published',
                'created_by' => $creator->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subMonths(6)->addDays(2),
                'approved_by' => $approver->id,
                'approved_at' => now()->subMonths(6)->addDays(3),
                'notes' => 'Version 1.0 - Initial release',
                'view_count' => rand(50, 200),
                'download_count' => rand(20, 80),
            ],

            // 2. Published SOP - Financial Disbursement
            [
                'sop_code' => 'SOP-002',
                'title' => 'Financial Disbursement Procedure',
                'purpose' => 'To establish a standardized process for requesting, approving, and disbursing funds to ensure financial accountability.',
                'scope' => 'Covers all financial disbursements for event operations, procurement, and petty cash.',
                'category' => 'finance',
                'content' => '<h2>Introduction</h2><p>This procedure ensures all financial disbursements follow proper authorization and documentation requirements.</p>
                    <h2>Authorization Limits</h2><ul>
                    <li>Up to Rp 1,000,000: Department Head approval</li>
                    <li>Rp 1,000,001 - Rp 5,000,000: Finance Director approval</li>
                    <li>Above Rp 5,000,000: Board approval required</li>
                    </ul>',
                'procedures' => [
                    ['step' => 1, 'description' => 'Submit disbursement request with supporting documents', 'notes' => 'Use form FIN-001'],
                    ['step' => 2, 'description' => 'Finance team reviews request and budget availability', 'notes' => 'Verify against approved budget'],
                    ['step' => 3, 'description' => 'Obtain required approvals based on amount', 'notes' => 'Follow authorization matrix'],
                    ['step' => 4, 'description' => 'Process payment through approved channels', 'notes' => 'Bank transfer, check, or petty cash'],
                    ['step' => 5, 'description' => 'Update financial records and notify requester', 'notes' => 'Record in accounting system'],
                ],
                'responsibilities' => [
                    ['role' => 'Requester', 'tasks' => 'Submit complete disbursement request with justification and supporting documents'],
                    ['role' => 'Finance Officer', 'tasks' => 'Review requests, verify budget availability, process approved payments'],
                    ['role' => 'Finance Director', 'tasks' => 'Approve disbursements within authority limit and ensure policy compliance'],
                ],
                'related_forms' => [
                    ['name' => 'Disbursement Request Form', 'reference' => 'FIN-001'],
                    ['name' => 'Payment Voucher', 'reference' => 'FIN-002'],
                ],
                'related_templates' => [
                    ['name' => 'Payment Confirmation Letter', 'reference' => 'FIN-TPL-001'],
                ],
                'version' => '2.0',
                'effective_date' => now()->subMonths(3),
                'review_date' => now()->addMonths(9),
                'status' => 'published',
                'created_by' => $creator->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subMonths(3)->addDays(1),
                'approved_by' => $approver->id,
                'approved_at' => now()->subMonths(3)->addDays(2),
                'notes' => 'Version 2.0 - Updated authorization limits and added online payment options',
                'view_count' => rand(100, 300),
                'download_count' => rand(40, 120),
            ],

            // 3. Under Review SOP - Emergency Response
            [
                'sop_code' => 'SOP-003',
                'title' => 'Emergency Response Protocol',
                'purpose' => 'To provide clear guidelines for responding to emergency situations during events to ensure participant safety.',
                'scope' => 'Applies to all events organized by the committee, covering medical emergencies, fire, natural disasters, and security threats.',
                'category' => 'emergency',
                'content' => '<h2>Emergency Types</h2><p>This SOP covers responses to:</p><ul>
                    <li>Medical emergencies</li>
                    <li>Fire incidents</li>
                    <li>Natural disasters (earthquake, flood)</li>
                    <li>Security threats</li>
                    </ul>
                    <h2>Response Priorities</h2><ol>
                    <li>Ensure participant safety</li>
                    <li>Contact emergency services</li>
                    <li>Evacuate if necessary</li>
                    <li>Provide first aid</li>
                    <li>Document incident</li>
                    </ol>',
                'procedures' => [
                    ['step' => 1, 'description' => 'Identify and assess the emergency situation', 'notes' => 'Stay calm and evaluate the severity'],
                    ['step' => 2, 'description' => 'Alert emergency response team and call emergency services (118/119)', 'notes' => 'Provide clear location and situation details'],
                    ['step' => 3, 'description' => 'Initiate evacuation procedures if required', 'notes' => 'Follow designated evacuation routes'],
                    ['step' => 4, 'description' => 'Provide first aid while waiting for emergency services', 'notes' => 'Only if trained and it is safe to do so'],
                    ['step' => 5, 'description' => 'Document the incident and submit report', 'notes' => 'Complete within 24 hours'],
                ],
                'responsibilities' => [
                    ['role' => 'Event Safety Officer', 'tasks' => 'Coordinate emergency response, liaise with emergency services, ensure evacuation procedures are followed'],
                    ['role' => 'First Aid Team', 'tasks' => 'Provide immediate medical assistance, maintain first aid supplies'],
                    ['role' => 'Security Team', 'tasks' => 'Secure the area, control crowd, assist with evacuation'],
                    ['role' => 'Event Coordinator', 'tasks' => 'Notify stakeholders, document incident, handle media inquiries'],
                ],
                'related_forms' => [
                    ['name' => 'Incident Report Form', 'reference' => 'EMG-001'],
                    ['name' => 'Medical Emergency Log', 'reference' => 'EMG-002'],
                ],
                'version' => '1.0',
                'effective_date' => now()->addWeek(),
                'review_date' => now()->addMonths(6),
                'status' => 'under_review',
                'created_by' => $creator->id,
                'notes' => 'Submitted for review - waiting for safety committee approval',
                'view_count' => rand(10, 30),
                'download_count' => rand(2, 10),
            ],

            // 4. Draft SOP - Documentation Management
            [
                'sop_code' => 'SOP-004',
                'title' => 'Event Documentation Management',
                'purpose' => 'To establish standards for creating, storing, and managing event documentation.',
                'scope' => 'Covers all documentation related to event planning, execution, and post-event reporting.',
                'category' => 'documentation',
                'content' => '<h2>Document Types</h2><p>This SOP covers management of:</p><ul>
                    <li>Planning documents (proposals, budgets, timelines)</li>
                    <li>Operational documents (rundowns, checklists, forms)</li>
                    <li>Reporting documents (attendance, financials, evaluations)</li>
                    <li>Media files (photos, videos, recordings)</li>
                    </ul>',
                'procedures' => [
                    ['step' => 1, 'description' => 'Create documents using approved templates', 'notes' => 'Follow naming conventions'],
                    ['step' => 2, 'description' => 'Store documents in designated shared drive folders', 'notes' => 'Organize by event and document type'],
                    ['step' => 3, 'description' => 'Set appropriate access permissions', 'notes' => 'Limit access based on roles'],
                ],
                'responsibilities' => [
                    ['role' => 'Documentation Coordinator', 'tasks' => 'Maintain document repository, ensure compliance with standards'],
                    ['role' => 'Department Heads', 'tasks' => 'Ensure team members follow documentation procedures'],
                ],
                'version' => '1.0',
                'effective_date' => now()->addMonths(2),
                'status' => 'draft',
                'created_by' => $creator->id,
                'notes' => 'Draft in progress - need to finalize storage structure',
                'view_count' => rand(5, 15),
                'download_count' => rand(1, 5),
            ],

            // 5. Approved SOP - Volunteer Management
            [
                'sop_code' => 'SOP-005',
                'title' => 'Volunteer Recruitment and Management',
                'purpose' => 'To standardize the process of recruiting, training, and managing event volunteers.',
                'scope' => 'Applies to all volunteer programs associated with committee events.',
                'category' => 'general',
                'content' => '<h2>Volunteer Lifecycle</h2><p>This SOP covers the complete volunteer management process from recruitment to appreciation.</p>
                    <h2>Key Principles</h2><ul>
                    <li>Clear role descriptions</li>
                    <li>Proper training and orientation</li>
                    <li>Recognition and appreciation</li>
                    <li>Ongoing communication</li>
                    </ul>',
                'procedures' => [
                    ['step' => 1, 'description' => 'Define volunteer roles and requirements', 'notes' => 'Create detailed role descriptions'],
                    ['step' => 2, 'description' => 'Announce volunteer opportunities', 'notes' => 'Use multiple channels (social media, email, website)'],
                    ['step' => 3, 'description' => 'Screen and select volunteers', 'notes' => 'Review applications and conduct interviews if needed'],
                    ['step' => 4, 'description' => 'Conduct orientation and training sessions', 'notes' => 'Cover roles, responsibilities, and safety'],
                    ['step' => 5, 'description' => 'Coordinate volunteer activities during events', 'notes' => 'Provide clear instructions and support'],
                    ['step' => 6, 'description' => 'Recognize and appreciate volunteer contributions', 'notes' => 'Certificates, thank you notes, appreciation events'],
                ],
                'responsibilities' => [
                    ['role' => 'Volunteer Coordinator', 'tasks' => 'Manage recruitment process, coordinate training, maintain volunteer database'],
                    ['role' => 'Department Leads', 'tasks' => 'Define volunteer needs, supervise volunteers during events'],
                ],
                'related_forms' => [
                    ['name' => 'Volunteer Application Form', 'reference' => 'VOL-001'],
                    ['name' => 'Volunteer Attendance Sheet', 'reference' => 'VOL-002'],
                ],
                'version' => '1.0',
                'effective_date' => now()->addDays(7),
                'status' => 'approved',
                'created_by' => $creator->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subDays(2),
                'approved_by' => $approver->id,
                'approved_at' => now()->subDay(),
                'notes' => 'Approved - ready for publication',
                'view_count' => rand(20, 50),
                'download_count' => rand(5, 15),
            ],

            // 6. Published SOP - Sponsorship Management
            [
                'sop_code' => 'SOP-006',
                'title' => 'Sponsorship Acquisition and Management',
                'purpose' => 'To establish a systematic approach to identifying, approaching, and managing event sponsors.',
                'scope' => 'Covers the entire sponsorship lifecycle from prospecting to fulfillment.',
                'category' => 'finance',
                'content' => '<h2>Sponsorship Tiers</h2><p>We offer multiple sponsorship levels:</p><ul>
                    <li>Platinum: Rp 50,000,000+</li>
                    <li>Gold: Rp 25,000,000 - Rp 49,999,999</li>
                    <li>Silver: Rp 10,000,000 - Rp 24,999,999</li>
                    <li>Bronze: Rp 5,000,000 - Rp 9,999,999</li>
                    </ul>',
                'procedures' => [
                    ['step' => 1, 'description' => 'Identify potential sponsors aligned with event objectives', 'notes' => 'Research company CSR programs'],
                    ['step' => 2, 'description' => 'Prepare sponsorship proposal package', 'notes' => 'Include sponsorship tiers and benefits'],
                    ['step' => 3, 'description' => 'Reach out and present proposal to prospects', 'notes' => 'Schedule meetings with decision makers'],
                    ['step' => 4, 'description' => 'Negotiate terms and finalize sponsorship agreement', 'notes' => 'Have legal review contracts'],
                    ['step' => 5, 'description' => 'Fulfill sponsorship obligations during and after event', 'notes' => 'Track deliverables and report to sponsors'],
                ],
                'responsibilities' => [
                    ['role' => 'Sponsorship Manager', 'tasks' => 'Lead sponsor acquisition, negotiate agreements, maintain relationships'],
                    ['role' => 'Marketing Team', 'tasks' => 'Fulfill sponsor visibility and branding commitments'],
                    ['role' => 'Finance Team', 'tasks' => 'Process sponsor payments, issue receipts'],
                ],
                'related_forms' => [
                    ['name' => 'Sponsorship Agreement Template', 'reference' => 'SPON-001'],
                    ['name' => 'Sponsor Benefit Tracking Form', 'reference' => 'SPON-002'],
                ],
                'version' => '1.0',
                'effective_date' => now()->subMonths(4),
                'review_date' => now()->addMonths(8),
                'status' => 'published',
                'created_by' => $creator->id,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now()->subMonths(4)->addDays(1),
                'approved_by' => $approver->id,
                'approved_at' => now()->subMonths(4)->addDays(2),
                'view_count' => rand(60, 150),
                'download_count' => rand(25, 70),
            ],

            // 7. Draft SOP - Social Media Management
            [
                'sop_code' => 'SOP-007',
                'title' => 'Social Media Content Management',
                'purpose' => 'To ensure consistent, professional, and engaging social media presence for committee events.',
                'scope' => 'Covers content creation, posting schedule, and engagement practices across all official social media channels.',
                'category' => 'general',
                'content' => '<h2>Social Media Channels</h2><p>Official channels include:</p><ul>
                    <li>Instagram: @committee_official</li>
                    <li>Facebook: Committee Official Page</li>
                    <li>Twitter: @CommitteeHQ</li>
                    <li>YouTube: Committee Channel</li>
                    </ul>',
                'procedures' => [
                    ['step' => 1, 'description' => 'Plan content calendar for the month', 'notes' => 'Align with event timeline'],
                    ['step' => 2, 'description' => 'Create content following brand guidelines', 'notes' => 'Use approved templates and hashtags'],
                    ['step' => 3, 'description' => 'Get approval from social media manager', 'notes' => 'Allow 24 hours for review'],
                ],
                'responsibilities' => [
                    ['role' => 'Social Media Manager', 'tasks' => 'Oversee content strategy, approve posts, monitor engagement'],
                    ['role' => 'Content Creators', 'tasks' => 'Create posts, graphics, and videos according to calendar'],
                ],
                'version' => '1.0',
                'effective_date' => now()->addMonth(),
                'status' => 'draft',
                'created_by' => $creator->id,
                'notes' => 'Work in progress - need to finalize brand guidelines',
                'view_count' => rand(3, 10),
                'download_count' => rand(1, 3),
            ],
        ];

        foreach ($sops as $sopData) {
            SOP::create($sopData);
        }

        $this->command->info('✅ Successfully created ' . count($sops) . ' SOPs');
        $this->command->info('   - Published: 3 SOPs');
        $this->command->info('   - Approved: 1 SOP');
        $this->command->info('   - Under Review: 1 SOP');
        $this->command->info('   - Draft: 2 SOPs');
    }
}
