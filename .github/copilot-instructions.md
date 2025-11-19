# AI Coding Agent Instructions for University Enrollment & Portal

```md
# Copilot instructions — University enrollment & portal (concise)

Purpose: quickly onboard AI coding agents so they can make focused, safe edits.

High level

- PHP app (plain PHP + PDO) with MySQL; front-end uses Tailwind CSS + Alpine.js.
- Modules are filesystem-separated by feature: `students/`, `courses/`, `subjects/`, `collections/`, `grades/`, `enrollment/`, `semesters/`, `partials/`, `student/`, `teacher/`.

Key patterns (do these exactly)

- CRUD per module: `index.php` (list), `create.php` (form), `store.php` (insert), `edit.php`, `update.php`, `delete.php` (modal), `destroy.php` (delete). Follow this pattern for new features.
- DB access: use `partials/database.php` PDO connection and prepared statements. Never inline raw user input into SQL.
- Auth: session-based. Guard pages with `if (empty($_SESSION['user'])) { header('location: menu.php'); exit(); }`.
- Flash messages: codebase uses `$_SESSION['message']` (or similar) for feedback; preserve these conventions when redirecting.
- Audit log: `audit_trait` is used by `collections/` (A/E/D) — include user_id, module, refno when logging.

Concrete examples (search before changing)

- Enrollment creates both `students` and an auto user — see `enrollment/store.php`.
- Collections: see `collections/store.php`, `collections/or_api.php`, `collections/api.php` for payment-related flows and OR number uniqueness.
- PDFs: `fpdf186/` contains FPDF; `grades/print.php` and `subjects/print.php` show how reports are built (extend FPDF, custom header/footer).

Developer flows

- Local run: import `database/database.sql` into MySQL, update `partials/database.php` with credentials.
- Quick dev server: from project root run `php -S localhost:8000 -t .` and open `http://localhost:8000/` (or use Apache/Nginx).
- No automated tests found — changes must be validated manually in-browser and by inspecting DB.

Conventions worth preserving

- Tailwind classes for layout; floating-label form pattern uses `relative z-0 w-full mb-5 group`.
- Alpine.js patterns for modals: `x-data="{deleteModal:false, deleteId:null}"` and toggling values via `x-on:click`.
- Student number format: `S{year}{3-digit sequential}` (e.g., `S2025001`) — modify only where enrollment logic lives.
- GPA calculation: use only subjects with `final_course_grade > 0`; formula: sum(final_grade \* units) / total_units.

Integration & APIs

- Minimal internal APIs live in module folders (`subjects/api.php`, `grades/api.php`, `collections/or_api.php`) — they expect POST and return HTML/JSON.

What AI agents should do first

- Read `partials/database.php`, `functions.php`, `enrollment/store.php`, `collections/store.php`, and one PDF print file (`grades/print.php`).
- Search for `store.php` and `destroy.php` when changing flows to update redirects/messages consistently.

Safety & scope

- Avoid changing global templates (`partials/head.php`, `menu.php`, `partials/footer.php`) unless UX-wide change requested.
- Don't invent new authentication mechanisms; extend existing session-based role checks.

When unsure, ask the human: share the specific file and the DB table you plan to modify.

Files to reference quickly

- `partials/database.php`, `functions.php`, `menu.php`, `enrollment/store.php`, `collections/store.php`, `grades/print.php`, `database/database.sql`.

End of instructions.
```
