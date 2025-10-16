# University Enrollment System - AI Coding Guidelines

## Architecture Overview

This is a PHP/MySQL web application for university enrollment management with session-based authentication. The system manages students, courses, subjects, grades, and enrollment across semesters.

**Core Components:**

- **Database**: MySQL (`unicore` database) with PDO connections
- **Frontend**: Tailwind CSS + Alpine.js for responsive UI and modals
- **PDF Generation**: FPDF library for reports
- **Authentication**: Single-user system (username: `shimi`, password: `shimi`)

## Key Patterns & Conventions

### File Structure

```
modules/{entity}/
├── index.php      # List view with responsive table
├── create.php     # Modal form for adding records
├── store.php      # Process creation (POST) - redirects on success/error
├── edit.php       # Edit form (legacy - being phased out)
├── update.php     # Process updates (POST) - redirects on success/error
├── delete.php     # Delete confirmation modal (Alpine.js)
├── destroy.php    # Process deletion (POST)
├── api.php        # JSON endpoints for search/autocomplete
└── print.php      # PDF report generation

enrollment/
├── index.php      # Student self-enrollment form
├── store.php      # Process enrollment - generates student number
└── success.php    # Enrollment confirmation page
```

### Responsive Design Patterns

**Mobile-First Approach:**

- Container widths: `w-full md:max-w-3/4 mx-auto px-4` (full width on mobile, constrained on desktop)
- Form layouts: `flex flex-col sm:flex-row gap-2 items-start sm:items-center`
- Tables: `overflow-x-auto` wrapper with `min-w-[XXXpx]` columns for horizontal scrolling
- Buttons: `w-full sm:w-auto` for stacked mobile, inline desktop

**Modal Patterns:**

- Centered positioning: `flex items-center justify-center` (not `items-end`)
- Responsive sizing: `w-full max-w-sm mx-4 sm:max-w-lg`
- Consistent padding: `p-4 backdrop-blur-md sm:p-8`

### Database Operations

- **Connection**: `$connection` global from `partials/database.php`
- **Queries**: Always use prepared statements with `PDO::prepare()` and `execute()`
- **Fetch**: Use `PDO::FETCH_ASSOC` for associative arrays
- **Grade Formatting**: Display grades as 2 decimals: `number_format((float)$grade, 2)`
- **Joins**: Complex queries join students/courses/subjects/teachers/rooms/semesters

### Authentication & Sessions

```php
// Check auth at top of protected pages
if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}
```

### URL Patterns & State Management

- **Semester filtering**: `?semester=1st25-26`
- **Student search**: Store `$_SESSION['student_number']` for persistence across pages
- **CRUD actions**: Modal-based interactions, form submissions redirect back
- **Error handling**: Try/catch blocks redirect to `404.php` on database errors

### Data Formats & Validation

- **Gender**: Store as `M`/`F`, display as `Male`/`Female`
- **Student numbers**: `S{year_short}{sequential}` (e.g., `S25001`) - auto-generated on enrollment
- **Semester codes**: `{1st|2nd}{year_short}-{year_short}` (e.g., `1st25-26`)
- **Grades**: DECIMAL(20,6) in DB, display as 2 decimals, input via dropdowns
- **No validation**: Forms lack input sanitization or CSRF protection (by design)

### UI Components & Styling

**Tables:**

- Responsive wrapper: `<div class="w-full md:max-w-3/4 mx-auto px-4 overflow-x-auto">`
- Headers: `bg-blue-500 text-white` with `min-w-[XXXpx]` columns
- Data cells: `border border-black px-2 py-2`

**Forms & Inputs:**

- Labels: `block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2`
- Inputs: `border w-full border-neutral-200 px-3 py-2 rounded focus:ring-2 focus:ring-blue-500`
- Selects: Pre-populate with `:selected="value == 'X.XX'"` for current values

**Modals (Alpine.js):**

- Backdrop: `fixed inset-0 z-30 flex items-center justify-center bg-black/20 p-4 backdrop-blur-md sm:p-8`
- Dialog: `w-full max-w-sm mx-4 sm:max-w-lg flex flex-col gap-4 overflow-hidden rounded-sm border`
- State management: `x-show`, `x-cloak`, Alpine data properties for modal state

