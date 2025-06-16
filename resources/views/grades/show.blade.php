<x-app-layout>
    <form class="max-w-3xl mx-auto p-4" id="grades-form">
        <!-- Filter container -->
        <div class="flex justify-end items-center space-x-3 mb-4">
            <select id="sort_by" class="border border-gray-300 rounded px-3 py-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
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

        <div id="average-grade" class="mt-6 text-right font-semibold text-lg">
            Average Grade: <span id="average-value"></span>
        </div>

        <div id="grades-container" class="space-y-4">
            @foreach ($grades as $grade)
                <div 
                    class="grade-entry border border-gray-300 rounded p-4 transition-colors duration-300"
                    data-subject="{{ strtolower($grade->subject->name ?? '') }}"
                    data-grade="{{ $grade->grade }}"
                >
                    <p class="subject-name text-lg font-medium text-gray-900">{{ $grade->subject->name ?? 'N/A' }}</p>
                    <p class="grade-value text-gray-700 text-xl">{{ $grade->grade }}</p>
                </div>
            @endforeach
        </div>
    </form>

    <!-- pdfmake scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const gradesContainer = document.getElementById('grades-container');
            const sortBySelect = document.getElementById('sort_by');
            const searchInput = document.getElementById('search_text');
            const orderSelect = document.getElementById('order');
            const downloadPdfBtn = document.getElementById('download-pdf');
            const averageValueSpan = document.getElementById('average-value');

            function calculateAverage() {
                const visibleEntries = Array.from(gradesContainer.querySelectorAll('.grade-entry')).filter(e => e.style.display !== 'none');
                if (visibleEntries.length === 0) return 0;
                const total = visibleEntries.reduce((sum, entry) => sum + parseFloat(entry.dataset.grade), 0);
                return (total / visibleEntries.length).toFixed(2);
            }

            function filterAndSortGrades() {
                const sortBy = sortBySelect.value;
                const searchText = searchInput.value.trim().toLowerCase();
                const order = orderSelect.value;

                let entries = Array.from(gradesContainer.querySelectorAll('.grade-entry'));

                // Filter by search text on subject or grade
                entries.forEach(entry => {
                    let fieldValue = entry.dataset[sortBy] || '';
                    if (fieldValue.includes(searchText)) {
                        entry.style.display = '';
                    } else {
                        entry.style.display = 'none';
                    }
                });

                // Only visible entries for sorting
                let visibleEntries = entries.filter(e => e.style.display !== 'none');

                visibleEntries.sort((a, b) => {
                    if (sortBy === 'grade') {
                        let gradeA = parseFloat(a.dataset.grade);
                        let gradeB = parseFloat(b.dataset.grade);
                        return order === 'asc' ? gradeA - gradeB : gradeB - gradeA;
                    } else {
                        let valA = a.dataset[sortBy] || '';
                        let valB = b.dataset[sortBy] || '';
                        if (valA < valB) return order === 'asc' ? -1 : 1;
                        if (valA > valB) return order === 'asc' ? 1 : -1;
                        return 0;
                    }
                });

                visibleEntries.forEach(entry => gradesContainer.appendChild(entry));

                averageValueSpan.textContent = calculateAverage();
            }

            // Initial filter and sort + average calc
            filterAndSortGrades();

            // Event listeners
            sortBySelect.addEventListener('change', filterAndSortGrades);
            searchInput.addEventListener('input', filterAndSortGrades);
            orderSelect.addEventListener('change', filterAndSortGrades);

            downloadPdfBtn.addEventListener('click', function () {
                const entries = Array.from(gradesContainer.querySelectorAll('.grade-entry')).filter(e => e.style.display !== 'none');

                const body = [
                    [
                        { text: 'Subject', style: 'tableHeader' },
                        { text: 'Grade', style: 'tableHeader' }
                    ]
                ];

                entries.forEach(entry => {
                    const cap = str => str.charAt(0).toUpperCase() + str.slice(1);
                    body.push([
                        cap(entry.dataset.subject),
                        entry.dataset.grade
                    ]);
                });

                // Add average row at the end
                body.push([
                    { text: 'Average', colSpan: 1, bold: true, alignment: 'right' }, 
                    { text: averageValueSpan.textContent, bold: true }
                ]);

                const docDefinition = {
                    content: [
                        { text: 'My Grades Report', style: 'header' },
                        {
                            style: 'tableExample',
                            table: {
                                headerRows: 1,
                                widths: ['*', 'auto'],
                                body: body
                            },
                            layout: {
                                fillColor: function (rowIndex) {
                                    return rowIndex === 0 ? '#2980b9' : null;
                                },
                                hLineColor: () => '#ccc',
                                vLineColor: () => '#ccc'
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

                pdfMake.createPdf(docDefinition).download('my-grades-report.pdf');
            });
        });
    </script>
</x-app-layout>
