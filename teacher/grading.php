<?php
require('../functions.php');
require('../partials/head.php');

if (empty($_SESSION['user'])) {
    header('location: /menu.php');
    exit();
}

// Get teacher ID from session
$teacher_code = $_SESSION['user']['name'];
$stmt = $connection->prepare("SELECT id FROM teachers WHERE code = ?");
$stmt->execute([$teacher_code]);
$teacher = $stmt->fetch();

if (!$teacher) {
    header('location: index.php');
    exit();
}

$teacher_id = $teacher['id'];

// Fetch semesters
$stmt = $connection->prepare("SELECT * FROM semesters ORDER BY id DESC");
$stmt->execute();
$semesters = $stmt->fetchAll();

// Fetch subjects taught by this teacher
$stmt = $connection->prepare("SELECT * FROM subjects WHERE teacher_id = ? ORDER BY code");
$stmt->execute([$teacher_id]);
$subjects = $stmt->fetchAll();

// Get selected filters
$selected_semester = $_GET['semester'] ?? '';
$selected_subject = $_GET['subject'] ?? '';
$selected_term = $_GET['term'] ?? '';

// Fetch students if all filters are selected
$students = [];
if ($selected_semester && $selected_subject && $selected_term) {
    $stmt = $connection->prepare("
        SELECT 
            s.student_id,
            s.student_number,
            s.name as student_name,
            s.gender,
            c.code as course_code,
            c.name as course_name,
            ss.midterm_grade,
            ss.final_course_grade,
            sub.code as subject_code,
            sub.description as subject_description
        FROM student_subjects ss
        JOIN students s ON ss.student_id = s.student_id
        JOIN courses c ON s.course_id = c.course_id
        JOIN subjects sub ON ss.subject_id = sub.id
        WHERE ss.subject_id = ? 
        AND ss.semester_id = (SELECT id FROM semesters WHERE code = ?)
        ORDER BY 
            CASE WHEN s.gender = 'F' THEN 0 ELSE 1 END,
            s.name ASC
    ");
    $stmt->execute([$selected_subject, $selected_semester]);
    $students = $stmt->fetchAll();
}
?>

<body class="min-h-screen bg-gray-50" x-data="gradingData()">
    <?php require('../partials/teacher_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-64">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-2xl font-extrabold text-gray-900">Student Grading</h1>
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Teacher:</span> <?= htmlspecialchars($_SESSION['user']['name']) ?>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Success Message -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="mb-6 p-4 border border-green-200 bg-green-50">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="ml-3 text-sm font-medium text-green-800">
                                <?= htmlspecialchars($_SESSION['message']) ?>
                            </p>
                        </div>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <!-- Filter Card -->
                <div class="bg-white border border-gray-200 p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Select Grading Parameters</h2>
                    <form method="GET" action="grading.php" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Semester Selection -->
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                                <select name="semester" id="semester" required
                                    class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                                    <option value="">Select Semester</option>
                                    <?php foreach ($semesters as $semester): ?>
                                        <option value="<?= $semester['code'] ?>" <?= $selected_semester == $semester['code'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($semester['code']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Subject Selection -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                <select name="subject" id="subject" required
                                    class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                                    <option value="">Select Subject</option>
                                    <?php foreach ($subjects as $subject): ?>
                                        <option value="<?= $subject['id'] ?>" <?= $selected_subject == $subject['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($subject['code']) ?> - <?= htmlspecialchars($subject['description']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Term Selection -->
                            <div>
                                <label for="term" class="block text-sm font-medium text-gray-700 mb-2">Term</label>
                                <select name="term" id="term" required
                                    class="w-full px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                                    <option value="">Select Term</option>
                                    <option value="midterm" <?= $selected_term == 'midterm' ? 'selected' : '' ?>>Midterm</option>
                                    <option value="final" <?= $selected_term == 'final' ? 'selected' : '' ?>>Final</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-neutral-800 hover:bg-neutral-900 focus:ring-2 focus:ring-neutral-800">
                                Load Students
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Students Table -->
                <?php if ($selected_semester && $selected_subject && $selected_term): ?>
                    <div class="bg-white border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">
                                Student List - <?= $selected_term == 'midterm' ? 'Midterm Grades' : 'Final Grades' ?>
                            </h2>
                            <?php if (!empty($students)): ?>
                                <span class="text-sm text-gray-600">
                                    <?= count($students) ?> student<?= count($students) != 1 ? 's' : '' ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($students)): ?>
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-gray-600">No students enrolled in this subject for the selected semester.</p>
                            </div>
                        <?php else: ?>
                            <div class="relative">
                                <!-- Paste from Excel Section -->
                                <div class="mb-4 p-4 border border-gray-200 bg-gray-50">
                                    <h3 class="text-sm font-medium text-gray-900 mb-3">Paste Grades from Excel</h3>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <button type="button" @click="pasteGrades('all')"
                                            class="px-3 py-2 text-xs font-medium text-white bg-neutral-800 hover:bg-neutral-900 focus:ring-2 focus:ring-neutral-800">
                                            Paste All Students
                                        </button>
                                        <button type="button" @click="pasteGrades('female')"
                                            class="px-3 py-2 text-xs font-medium text-gray-700 border border-gray-300 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-neutral-800">
                                            Paste Female Only
                                        </button>
                                        <button type="button" @click="pasteGrades('male')"
                                            class="px-3 py-2 text-xs font-medium text-gray-700 border border-gray-300 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-neutral-800">
                                            Paste Male Only
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-600 mb-2">Copy grades from Excel (one grade per line), click a button above, then paste (Ctrl+V) to auto-fill.</p>
                                </div>

                                <!-- Preview Modal -->
                                <div x-cloak x-show="previewGrades.length > 0"
                                    x-transition.opacity.duration.200ms
                                    x-on:keydown.esc.window="clearPreview()"
                                    x-on:click.self="clearPreview()"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 p-4"
                                    role="dialog"
                                    aria-modal="true"
                                    aria-labelledby="previewModalTitle">
                                    <!-- Modal Dialog -->
                                    <div x-show="previewGrades.length > 0"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="w-full max-w-md bg-white border border-gray-300"
                                        @click.stop>
                                        <!-- Dialog Header -->
                                        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                                            <div>
                                                <h3 id="previewModalTitle" class="text-lg font-medium text-gray-900">Preview Grades</h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <span x-text="previewGrades.length"></span> grade(s) •
                                                    <span x-text="pasteMode === 'all' ? 'All Students' : (pasteMode === 'female' ? 'Female Only' : 'Male Only')"></span>
                                                </p>
                                            </div>
                                            <button type="button" @click="clearPreview()" aria-label="close modal" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Dialog Body -->
                                        <div class="px-6 py-6">
                                            <div class="max-h-96 overflow-y-auto p-4 bg-gray-50 border border-gray-200">
                                                <div class="flex flex-wrap gap-2">
                                                    <template x-for="(grade, index) in previewGrades" :key="index">
                                                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium bg-white border border-gray-300 text-gray-900">
                                                            <span class="text-gray-500 mr-2" x-text="(index + 1) + '.'"></span>
                                                            <span x-text="grade"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dialog Footer -->
                                        <div class="flex items-center justify-end space-x-3 border-t border-gray-200 px-6 py-4 bg-gray-50">
                                            <button type="button" @click="clearPreview()"
                                                class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-medium focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                                Cancel
                                            </button>
                                            <button type="button" @click="fillGrades()"
                                                class="px-4 py-2 bg-neutral-800 hover:bg-neutral-900 text-white font-medium border border-neutral-800 focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2">
                                                Apply Grades
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <form method="POST" action="update.php">
                                        <input type="hidden" name="semester" value="<?= htmlspecialchars($selected_semester) ?>">
                                        <input type="hidden" name="subject" value="<?= htmlspecialchars($selected_subject) ?>">
                                        <input type="hidden" name="term" value="<?= htmlspecialchars($selected_term) ?>">

                                        <table class="w-full">
                                            <thead>
                                                <tr class="bg-gray-50 border-b border-gray-200">
                                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-900">Student Number</th>
                                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-900">Student Name</th>
                                                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-900">Gender</th>
                                                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-900">Course</th>
                                                    <th class="px-4 py-3 text-center text-sm font-medium text-gray-900">
                                                        <?= $selected_term == 'midterm' ? 'Midterm Grade' : 'Final Grade' ?>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <?php foreach ($students as $student): ?>
                                                    <tr class="hover:bg-gray-50" data-gender="<?= $student['gender'] ?>">
                                                        <td class="px-4 py-3 text-sm text-gray-900"><?= htmlspecialchars($student['student_number']) ?></td>
                                                        <td class="px-4 py-3 text-sm text-gray-900"><?= htmlspecialchars($student['student_name']) ?></td>
                                                        <td class="px-4 py-3 text-sm text-center">
                                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium <?= $student['gender'] == 'F' ? 'text-pink-700 bg-pink-50' : 'text-blue-700 bg-blue-50' ?>">
                                                                <?= $student['gender'] == 'F' ? 'Female' : 'Male' ?>
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-900"><?= htmlspecialchars($student['course_code']) ?></td>
                                                        <td class="px-4 py-3 text-sm text-center">
                                                            <input type="hidden" name="student_ids[]" value="<?= $student['student_id'] ?>">
                                                            <select name="grades[]" class="grade-select w-32 px-3 py-2 border border-gray-300 focus:ring-2 focus:ring-neutral-800 focus:border-neutral-800">
                                                                <option value="">Select Grade</option>
                                                                <?php
                                                                $current_grade = $selected_term == 'midterm' ? $student['midterm_grade'] : $student['final_course_grade'];
                                                                $grades = ['1.00', '1.25', '1.50', '1.75', '2.00', '2.25', '2.50', '2.75', '3.00', '4.00', '5.00'];
                                                                foreach ($grades as $grade):
                                                                ?>
                                                                    <option value="<?= $grade ?>" <?= $current_grade == $grade ? 'selected' : '' ?>>
                                                                        <?= $grade ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                        <div class="mt-6 flex justify-end">
                                            <button type="submit"
                                                class="px-6 py-2 text-sm font-medium text-white bg-neutral-800 hover:bg-neutral-900 focus:ring-2 focus:ring-neutral-800">
                                                Save Grades
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

<script>
    function gradingData() {
        return {
            sidebarOpen: false,
            pasteMode: null,
            previewGrades: [],

            async pasteGrades(mode) {
                this.pasteMode = mode;

                try {
                    // Read text from clipboard
                    const pastedText = await navigator.clipboard.readText();
                    const grades = pastedText.split(/\r?\n/).map(g => g.trim()).filter(g => g);

                    if (grades.length === 0) {
                        alert('No data found in clipboard. Please copy grades from Excel first.');
                        return;
                    }

                    // Show preview in modal
                    this.previewGrades = grades;
                } catch (err) {
                    console.error('Failed to read clipboard:', err);
                    alert('Failed to read clipboard. Please make sure you have copied data and granted clipboard permissions.');
                }
            },

            fillGrades() {
                const grades = this.previewGrades;
                const mode = this.pasteMode;
                const rows = document.querySelectorAll('tbody tr');
                let gradeIndex = 0;
                let filledCount = 0;

                rows.forEach(row => {
                    if (gradeIndex >= grades.length) return;

                    const gender = row.getAttribute('data-gender');
                    const select = row.querySelector('.grade-select');

                    // Check if this row should be filled based on mode
                    let shouldFill = false;
                    if (mode === 'all') {
                        shouldFill = true;
                    } else if (mode === 'female' && gender === 'F') {
                        shouldFill = true;
                    } else if (mode === 'male' && gender === 'M') {
                        shouldFill = true;
                    }

                    if (shouldFill) {
                        const rawGrade = grades[gradeIndex];
                        const formattedGrade = parseFloat(rawGrade).toFixed(2);
                        // Check if the grade exists in the select options
                        const option = Array.from(select.options).find(opt => opt.value === formattedGrade);
                        if (option) {
                            select.value = formattedGrade;
                            filledCount++;
                        }
                        gradeIndex++;
                    }
                });

                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-neutral-800 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                toast.textContent = `${filledCount} grade${filledCount !== 1 ? 's' : ''} filled successfully!`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);

                this.clearPreview();
            },

            clearPreview() {
                this.previewGrades = [];
                this.pasteMode = null;
            }
        }
    }
</script>

<?php
require('../partials/footer.php')
?>