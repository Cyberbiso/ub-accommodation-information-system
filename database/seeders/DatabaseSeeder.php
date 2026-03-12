<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Accommodation;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Database Seeder
 * 
 * Populates the database with test data for development and testing.
 * Creates users with different roles, sample accommodations, and properties.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==========================================
        // CREATE TEST USERS
        // ==========================================

        echo "Creating test users...\n";

        // Create Admin User
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@ub.ac.bw',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create Welfare Officer
        User::create([
            'name' => 'Welfare Officer',
            'email' => 'welfare@ub.ac.bw',
            'password' => Hash::make('password123'),
            'role' => 'welfare',
        ]);

        // Create Test Student
        User::create([
            'name' => 'John Student',
            'email' => 'student@ub.ac.bw',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);

        // Create Test Landlord
        User::create([
            'name' => 'Sarah Landlord',
            'email' => 'landlord@example.com',
            'password' => Hash::make('password123'),
            'role' => 'landlord',
        ]);

        // Create Additional Students
        User::create([
            'name' => 'Mary Johnson',
            'email' => 'mary.johnson@ub.ac.bw',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Peter Smith',
            'email' => 'peter.smith@ub.ac.bw',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);

        // Create Additional Landlords
        User::create([
            'name' => 'Robert Properties',
            'email' => 'robert@example.com',
            'password' => Hash::make('password123'),
            'role' => 'landlord',
        ]);

        User::create([
            'name' => 'Estates Management',
            'email' => 'estates@example.com',
            'password' => Hash::make('password123'),
            'role' => 'landlord',
        ]);

        echo "✓ Users created successfully\n";

        // ==========================================
        // CREATE ON-CAMPUS ACCOMMODATIONS
        // ==========================================

        echo "Creating on-campus accommodations...\n";

        $accommodations = [
            [
                'name' => 'Block A - Room 101',
                'type' => 'single',
                'capacity' => 1,
                'monthly_rent' => 450.00,
                'block' => 'A',
                'floor' => 1,
                'facilities' => json_encode(['WiFi', 'Single Bed', 'Desk', 'Wardrobe', 'Study Lamp']),
            ],
            [
                'name' => 'Block A - Room 102',
                'type' => 'single',
                'capacity' => 1,
                'monthly_rent' => 450.00,
                'block' => 'A',
                'floor' => 1,
                'facilities' => json_encode(['WiFi', 'Single Bed', 'Desk', 'Wardrobe', 'Study Lamp']),
            ],
            [
                'name' => 'Block A - Room 103',
                'type' => 'single',
                'capacity' => 1,
                'monthly_rent' => 450.00,
                'block' => 'A',
                'floor' => 1,
                'facilities' => json_encode(['WiFi', 'Single Bed', 'Desk', 'Wardrobe', 'Study Lamp']),
            ],
            [
                'name' => 'Block B - Room 201',
                'type' => 'shared',
                'capacity' => 2,
                'monthly_rent' => 350.00,
                'block' => 'B',
                'floor' => 2,
                'facilities' => json_encode(['WiFi', 'Bunk Beds', 'Desks x2', 'Shared Wardrobe', 'Study Area']),
            ],
            [
                'name' => 'Block B - Room 202',
                'type' => 'shared',
                'capacity' => 2,
                'monthly_rent' => 350.00,
                'block' => 'B',
                'floor' => 2,
                'facilities' => json_encode(['WiFi', 'Bunk Beds', 'Desks x2', 'Shared Wardrobe', 'Study Area']),
            ],
            [
                'name' => 'Block C - Room 301',
                'type' => 'family',
                'capacity' => 4,
                'monthly_rent' => 650.00,
                'block' => 'C',
                'floor' => 3,
                'facilities' => json_encode(['WiFi', '2 Bedrooms', 'Living Room', 'Kitchen', 'Bathroom']),
            ],
            [
                'name' => 'Block D - Room 101',
                'type' => 'single',
                'capacity' => 1,
                'monthly_rent' => 480.00,
                'block' => 'D',
                'floor' => 1,
                'facilities' => json_encode(['WiFi', 'Single Bed', 'Desk', 'Wardrobe', 'AC', 'Private Bathroom']),
            ],
            [
                'name' => 'Block D - Room 102',
                'type' => 'single',
                'capacity' => 1,
                'monthly_rent' => 480.00,
                'block' => 'D',
                'floor' => 1,
                'facilities' => json_encode(['WiFi', 'Single Bed', 'Desk', 'Wardrobe', 'AC', 'Private Bathroom']),
            ],
        ];

        foreach ($accommodations as $acc) {
            Accommodation::create($acc);
        }

        echo "✓ " . count($accommodations) . " accommodations created successfully\n";

        // ==========================================
        // CREATE OFF-CAMPUS PROPERTIES
        // ==========================================

        echo "Creating off-campus properties...\n";

        // Get landlord IDs
        $landlord1 = User::where('email', 'landlord@example.com')->first();
        $landlord2 = User::where('email', 'robert@example.com')->first();
        $landlord3 = User::where('email', 'estates@example.com')->first();

        $properties = [
            [
                'landlord_id' => $landlord1->id,
                'title' => 'Modern Studio Apartment Near UB',
                'description' => 'Fully furnished studio apartment just 5 minutes walk from UB main campus. Perfect for a single student. Includes high-speed WiFi, electricity, and water.',
                'address' => '123 University Way',
                'city' => 'Gaborone',
                'postal_code' => null,
                'monthly_rent' => 2800.00,
                'type' => 'studio',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'distance_to_campus_km' => 0.5,
                'amenities' => json_encode(['WiFi', 'Electricity Included', 'Water Included', 'Furnished', 'Security']),
                'photos' => json_encode([]),
                'is_approved' => true,
            ],
            [
                'landlord_id' => $landlord1->id,
                'title' => '2-Bedroom Apartment in Secure Complex',
                'description' => 'Spacious 2-bedroom apartment in a secure complex with 24/7 security. Pool and gym access included. Ideal for sharing with another student.',
                'address' => '45 Independence Avenue',
                'city' => 'Gaborone',
                'postal_code' => null,
                'monthly_rent' => 4200.00,
                'type' => 'apartment',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'distance_to_campus_km' => 2.3,
                'amenities' => json_encode(['WiFi', 'Pool', 'Gym', 'Secure Parking', '24hr Security', 'Electricity Included']),
                'photos' => json_encode([]),
                'is_approved' => true,
            ],
            [
                'landlord_id' => $landlord2->id,
                'title' => 'Shared House - Room Available',
                'description' => 'Room available in a shared house with 3 other students. Common areas include living room, kitchen, and garden. Utilities shared equally.',
                'address' => '78 Extension 2',
                'city' => 'Gaborone',
                'postal_code' => null,
                'monthly_rent' => 1800.00,
                'type' => 'shared',
                'bedrooms' => 1,
                'bathrooms' => 2,
                'distance_to_campus_km' => 3.1,
                'amenities' => json_encode(['WiFi', 'Shared Kitchen', 'Garden', 'Laundry', 'Parking']),
                'photos' => json_encode([]),
                'is_approved' => true,
            ],
            [
                'landlord_id' => $landlord2->id,
                'title' => 'Student Friendly 1-Bedroom Flat',
                'description' => 'Cozy 1-bedroom flat perfect for a single student. Close to shops and public transport. 10 minutes to UB by bus.',
                'address' => '12 Broadhurst Mall',
                'city' => 'Gaborone',
                'postal_code' => null,
                'monthly_rent' => 2300.00,
                'type' => 'apartment',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'distance_to_campus_km' => 4.5,
                'amenities' => json_encode(['WiFi', 'Kitchen', 'Parking', 'Near Shops']),
                'photos' => json_encode([]),
                'is_approved' => true,
            ],
            [
                'landlord_id' => $landlord3->id,
                'title' => '3-Bedroom House with Garden',
                'description' => 'Large 3-bedroom house with big garden. Perfect for group of students. Fully furnished with modern appliances.',
                'address' => '56 Phakalane',
                'city' => 'Gaborone',
                'postal_code' => null,
                'monthly_rent' => 5500.00,
                'type' => 'house',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'distance_to_campus_km' => 6.2,
                'amenities' => json_encode(['WiFi', 'Garden', 'Parking', 'Furnished', 'DStv', 'Kitchen Appliances']),
                'photos' => json_encode([]),
                'is_approved' => true,
            ],
            [
                'landlord_id' => $landlord3->id,
                'title' => 'New Development - Studios Available',
                'description' => 'Brand new studio apartments in a modern block. Each unit has own bathroom and kitchenette. Popular with postgrad students.',
                'address' => '89 Village',
                'city' => 'Gaborone',
                'postal_code' => null,
                'monthly_rent' => 3200.00,
                'type' => 'studio',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'distance_to_campus_km' => 1.8,
                'amenities' => json_encode(['WiFi', 'Air Conditioning', 'Kitchenette', 'Study Desk', 'Secure Entry']),
                'photos' => json_encode([]),
                'is_approved' => false, // Pending approval
            ],
        ];

        foreach ($properties as $prop) {
            Property::create($prop);
        }

        echo "✓ " . count($properties) . " properties created successfully\n";
        echo "\n========================================\n";
        echo "SEEDING COMPLETED SUCCESSFULLY!\n";
        echo "========================================\n";
        echo "Test Accounts:\n";
        echo "- Admin: admin@ub.ac.bw / password123\n";
        echo "- Welfare: welfare@ub.ac.bw / password123\n";
        echo "- Student: student@ub.ac.bw / password123\n";
        echo "- Landlord: landlord@example.com / password123\n";
        echo "========================================\n";
    }
}