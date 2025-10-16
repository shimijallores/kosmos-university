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
├── index.php      # List view with table
├── create.php     # Add form
├── store.php      # Process creation (POST)
├── edit.php       # Edit form
└── update.php     # Process updates (POST)
├── delete.php     # Delete modal (Alpine.js)
└── destroy.php    # Process deletion (POST)
```

### Database Operations

- **Connection**: `$connection` global from `partials/database.php`
- **Queries**: Always use prepared statements with `PDO::prepare()` and `execute()`
- **Fetch**: Use `PDO::FETCH_ASSOC` for associative arrays
- **Joins**: Complex queries join students/courses/subjects/teachers/rooms/semesters

### Authentication & Sessions

```php
// Check auth at top of protected pages
if (empty($_SESSION['user'])) {
    header('location: /login.php');
    exit();
}
```

### URL Patterns

- **Semester filtering**: `?semester=1st25-26`
- **Student search**: Store `$_SESSION['student_number']` for persistence
- **CRUD actions**: Direct links like `edit.php?id={primary_key}`

### Data Formats

- **Gender**: Store as `M`/`F`, display as `Male`/`Female`
- **Student numbers**: `S2023XXX` format
- **Semester codes**: `{1st|2nd}{year_short}-{year_short}` (e.g., `1st25-26`)

### UI Components

- **Tables**: Tailwind classes with `w-3/4` width, blue headers
- **Forms**: Floating labels with Tailwind border animations
- **Modals**: Alpine.js with `x-show`, `x-cloak` for delete confirmations
- **Buttons**: Blue primary (`bg-blue-500`), red delete (`bg-red-500`)

## Critical Workflows

### Student Enrollment Flow

1. Search student by number (stores in session)
2. Display available subjects for semester
3. Insert into `student_subjects` junction table
4. **Note**: Enrollment feature incomplete per TODO

### Grade Management

1. Filter by student number and semester
2. Display subjects with `midterm_grade` and `final_course_grade` fields
3. Update `student_subjects` table
4. **Note**: Grade input UI incomplete per TODO

### PDF Reports

- Use FPDF for student lists and grade reports
- Headers: Arial Bold 20 for titles, Arial Bold 12 for headers
- Data: Arial 10 for content
- Tables: 40-unit wide cells for 4 columns

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

- `subjects/api.php?name={search}` - JSON student search
- `grades/api.php?name={search}` - JSON student search
- **Note**: Basic LIKE search, returns full student records

## Development Notes

- **No validation**: Forms lack input sanitization or CSRF protection
- **No password hashing**: Plain text passwords in database
- **Session storage**: Used for temporary state (student search persistence)
- **Error handling**: Basic try/catch redirects to 404.php on failure
- **Mobile responsive**: TODO indicates design needs mobile overhaul

## Common Query Patterns

```php
// Student with course join
SELECT s.student_id, s.student_number, s.name, c.name as course_name
FROM students s JOIN courses c ON s.course_id = c.course_id

// Student subjects with full joins
SELECT sub.code, sub.description, sem.code as semester_code,
       r.name as room_name, t.name as teacher_name,
       ss.midterm_grade, ss.final_course_grade
FROM students s
JOIN student_subjects ss ON ss.student_id = s.student_id
JOIN subjects sub ON ss.subject_id = sub.id
JOIN semesters sem ON ss.semester_id = sem.id
JOIN rooms r ON r.id = sub.room_id
JOIN teachers t ON t.id = sub.teacher_id
WHERE s.student_number = ? AND sem.code = ?
```
