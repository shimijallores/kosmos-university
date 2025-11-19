# University Portal System

A PHP-based university management system for handling student enrollment, grading, and financial collections.

## Features

- **Admin Portal**: Manage students, courses, subjects, semesters, and grades
- **Teacher Portal**: Grade management with Excel paste support for bulk entry
- **Student Portal**: View grades, collections, and payment history
- **Collections Management**: Track payments with OR number generation and history

## Tech Stack

- **Backend**: PHP 8.0+ with PDO/MySQL
- **Frontend**: Tailwind CSS, Alpine.js
- **PDF Generation**: FPDF library
- **Design**: Flat, minimalistic UI (shadcn-inspired)

## Setup

1. Import `database/database.sql` into MySQL
2. Configure database connection in `partials/database.php`
3. Access via web server (Apache/Nginx)

## Default Credentials

- Admin: Configure in `users` table
- Teachers: Code-based authentication (e.g., 'AAAAAAAAAA')
- Students: Student number + password

## Project Structure

```
├── auth/              # Authentication logic
├── collections/       # Payment & OR management
├── courses/          # Course CRUD
├── enrollment/       # Student enrollment
├── grades/           # Grade management
├── partials/         # Reusable components (sidebar, header, footer)
├── semesters/        # Semester CRUD
├── student/          # Student portal
├── students/         # Student CRUD (admin)
├── subjects/         # Subject CRUD
├── teacher/          # Teacher portal (grading)
└── fpdf186/          # PDF generation library
```

---

Built as a requirement for Intelligent Systems