**Buttons:**

- Primary: `bg-blue-600 hover:bg-blue-700 text-white`
- Delete: `bg-red-700 hover:bg-red-600 text-white`
- Cancel: `text-neutral-600 hover:opacity-75`
- Responsive: `w-full sm:w-auto whitespace-nowrap rounded-sm px-4 py-2`

## Critical Workflows

### Student Search & Grade Management Flow

1. Search student by number → stores in `$_SESSION['student_number']`
2. Display student info with semester selector dropdown
3. Show enrolled subjects table with current grades
4. Click "Input Grades" → modal opens pre-populated with current values
5. Select new grades from dropdowns → submit form → `update.php` → redirect back
6. Grades display as formatted 2-decimal values in table

### Student Self-Enrollment Flow

1. Student accesses `enrollment/index.php` (no login required)
2. Fill form: name, gender, course, semester
3. Submit → `store.php` generates student number (S{year}{sequential})
4. Creates student record in database
5. Redirects to `success.php` with confirmation and student number
6. Student can now login with their student number

### PDF Report Generation

- Use FPDF with Arial fonts (Bold 20 titles, Bold 12 headers, 10pt data)
- Student info header with university logo
- Table with subject codes, descriptions, units, midterm/final grades
- GPA calculation: `($total_points / $total_units)` formatted to 2 decimals
- Pass/Fail logic: grades 1.0-3.0 = "Passed"

## Database Schema Reference

**Core Tables:**

- `students` (student_id, student_number, name, gender, course_id)
- `courses` (course_id, code, name)
- `subjects` (id, code, description, days, time, room_id, teacher_id, price_unit, units)
- `student_subjects` (id, subject_id, student_id, semester_id, midterm_grade, final_course_grade)
- `semesters` (id, code)
- `teachers` (id, name), `rooms` (id, name)

**Relationships:**

- Students → Courses (many-to-one)
- Subjects → Teachers/Rooms (many-to-one)
- Student_Subjects → Students/Subjects/Semesters (junction)

## API Endpoints

- `subjects/api.php?name={search}` - JSON student search (LIKE query)
- `grades/api.php?name={search}` - JSON student search (LIKE query)
- **Note**: Basic LIKE search, returns full student records for autocomplete

## Development Notes

- **Mobile responsive**: Fully implemented across all modules (students, courses, subjects, grades)
- **Modal-first UI**: All CRUD operations use Alpine.js modals instead of separate pages
- **Student enrollment**: Self-service enrollment form at `enrollment/index.php` (no login required)
- **Student number generation**: Auto-generates sequential numbers (S{year}{sequential})
- **No validation**: Forms lack input sanitization or CSRF protection (accepted design choice)
- **No password hashing**: Plain text passwords in database
- **Session storage**: Used for temporary state (student search persistence)
- **Error handling**: Basic try/catch redirects to 404.php on failure
- **Grade precision**: Database stores 6 decimals, display shows 2 decimals

## Common Query Patterns

```php
// Student with course join
SELECT s.student_id, s.student_number, s.name, c.name as course_name
FROM students s JOIN courses c ON s.course_id = c.course_id

// Student subjects with grades (current implementation)
SELECT sub.code, sub.description, sub.units,
       ss.midterm_grade, ss.final_course_grade
FROM students s
JOIN student_subjects ss ON ss.student_id = s.student_id
JOIN subjects sub ON ss.subject_id = sub.id
JOIN semesters sem ON ss.semester_id = sem.id
WHERE s.student_number = ? AND sem.code = ?

// GPA calculation
$total_points = 0; $total_units = 0;
foreach ($subjects as $subject) {
    if ($subject['final_course_grade'] > 0) {
        $total_points += $subject['final_course_grade'] * $subject['units'];
        $total_units += $subject['units'];
    }
}
$gpa = $total_units > 0 ? number_format($total_points / $total_units, 2) : '0.00';
```
