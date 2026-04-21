# UB Accommodation Information System Implementation Report

Date: April 8, 2026
Commit: `d6696d0`
Branch: `main`
Remote: `origin/main`

## 1. Summary

This update expanded the UB Accommodation Information System from a basic accommodation and onboarding portal into a broader multi-role platform that supports:

- international student onboarding and accommodation access
- landlord onboarding with staged verification
- off-campus property discovery, booking, and payment
- welfare room allocation and support workflows
- admin review, moderation, and announcement management
- student-landlord communication and in-app notifications
- public information and resource access

In addition to the feature work, a full system review was completed and the major page, data, and workflow issues found during that audit were fixed.

## 2. Major Functionality Added

### 2.1 Student Features

- International student registration support was added with student category, nationality, passport, and onboarding-related details.
- Students can apply for on-campus accommodation through the portal.
- Students can browse verified off-campus properties.
- Students can submit viewing requests for off-campus properties.
- Students can book selected off-campus accommodation on the system.
- Students can pay for off-campus accommodation through the system payment flow.
- Students can submit property enquiries to landlords and track responses.
- Students can submit and track support requests through a virtual help desk.
- Students can view property locations, campus distance, nearby amenities, and transport route details.
- Students can use GPS navigation links and route assistance from the property page.
- Students can review bookings, payments, applications, enquiries, and help desk activity from their dashboard.

### 2.2 Landlord Features

- Landlords can register on the platform.
- Landlords can upload verification documents for:
  - company registration
  - tax clearance certificate
  - director or signatory identity document
  - property ownership documentation
- Landlords can only list properties after verification is completed.
- Landlords can create, edit, and manage property listings.
- Landlords can upload property photos and add amenities, routes, and nearby facility data.
- Landlords can review and respond to viewing requests.
- Landlords can review off-campus bookings.
- Landlords can receive and respond to student enquiries.

### 2.3 Welfare Features

- Welfare can review on-campus applications.
- Welfare can allocate rooms to students.
- Welfare can reject accommodation applications with reasons.
- Welfare can manage accommodation inventory and occupancy data.
- Welfare can verify student documents.
- Welfare can review landlord verification progress.
- Welfare can manage student help desk requests and update statuses.

### 2.4 Admin Features

- Admin dashboard was expanded with user, landlord, property, and announcement oversight.
- Admin can manage users.
- Admin can activate or deactivate accounts.
- Admin can review landlord verification stages.
- Admin can approve, reject, or request more information during landlord verification.
- Admin can review pending property listings before they go live.
- Admin can approve, reject, remove, or request changes on property listings.
- Admin can create and manage announcements.

### 2.5 Shared Platform Features

- In-app notifications were added for major user events.
- Role-based route protection was enforced for student, landlord, welfare, and admin areas.
- Inactive account protection was added.
- Campus configuration was added for map distance and navigation support.

## 3. New Data Structures Added

### 3.1 Models Added

- `Announcement`
- `LandlordVerificationDocument`
- `PropertyBooking`
- `PropertyEnquiry`
- `SupportRequest`
- `SystemNotification`

### 3.2 Migrations Added

- landlord verification and international student fields on users
- landlord verification documents table
- property discovery and moderation fields
- property bookings table
- support requests table
- admin workflow fields for users and properties
- property enquiries table
- announcements table
- system notifications table

## 4. New or Rebuilt Pages

### 4.1 Public Pages

- Accommodation detail page
- Resources library page
- Office detail page
- Requirement detail page
- improved accommodation hub and information hub

### 4.2 Student Pages

- accommodation detail page
- bookings page
- enquiries page
- payments page
- support requests page
- improved dashboard, applications, properties, property detail, viewing requests, home, on-campus, and off-campus pages

### 4.3 Landlord Pages

- verification page
- properties list
- property create
- property edit
- bookings page
- enquiries page
- viewing requests page
- shared property form partial

### 4.4 Welfare Pages

- landlord verifications page
- support requests page
- improved dashboard

### 4.5 Admin Pages

- announcements page
- landlord verifications page
- pending properties page
- users page
- improved dashboard

## 5. Bugs and Logic Problems Fixed

### 5.1 Broken or Missing Pages

- Fixed the broken public resources page by adding the missing Blade template.
- Added missing public accommodation detail page.
- Added missing office and immigration requirement detail pages referenced by controllers.
- Fixed dead resource actions that previously pointed to `#`.

### 5.2 Route and Navigation Fixes

- Fixed stale route names in student and public templates.
- Replaced broken apply/view links with working route targets.
- Fixed dead “View Details” links on student accommodation pages.
- Replaced broken notice-board link usage with a valid destination.

### 5.3 Data Rendering Fixes

- Removed invalid `json_decode()` usage on model attributes already cast to arrays.
- Fixed facilities, amenities, checklist subtasks, and immigration required documents rendering.
- Updated landing page statistics to use live controller data instead of hardcoded placeholder counts.
- Added starter seeded resource records so the information module has visible data after seeding.

### 5.4 Security and Workflow Fixes

- Blocked direct POST access to viewing and booking actions for unapproved properties.
- Prevented welfare from approving already-processed accommodation applications repeatedly.
- Fixed occupancy overcount risk caused by duplicate application approvals.
- Fixed student document rejection state so rejected documents are reflected correctly in the UI.
- Prevented verified landlords from accidentally resetting themselves into an invalid `pending/completed` verification state.
- Changed approved property edits to return to admin review instead of staying live automatically.
- Improved booking confirmation so available units are decremented safely and cannot be oversold by duplicate confirmation flow.

## 6. Key Files Updated

### 6.1 Core Backend

- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/InformationHubController.php`
- `app/Http/Controllers/LandlordController.php`
- `app/Http/Controllers/StudentController.php`
- `app/Http/Controllers/WelfareController.php`
- `app/Http/Controllers/NotificationController.php`
- `app/Http/Middleware/EnsureAccountIsActive.php`
- `app/Http/Middleware/EnsureUserRole.php`
- `app/Models/User.php`
- `app/Models/Property.php`
- `app/Models/Payment.php`
- `app/Models/PropertyBooking.php`
- `app/Models/Resource.php`
- `app/Models/OnboardingChecklist.php`
- `routes/web.php`

### 6.2 Key Views

- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/public.blade.php`
- `resources/views/landing.blade.php`
- `resources/views/information-hub.blade.php`
- `resources/views/information/resources.blade.php`
- `resources/views/accommodation/show.blade.php`
- `resources/views/accommodation/property-show.blade.php`
- `resources/views/student/dashboard.blade.php`
- `resources/views/student/property-show.blade.php`
- `resources/views/landlord/verification.blade.php`
- `resources/views/admin/pending-properties.blade.php`
- `resources/views/welfare/landlord-verifications.blade.php`

## 7. Verification Completed

- PHP syntax verification passed across all PHP files in:
  - `app`
  - `routes`
  - `config`
  - `database`
- The implementation was committed and pushed to GitHub.

## 8. Git Status

- Commit pushed: `d6696d0`
- Commit message: `Implement portal workflows and system fixes`
- Remote branch updated: `origin/main`

## 9. Remaining Runtime Limitation

Code-level verification was completed, but full Laravel runtime checks could not be executed in this workspace because dependencies are not installed locally yet.

Recommended next runtime steps:

1. Run `composer install`
2. Run `php artisan migrate --seed`
3. Run `php artisan serve` or the project’s normal local startup flow
4. Click through each role flow in-browser
5. Run the full Laravel test suite once dependencies are available

