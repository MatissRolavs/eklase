<x-app-layout>
    <form method="POST" action="{{ route('grades.bulkUpdate') }}" id="grades-form" class="max-w-3xl mx-auto p-4">
        @csrf

        <!-- Filter container -->
        <div class="flex justify-end items-center space-x-3 mb-4">
            <select id="sort_by" class="border border-gray-300 rounded px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="name">Name</option>
                <option value="surname">Surname</option>
                <option value="subject">Subject</option>
                <option value="grade">Grade</option>
            </select>

            <input 
                type="text" 
                id="search_text" 
                placeholder="Enter search text..." 
                class="border border-gray-300 rounded px-3 py-1 w-48 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >

            <select id="order" class="border border-gray-300 rounded px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="asc">Lowest first</option>
                <option value="desc" selected>Highest first</option>
            </select>

            <!-- PDF Download Button -->
            <button 
                type="button" 
                id="download-pdf"
                class="ml-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
            >
                Download PDF
            </button>
        </div>

        <button 
            type="submit" 
            id="save-button"
            class="mb-6 px-4 py-2 bg-green-600 text-white rounded transition opacity-50 cursor-not-allowed"
            disabled
        >
            Save Changes
        </button>

        <div id="grades-container" class="space-y-4">
            @foreach ($grades as $grade)
                <div 
                    class="grade-entry border border-gray-300 rounded p-4 transition-colors duration-300" 
                    data-id="{{ $grade->id }}"
                    data-name="{{ strtolower($grade->student->full_name ?? '') }}"
                    data-surname="{{ strtolower($grade->student->surname ?? '') }}"
                    data-subject="{{ strtolower($grade->subject->name ?? '') }}"
                    data-grade="{{ $grade->grade }}"
                >
                    <div class="flex items-center space-x-6 mb-2">
                        <label class="flex items-center space-x-2 font-semibold text-gray-700">
                            <input type="checkbox" class="edit-toggle form-checkbox h-5 w-5 text-blue-600">
                            <span>Edit</span>
                        </label>
                        <label class="flex items-center space-x-2 font-semibold text-gray-700">
                            <input type="checkbox" class="delete-toggle form-checkbox h-5 w-5 text-red-600" name="delete_ids[]" value="{{ $grade->id }}">
                            <span>Delete</span>
                        </label>
                    </div>

                    <div class="grade-content space-y-1">
                        <p class="student-name text-lg font-medium text-gray-900">{{ $grade->student->full_name ?? 'N/A' }}</p>
                        <p class="subject-name text-gray-700">{{ $grade->subject->name ?? 'N/A' }}</p>

                        <input 
                            type="number" 
                            name="grades[{{ $grade->id }}]" 
                            value="{{ $grade->grade }}" 
                            class="grade-input mt-2 px-3 py-1 border border-gray-300 rounded w-20 text-center text-lg"
                            readonly
                        >
                    </div>
                </div>
            @endforeach
        </div>
    </form>

    <!-- pdfmake scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('grades-form');
            const saveButton = document.getElementById('save-button');
            const gradesContainer = document.getElementById('grades-container');
            const sortBySelect = document.getElementById('sort_by');
            const searchInput = document.getElementById('search_text');
            const orderSelect = document.getElementById('order');
            const downloadPdfBtn = document.getElementById('download-pdf');

            function updateSaveButtonState() {
                const edited = document.querySelectorAll('.edit-toggle:checked').length;
                const deleted = document.querySelectorAll('.delete-toggle:checked').length;

                if (edited || deleted) {
                    saveButton.disabled = false;
                    saveButton.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    saveButton.disabled = true;
                    saveButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            function filterAndSortGrades() {
                const sortBy = sortBySelect.value;
                const searchText = searchInput.value.trim().toLowerCase();
                const order = orderSelect.value;

                // Get all grade entries as array
                let entries = Array.from(gradesContainer.querySelectorAll('.grade-entry'));

                // Filter by search text ONLY in selected field
                entries.forEach(entry => {
                    const field = entry.dataset[sortBy] || '';
                    if (field.includes(searchText)) {
                        entry.style.display = '';
                    } else {
                        entry.style.display = 'none';
                    }
                });

                // Only keep visible entries for sorting
                let visibleEntries = entries.filter(e => e.style.display !== 'none');

                // Sort visible entries
                visibleEntries.sort((a, b) => {
                    if (sortBy === 'grade') {
                        // Numeric sort by grade
                        let gradeA = parseFloat(a.dataset.grade);
                        let gradeB = parseFloat(b.dataset.grade);

                        if (gradeA !== gradeB) {
                            return order === 'asc' ? gradeA - gradeB : gradeB - gradeA;
                        }
                        // If grades are equal, secondary sort alphabetically by subject
                        let subjectA = a.dataset.subject;
                        let subjectB = b.dataset.subject;
                        if (subjectA < subjectB) return -1;
                        if (subjectA > subjectB) return 1;
                        return 0;
                    } else {
                        // Alphabetical sort by chosen field
                        let valA = a.dataset[sortBy] || '';
                        let valB = b.dataset[sortBy] || '';
                        if (valA < valB) return order === 'asc' ? -1 : 1;
                        if (valA > valB) return order === 'asc' ? 1 : -1;
                        return 0;
                    }
                });

                // Reorder in DOM: append visible entries in new order
                visibleEntries.forEach(entry => gradesContainer.appendChild(entry));
            }

            // Setup edit/delete logic & update button state for each grade entry
            document.querySelectorAll('.grade-entry').forEach(entry => {
                const editCheckbox = entry.querySelector('.edit-toggle');
                const deleteCheckbox = entry.querySelector('.delete-toggle');
                const input = entry.querySelector('.grade-input');
                const content = entry.querySelector('.grade-content');

                input.dataset.original = input.value;

                editCheckbox.addEventListener('change', function () {
                    if (editCheckbox.checked) {
                        input.removeAttribute('readonly');
                        input.classList.add('ring', 'ring-blue-400');
                        entry.classList.remove('bg-red-100');
                        entry.classList.add('bg-blue-100');
                    } else {
                        input.setAttribute('readonly', true);
                        input.value = input.dataset.original;
                        input.classList.remove('ring', 'ring-blue-400');
                        if (!deleteCheckbox.checked) {
                            entry.classList.remove('bg-blue-100');
                        }
                    }
                    updateSaveButtonState();
                });

                deleteCheckbox.addEventListener('change', function () {
                    if (deleteCheckbox.checked) {
                        editCheckbox.checked = false;
                        editCheckbox.disabled = true;
                        input.setAttribute('readonly', true);
                        input.classList.remove('ring', 'ring-blue-400');

                        entry.classList.remove('bg-blue-100');
                        entry.classList.add('bg-red-100');

                        content.querySelectorAll('p').forEach(p => {
                            p.classList.add('line-through', 'text-red-600');
                        });
                    } else {
                        editCheckbox.disabled = false;

                        content.querySelectorAll('p').forEach(p => {
                            p.classList.remove('line-through', 'text-red-600');
                        });

                        if (editCheckbox.checked) {
                            entry.classList.add('bg-blue-100');
                            entry.classList.remove('bg-red-100');
                        } else {
                            entry.classList.remove('bg-red-100', 'bg-blue-100');
                        }
                    }
                    updateSaveButtonState();
                });
            });

            // Enable instant filtering/sorting on input changes
            sortBySelect.addEventListener('change', filterAndSortGrades);
            searchInput.addEventListener('input', filterAndSortGrades);
            orderSelect.addEventListener('change', filterAndSortGrades);

            // Initial filter & sort
            filterAndSortGrades();

            // Initial save button state
            updateSaveButtonState();

            // Confirm dialog on submit
            form.addEventListener('submit', function (e) {
                const edited = document.querySelectorAll('.edit-toggle:checked').length;
                const deleted = document.querySelectorAll('.delete-toggle:checked').length;

                if (edited || deleted) {
                    const confirmMessage = `You are about to:\n${edited ? '- Edit some grades\n' : ''}${deleted ? '- Delete some grades\n' : ''}\nAre you sure you want to continue?`;
                    if (!confirm(confirmMessage)) {
                        e.preventDefault();
                    }
                } else {
                    e.preventDefault();
                }
            });

            // PDF generation with pdfmake
            downloadPdfBtn.addEventListener('click', function () {
                // Get visible grade entries only
                const entries = Array.from(document.querySelectorAll('.grade-entry')).filter(e => e.style.display !== 'none');

                const body = [];

                // Table header
                body.push([
                    { text: 'Name', style: 'tableHeader' },
                    { text: 'Surname', style: 'tableHeader' },
                    { text: 'Subject', style: 'tableHeader' },
                    { text: 'Grade', style: 'tableHeader' }
                ]);

                // Rows
                entries.forEach(entry => {
                    const cap = str => str.charAt(0).toUpperCase() + str.slice(1);

                    body.push([
                        cap(entry.dataset.name),
                        cap(entry.dataset.surname),
                        cap(entry.dataset.subject),
                        entry.dataset.grade
                    ]);
                });

                const docDefinition = {
                    content: [
                        { text: 'Grades Report', style: 'header' },
                        {
                            style: 'tableExample',
                            table: {
                                headerRows: 1,
                                widths: ['*', '*', '*', 'auto'],
                                body: body
                            },
                            layout: {
                                fillColor: function (rowIndex) {
                                    return (rowIndex === 0) ? '#2980b9' : null;
                                },
                                hLineColor: function (i, node) {
                                    return '#ccc';
                                },
                                vLineColor: function (i, node) {
                                    return '#ccc';
                                }
                            }
                        }
                    ],
                    styles: {
                        header: {
                            fontSize: 18,
                            bold: true,
                            margin: [0, 0, 0, 10]
                        },
                        tableExample: {
                            margin: [0, 5, 0, 15]
                        },
                        tableHeader: {
                            bold: true,
                            fontSize: 13,
                            color: 'white',
                            fillColor: '#2980b9',
                            alignment: 'center'
                        }
                    },
                    defaultStyle: {
                        font: 'Roboto'
                    }
                };

                pdfMake.createPdf(docDefinition).download('grades-report.pdf');
            });
        });
    </script>
</x-app-layout>
