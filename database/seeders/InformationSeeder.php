<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CampusOffice;
use App\Models\ImmigrationRequirement;
use App\Models\OnboardingChecklist;
use App\Models\Resource;

class InformationSeeder extends Seeder
{
    public function run(): void
    {
        // Campus Offices
        $offices = [
            [
                'office_name' => 'International Student Office',
                'building' => 'Block 240',
                'room_number' => 'Room 105',
                'description' => 'Your first stop for all international student matters, including immigration, orientation, and support services.',
                'phone' => '+267 355 1234',
                'email' => 'international@ub.ac.bw',
                'hours' => 'Mon-Fri 8:30am - 4:30pm',
                'category' => 'student_services',
                'sort_order' => 1,
            ],
            [
                'office_name' => 'Immigration Office',
                'building' => 'Block 240',
                'room_number' => 'Room 201',
                'description' => 'Handles student visas, permits, and all immigration compliance matters.',
                'phone' => '+267 355 5678',
                'email' => 'immigration@ub.ac.bw',
                'hours' => 'Mon-Fri 9:00am - 3:30pm',
                'category' => 'administrative',
                'sort_order' => 2,
            ],
            [
                'office_name' => 'Student Welfare Office',
                'building' => 'Block 135',
                'room_number' => 'Ground Floor',
                'description' => 'Support for accommodation, financial aid, counseling, and student well-being.',
                'phone' => '+267 355 9012',
                'email' => 'welfare@ub.ac.bw',
                'hours' => 'Mon-Fri 8:30am - 4:30pm',
                'category' => 'student_services',
                'sort_order' => 3,
            ],
            [
                'office_name' => 'Registrar\'s Office',
                'building' => 'Block 100',
                'room_number' => 'Room 001',
                'description' => 'Academic records, registration, transcripts, and enrollment letters.',
                'phone' => '+267 355 3456',
                'email' => 'registrar@ub.ac.bw',
                'hours' => 'Mon-Fri 9:00am - 4:00pm',
                'category' => 'academic',
                'sort_order' => 4,
            ],
            [
                'office_name' => 'Campus Health Clinic',
                'building' => 'Block 185',
                'description' => 'Primary healthcare services, vaccinations, and wellness programs for students.',
                'phone' => '+267 355 7890',
                'email' => 'health@ub.ac.bw',
                'hours' => 'Mon-Fri 8:00am - 5:00pm',
                'category' => 'health',
                'sort_order' => 5,
            ],
        ];

        foreach ($offices as $office) {
            CampusOffice::create($office);
        }

        // Immigration Requirements
        $requirements = [
            [
                'title' => 'Student Visa Application',
                'description' => 'All international students must obtain a student visa before traveling to Botswana.',
                'category' => 'visa',
                'required_documents' => json_encode([
                    'Valid passport (minimum 6 months validity)',
                    'Letter of acceptance from UB',
                    'Proof of sufficient funds',
                    'Medical certificate',
                    'Police clearance certificate',
                ]),
                'process_steps' => "1. Gather all required documents\n2. Complete visa application form\n3. Submit to Botswana embassy/consulate\n4. Pay visa fee\n5. Wait for processing (4-6 weeks)",
                'office_responsible' => 'Immigration Office (Block 240, Room 201)',
                'priority' => 1,
                'deadline' => now()->addMonths(2),
            ],
            [
                'title' => 'Student Permit Registration',
                'description' => 'Upon arrival, you must register for a student permit within 30 days.',
                'category' => 'permits',
                'required_documents' => json_encode([
                    'Passport with visa',
                    'Proof of enrollment',
                    'Proof of address',
                    'Passport photos (2)',
                ]),
                'process_steps' => "1. Visit Immigration Office within 30 days of arrival\n2. Submit required documents\n3. Pay permit fee\n4. Biometric capture\n5. Collect permit card",
                'office_responsible' => 'Immigration Office (Block 240, Room 201)',
                'priority' => 1,
                'deadline' => now()->addDays(30),
            ],
        ];

        foreach ($requirements as $req) {
            ImmigrationRequirement::create($req);
        }

        // Onboarding Checklist
        $checklist = [
            [
                'title' => 'Accept your offer',
                'description' => 'Accept your offer of admission and pay the acceptance fee.',
                'category' => 'before_arrival',
                'subtasks' => json_encode(['Log in to portal', 'Click Accept Offer', 'Pay acceptance fee']),
                'sort_order' => 1,
                'is_mandatory' => true,
            ],
            [
                'title' => 'Apply for visa',
                'description' => 'Apply for your student visa at the nearest Botswana embassy.',
                'category' => 'before_arrival',
                'subtasks' => json_encode(['Gather documents', 'Complete application', 'Submit to embassy', 'Wait for approval']),
                'sort_order' => 2,
                'is_mandatory' => true,
            ],
            [
                'title' => 'Book accommodation',
                'description' => 'Secure your housing either on-campus or off-campus.',
                'category' => 'before_arrival',
                'sort_order' => 3,
                'is_mandatory' => true,
            ],
            [
                'title' => 'Register with Immigration',
                'description' => 'Visit Immigration Office within 30 days of arrival.',
                'category' => 'upon_arrival',
                'sort_order' => 1,
                'is_mandatory' => true,
            ],
            [
                'title' => 'Attend Orientation',
                'description' => 'Attend international student orientation week.',
                'category' => 'upon_arrival',
                'sort_order' => 2,
                'is_mandatory' => true,
            ],
        ];

        foreach ($checklist as $item) {
            OnboardingChecklist::create($item);
        }

        $resources = [
            [
                'title' => 'UB International Student Guide',
                'description' => 'A quick-start guide covering arrival planning, orientation, and essential student services.',
                'type' => 'guide',
                'external_link' => 'https://www.ub.bw/',
                'tags' => ['arrival', 'orientation', 'student services'],
                'category' => 'onboarding',
                'is_featured' => true,
            ],
            [
                'title' => 'Admissions and Registration Support',
                'description' => 'Useful admissions and registration information for new and returning students.',
                'type' => 'link',
                'external_link' => 'https://www.ub.bw/admissions',
                'tags' => ['registration', 'admissions'],
                'category' => 'academics',
                'is_featured' => true,
            ],
            [
                'title' => 'Student Welfare Contact Directory',
                'description' => 'Key welfare and support contacts for accommodation, counseling, and student wellbeing.',
                'type' => 'contact',
                'external_link' => 'https://www.ub.bw/',
                'tags' => ['welfare', 'support', 'contacts'],
                'category' => 'support',
                'is_featured' => false,
            ],
            [
                'title' => 'Visa and Permit Checklist',
                'description' => 'A reference checklist for common immigration documents and deadlines.',
                'type' => 'faq',
                'external_link' => 'https://www.ub.bw/',
                'tags' => ['visa', 'permits', 'immigration'],
                'category' => 'visa',
                'is_featured' => true,
            ],
        ];

        foreach ($resources as $resource) {
            Resource::create($resource);
        }
    }
}
