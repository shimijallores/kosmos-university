# AI Coding Agent Instructions for University Enrollment & Portal

## Project Overview

This is a PHP-based university management system with MySQL database. It handles student enrollment, course management, grading, collections (payments), and provides separate portals for admins and students. The system uses plain PHP with PDO for database interactions, Tailwind CSS for styling, and Alpine.js for client-side interactivity.

## Architecture & Data Flow

- **Core Tables**: `users` (auth), `students`, `courses`, `subjects`, `semesters`, `student_subjects` (enrollments/grades), `collections` (payments), `audit_trait` (activity log)
- **Authentication**: Session-based with roles (admin/student). Students login with auto-generated student_number as credentials
- **Modules**: Each feature (students, courses, subjects, etc.) follows CRUD pattern in separate directories
- **Data Relationships**: Students enroll in subjects per semester, earn grades, make payments via collections
- **External Integrations**: FPDF library for PDF report generation (grades, subjects)

## Key Conventions & Patterns

- **File Structure**: Each module has `index.php` (list), `create.php` (form), `store.php` (insert), `edit.php` (form), `update.php` (update), `delete.php` (modal), `destroy.php` (delete)
- **Database Access**: Use PDO from `partials/database.php`. Always prepare statements to prevent SQL injection
- **Authentication Checks**: Start each protected page with `if (empty($_SESSION['user'])) { header('location: login.php'); exit(); }`
- **Error Handling**: On DB errors, redirect to `404.php` or `failed.php`. Use try-catch in store/update operations
- **Audit Logging**: For collections module, log all actions (A/E/D) to `audit_trait` table with user_id, module, refno
- **Session Messages**: Store feedback in `$_SESSION['message']` or similar for user notifications
- **Form Processing**: POST to `store.php`/`update.php`/`destroy.php`, redirect back to `index.php` on success
- **Styling**: Use Tailwind CSS classes. Forms use floating label style with `relative z-0 w-full mb-5 group`
- **JavaScript**: Alpine.js for modals (e.g., `x-data="{deleteModal: false, deleteId: null}"`)
- **PDF Generation**: Extend FPDF class for custom headers/footers. Query data, calculate metrics (GPA), output to browser

## Developer Workflows

- **Setup**: Import `database/database.sql` into MySQL. Configure `partials/database.php` with correct credentials. Run via Apache/Nginx or PHP built-in server
- **Adding Features**: Create new module directory with standard CRUD files. Add menu link in `index.php` or `student/index.php`
- **Database Changes**: Update `database.sql` with new tables/migrations. Use foreign keys for relationships
- **Testing**: Manual testing only. Verify CRUD operations, PDF outputs, and role-based access
- **Debugging**: Use `dd()` function from `functions.php` for var dumps. Check PHP error logs

## Common Patterns

- **Student Number Generation**: In enrollment, format as `S{year}{3-digit sequential}` (e.g., S2025001)
- **Grade Calculation**: GPA = sum(final_grade \* units) / total_units, only for subjects with final_course_grade > 0
- **Collections**: Support cash and GCash payments. OR numbers are unique, can be updated
- **Semester Filtering**: Pass `?semester={code}` in URLs for semester-specific views
- **Role-Based UI**: Admins see full menus, students see limited portal (grades, ledger, password change)

## Key Files to Reference

- `partials/database.php`: DB connection setup
- `functions.php`: Debug utilities
- `students/index.php`: Example list view with table and delete modal
- `collections/store.php`: Example with audit logging
- `grades/print.php`: PDF generation pattern
- `enrollment/store.php`: Auto user creation for students
